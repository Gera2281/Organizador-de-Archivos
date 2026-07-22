<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarpetaController;
use App\Http\Controllers\ArchivosController;

//inicio de la aplicacion y visualizacion de escuelas
Route::get('/', [CarpetaController::class, 'index']);
//Rutas para buscar escuelas
Route::get('/escuelas/search', [CarpetaController::class, 'search'])->name('escuelas.search');

//Rutas para agregar y validar carpetas de escuelas
Route::get('/crear/carpetas', [CarpetaController::class, 'CrearCarpetasEscuelas'])->name('escuelas.creaRR');
Route::post('/agregar/carpetas', [CarpetaController::class, 'ValidarCarpetasEscuelas'])->name('escuelas.agg');
//Ruta para mostrar la vista lo que hay dentro de la carpeta escuela (Carpetas, Archivos)
Route::get('/escuelas/{escuela}', [CarpetaController::class, 'show'])->name('escuelas.show');

//Rutas para agregar y validar carpetas dentro de las carpetas de escuelas
Route::get('/crear/carpetasarchivos/{escuela}', [ArchivosController::class, 'CrearCarpetasA'])->name('archivos.creaRR');
Route::post('/agregar/carpetasarchivos/{escuela}', [ArchivosController::class, 'ValidarCarpetasA'])->name('archivos.agg');
//Rutas para mostrar la vista de lo que hay dentro de estas carpetas de arriba ↑
Route::get('/archivos/{escuela}/carpeta/{carpeta}', [ArchivosController::class, 'show'])->name('archivos.carpeta');

//Rutas para Agregar y validar archivos
Route::get('/crear/archivos/{escuela}', [ArchivosController::class, 'AggArchivos'])->name('archivos.crearArch');
Route::post('/agregar/archivos/{escuela}', [ArchivosController::class, 'ValidarArchivos'])->name('archivos.agregarArch');

