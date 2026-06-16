<?php

use App\Http\Controllers\DesktopController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArquivosController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\TerminalCommand;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/organizacao', function () {
    return view('organizacao');
});

Route::get('/protocolos', function () {
    return view('protocolos');
});

Route::get('/arquivos', function () {
    return view('arquivos');
})->name('arquivos');

Route::post('/arquivos/unlock', [ArquivosController::class, 'unlock'])->name('arquivos.unlock');

Route::get('/arquivos/lista', [ArchiveController::class, 'index'])->name('arquivosLista');

Route::get('/sistema', [DesktopController::class, 'index'])->name('sistema');

Route::get('/sistema/terminal', function () {
    return view('terminal');
})->name('sistema.terminal');

Route::get('/sistema/pasta/{id}', [SystemController::class, 'openFolder']);



Route::get('/internal/migrate', function () {

    $token = request()->query('token');

    if (!$token || $token !== env('MIGRATION_TOKEN')) {
        abort(403);
    }

    try {

        Artisan::call('migrate', [
            '--force' => true
        ]);

        return response()->json([
            'success' => true,
            'output' => Artisan::output(),
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'trace' => app()->environment('local')
                ? $e->getTraceAsString()
                : null,
        ], 500);

    }

});



Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::post('/setup', [AdminController::class, 'setup'])->name('setup');

    Route::post('/login', [AdminController::class, 'login'])->name('login');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('/registrar', [AdminController::class, 'registerForm'])->name('register.form');

    Route::post('/registrar', [AdminController::class, 'register'])->name('register');

    Route::post('/chaves', [AdminController::class, 'createInvite'])->name('keys.store');

    Route::post('/contas/{adminAccount}/desabilitar', [AdminController::class, 'disableAccount'])->name('accounts.disable');

    Route::post('/contas/{adminAccount}/reativar', [AdminController::class, 'enableAccount'])->name('accounts.enable');

    Route::post('/arquivos', [AdminController::class, 'storeArchive'])->name('archives.store');

    Route::delete('/arquivos/{archive}', [AdminController::class, 'destroyArchive'])->name('archives.destroy');

    Route::post('/pastas', [AdminController::class, 'storeFolder'])->name('folders.store');

    Route::delete('/pastas/{folder}', [AdminController::class, 'destroyFolder'])->name('folders.destroy');

    Route::post('/sistema-arquivos', [AdminController::class, 'storeFile'])->name('files.store');

    Route::delete('/sistema-arquivos/{file}', [AdminController::class, 'destroyFile'])->name('files.destroy');

});

Route::get('/images/{path}', function ($path) {

    $file = public_path('images/' . $path);

    if (!file_exists($file)) {
        abort(404);
    }

    return response()->file($file);

})->where('path', '.*');


Route::get('/sounds/{path}', function ($path) {

    $file = public_path('sounds/' . $path);

    abort_unless(file_exists($file), 404);

    return response()->file($file);

})->where('path', '.*');

Route::get('/build/{path}', function ($path) {

    $file = public_path('build/' . $path);

    abort_unless(file_exists($file), 404);

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $mimeTypes = [
        'css' => 'text/css',
        'js'  => 'application/javascript',
        'json'=> 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg'=> 'image/jpeg',
        'svg' => 'image/svg+xml',
        'woff'=> 'font/woff',
        'woff2'=> 'font/woff2',
    ];

    return response(
        file_get_contents($file),
        200,
        [
            'Content-Type' => $mimeTypes[$ext]
                ?? mime_content_type($file)
                ?? 'application/octet-stream',

            'Cache-Control' => 'public, max-age=31536000'
        ]
    );

})->where('path', '.*');

Route::post(
    '/terminal-command',
    [AdminController::class, 'storeTerminalCommand']
)->name('terminal.store');

Route::delete(
    '/terminal-command/{terminalCommand}',
    [AdminController::class, 'destroyTerminalCommand']
)->name('terminal.destroy');

Route::get('/api/terminal-commands', function () {

    return TerminalCommand::where('active', true)
        ->get(['command', 'response']);

});