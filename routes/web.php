<?php

use App\Http\Controllers\DesktopController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArquivosController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/debug-files', function () {
    return response()->json([
        'manifest' => file_exists(public_path('build/manifest.json')),
        'css' => file_exists(public_path('build/assets/home-BSWOmypJ.css')),
        'asset_url' => asset('build/assets/home-BSWOmypJ.css'),
        'public_path' => public_path(),
    ]);
});