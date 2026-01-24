<?php

use App\Http\Controllers\DesktopController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArquivosController;
use Illuminate\Support\Facades\Route;

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

Route::get('/arquivos/lista', [ArquivosController::class, 'lista'])->name('arquivosLista');

Route::get('/sistema', function () {
    return view('sistema');
});

Route::get('/arquivos/lista', [ArchiveController::class, 'index'])->name('arquivosLista');

Route::get('/sistema', [SystemController::class, 'index']);

Route::get('/sistema/pasta/{id}', [SystemController::class, 'openFolder']);

Route::get('/sistema', [DesktopController::class, 'index']);

