<?php

namespace App\Http\Controllers;

use App\Models\AdminAccount;
use App\Models\AdminInviteKey;
use App\Models\Archive;
use App\Models\File as SystemFile;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FileSystem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    private const CLASSIFICATIONS = [
        'neutralizado',
        'seguro',
        'totin',
        'ísop',
        'denus',
        'setis',
    ];

    private const FILE_TYPES = [
        'txt',
        'png',
        'mp3',
        'mp4',
    ];

    public function index(Request $request)
    {
        if (! $this->ownerExists()) {
            return view('admin.setup');
        }

        $admin = $this->currentAdmin($request);

        if (! $admin) {
            return view('admin.login', [
                'canRegister' => AdminInviteKey::where('active', true)->whereNull('used_at')->exists(),
            ]);
        }

        return view('admin.dashboard', [
            'admin' => $admin,
            'archives' => Archive::orderByDesc('created_at')->get(),
            'folders' => Folder::with('files')->orderBy('name')->get(),
            'classifications' => self::CLASSIFICATIONS,
            'fileTypes' => self::FILE_TYPES,
            'adminAccounts' => AdminAccount::orderByRaw("role = 'owner' desc")->orderBy('name')->get(),
            'inviteKeys' => AdminInviteKey::with(['creator', 'usedBy'])->latest()->limit(12)->get(),
        ]);
    }

    public function setup(Request $request)
    {
        abort_if($this->ownerExists(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:admin_accounts,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = AdminAccount::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'owner',
            'active' => true,
        ]);

        $request->session()->regenerate();
        $request->session()->put('admin_account_id', $admin->id);

        return redirect()->route('admin.index')->with('status', 'Conta de dono criada.');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = AdminAccount::where('email', $data['email'])->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
        }

        if (! $admin->active) {
            return back()->withErrors(['email' => 'Conta administrativa desabilitada.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_account_id', $admin->id);

        return redirect()->route('admin.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_account_id');
        $request->session()->regenerateToken();

        return redirect()->route('admin.index');
    }

    public function registerForm()
    {
        abort_if(! $this->ownerExists(), 404);

        return view('admin.register');
    }

    public function register(Request $request)
    {
        abort_if(! $this->ownerExists(), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:admin_accounts,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'invite_key' => ['required', 'string', 'max:120'],
        ]);

        $invite = AdminInviteKey::where('code_hash', $this->hashInviteCode($data['invite_key']))
            ->where('active', true)
            ->whereNull('used_at')
            ->first();

        if (! $invite) {
            return back()->withErrors(['invite_key' => 'Chave inválida ou já utilizada.'])->withInput();
        }

        $admin = AdminAccount::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $invite->role,
            'active' => true,
        ]);

        $invite->update([
            'active' => false,
            'used_by' => $admin->id,
            'used_at' => now(),
        ]);

        $request->session()->regenerate();
        $request->session()->put('admin_account_id', $admin->id);

        return redirect()->route('admin.index')->with('status', 'Conta administrativa criada.');
    }

    public function createInvite(Request $request)
    {
        $owner = $this->requireOwner($request);

        $data = $request->validate([
            'role' => ['required', Rule::in(['admin'])],
        ]);

        do {
            $code = $this->generateInviteCode($data['role']);
            $hash = $this->hashInviteCode($code);
        } while (AdminInviteKey::where('code_hash', $hash)->exists());

        AdminInviteKey::create([
            'code_hash' => $hash,
            'role' => $data['role'],
            'created_by' => $owner->id,
            'active' => true,
        ]);

        return back()
            ->with('generated_admin_key', $code)
            ->with('admin_tab', 'keys')
            ->with('status', 'Chave de admin gerada.');
    }

    public function disableAccount(Request $request, AdminAccount $adminAccount)
    {
        $owner = $this->requireOwner($request);

        abort_if($adminAccount->id === $owner->id || $adminAccount->isOwner(), 403);

        $adminAccount->update([
            'active' => false,
            'disabled_at' => now(),
        ]);

        return back()->with('admin_tab', 'keys')->with('status', 'Conta administrativa desabilitada.');
    }

    public function enableAccount(Request $request, AdminAccount $adminAccount)
    {
        $owner = $this->requireOwner($request);

        abort_if($adminAccount->id === $owner->id || $adminAccount->isOwner(), 403);

        $adminAccount->update([
            'active' => true,
            'disabled_at' => null,
        ]);

        return back()->with('admin_tab', 'keys')->with('status', 'Conta administrativa reativada.');
    }

    public function storeArchive(Request $request)
    {
        $this->requireAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'identifier' => ['required', 'string', 'max:80', 'unique:archives,identifier'],
            'classification' => ['required', Rule::in(self::CLASSIFICATIONS)],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
            'description' => ['required', 'string', 'max:4000'],
        ]);

        $imagePath = $this->storePublicUpload($request, 'image', 'images/entidades');

        Archive::create([
            'name' => $data['name'],
            'identifier' => $data['identifier'],
            'classification' => $data['classification'],
            'image_path' => $imagePath,
            'description' => $data['description'],
        ]);

        return back()->with('admin_tab', 'archives')->with('status', 'Arquivo de entidade criado.');
    }

    public function storeFolder(Request $request)
    {
        $this->requireAdmin($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        Folder::create([
            'name' => $data['name'],
            'icon' => 'folder.png',
        ]);

        return back()->with('admin_tab', 'folders')->with('status', 'Pasta criada no sistema.');
    }

    public function storeFile(Request $request)
    {
        $this->requireAdmin($request);

        $data = $request->validate([
            'folder_id' => ['required', 'exists:folders,id'],
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(self::FILE_TYPES)],
        ]);

        $payload = [
            'folder_id' => $data['folder_id'],
            'name' => $data['name'],
            'type' => $data['type'],
        ];

        if ($data['type'] === 'txt') {
            $validated = $request->validate([
                'content' => ['required', 'string', 'max:20000'],
            ]);

            $payload['content'] = $validated['content'];
        }

        if ($data['type'] === 'png') {
            $request->validate([
                'system_image' => ['required', 'image', 'mimes:png,jpg,jpeg,webp,gif', 'max:5120'],
            ]);

            $payload['path'] = $this->storePublicUpload($request, 'system_image', 'images/sistema');
        }

        if ($data['type'] === 'mp3') {
            $request->validate([
                'system_audio' => ['required', 'file', 'mimes:mp3,mpga,wav,ogg', 'max:15360'],
            ]);

            $payload['path'] = $this->storePublicUpload($request, 'system_audio', 'sounds/sistema');
        }

        if ($data['type'] === 'mp4') {
            $validated = $request->validate([
                'content' => ['required', 'string', 'max:2048'],
            ]);

            $payload['content'] = $this->normalizeVideoEmbed($validated['content']);
        }

        SystemFile::create($payload);

        return back()->with('admin_tab', 'files')->with('status', 'Arquivo inserido na pasta.');
    }

    public function destroyArchive(Request $request, Archive $archive)
    {
        $this->requireAdmin($request);

        $this->deletePublicFile($archive->image_path);
        $archive->delete();

        return $this->deleteResponse($request, 'archives', 'Arquivo de entidade removido.', [
            'archive_count' => Archive::count(),
        ]);
    }

    public function destroyFolder(Request $request, Folder $folder)
    {
        $this->requireAdmin($request);

        $folder->load('files');

        foreach ($folder->files as $file) {
            $this->deletePublicFile($file->path);
        }

        $folder->delete();

        return $this->deleteResponse($request, 'folders', 'Pasta removida com os arquivos dela.');
    }

    public function destroyFile(Request $request, SystemFile $file)
    {
        $this->requireAdmin($request);

        $this->deletePublicFile($file->path);
        $file->delete();

        return $this->deleteResponse($request, 'files', 'Arquivo removido da pasta.');
    }

    private function deleteResponse(Request $request, string $tab, string $message, array $payload = [])
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'tab' => $tab,
                ...$payload,
            ]);
        }

        return back()->with('admin_tab', $tab)->with('status', $message);
    }

    private function currentAdmin(Request $request): ?AdminAccount
    {
        $id = $request->session()->get('admin_account_id');

        if (! $id) {
            return null;
        }

        $admin = AdminAccount::find($id);

        if (! $admin || ! $admin->active) {
            $request->session()->forget('admin_account_id');

            return null;
        }

        return $admin;
    }

    private function requireAdmin(Request $request): AdminAccount
    {
        $admin = $this->currentAdmin($request);

        abort_if(! $admin, 403);

        return $admin;
    }

    private function requireOwner(Request $request): AdminAccount
    {
        $admin = $this->requireAdmin($request);

        abort_if(! $admin->isOwner(), 403);

        return $admin;
    }

    private function ownerExists(): bool
    {
        return AdminAccount::where('role', 'owner')->exists();
    }

    private function generateInviteCode(string $role): string
    {
        $parts = [
            'NOIR',
            Str::upper($role),
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
        ];

        return implode('-', $parts);
    }

    private function hashInviteCode(string $code): string
    {
        return hash('sha256', Str::upper(trim($code)));
    }

    private function storePublicUpload(Request $request, string $field, string $directory): string
    {
        $file = $request->file($field);
        $targetDirectory = public_path($directory);

        if (! FileSystem::isDirectory($targetDirectory)) {
            FileSystem::makeDirectory($targetDirectory, 0755, true);
        }

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($baseName) ?: 'arquivo';
        $fileName = $safeName.'-'.now()->format('YmdHis').'-'.Str::lower(Str::random(6)).'.'.$file->getClientOriginalExtension();

        $file->move($targetDirectory, $fileName);

        return trim($directory.'/'.$fileName, '/');
    }

    private function deletePublicFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $path = trim($path, '/');
        $allowedPrefixes = [
            'images/entidades/',
            'images/sistema/',
            'sounds/sistema/',
        ];

        if (! Str::startsWith($path, $allowedPrefixes)) {
            return;
        }

        $fullPath = public_path($path);
        $publicRoot = realpath(public_path());
        $realPath = realpath($fullPath);

        if (! $publicRoot || ! $realPath || ! Str::startsWith($realPath, $publicRoot.DIRECTORY_SEPARATOR)) {
            return;
        }

        if (FileSystem::isFile($realPath)) {
            FileSystem::delete($realPath);
        }
    }

    private function normalizeVideoEmbed(string $url): string
    {
        $url = trim($url);

        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }

        $parts = parse_url($url);

        if (! $parts || empty($parts['host'])) {
            return $url;
        }

        $host = str_replace('www.', '', $parts['host']);

        if ($host === 'youtu.be' && ! empty($parts['path'])) {
            return 'https://www.youtube.com/embed/'.ltrim($parts['path'], '/');
        }

        if ($host === 'youtube.com' && ! empty($parts['query'])) {
            parse_str($parts['query'], $query);

            if (! empty($query['v'])) {
                return 'https://www.youtube.com/embed/'.$query['v'];
            }
        }

        return $url;
    }
}
