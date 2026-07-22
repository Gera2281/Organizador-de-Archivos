<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarpetaController;
use App\Http\Controllers\ArchivosController;

// Inicio de la aplicación y visualización de carpetas principales
Route::get('/', [CarpetaController::class, 'index']);
Route::get('/carpetas/search', [CarpetaController::class, 'search'])->name('carpetas.search');

Route::get('/crear/carpetas', [CarpetaController::class, 'CrearCarpetas'])->name('carpetas.crear');
Route::post('/agregar/carpetas', [CarpetaController::class, 'ValidarCarpetas'])->name('carpetas.guardar');
Route::get('/carpetas/{carpeta}', [CarpetaController::class, 'show'])->name('carpetas.show');

Route::get('/crear/carpetasarchivos/{carpeta}', [ArchivosController::class, 'CrearCarpetasA'])->name('archivos.crear-carpeta');
Route::post('/agregar/carpetasarchivos/{carpeta}', [ArchivosController::class, 'ValidarCarpetasA'])->name('archivos.guardar-carpeta');
Route::get('/archivos/{carpeta}/carpeta/{subcarpeta}', [ArchivosController::class, 'show'])->name('archivos.carpeta');

Route::get('/crear/archivos/{carpeta}', [ArchivosController::class, 'AggArchivos'])->name('archivos.crear');
Route::post('/agregar/archivos/{carpeta}', [ArchivosController::class, 'ValidarArchivos'])->name('archivos.guardar');

