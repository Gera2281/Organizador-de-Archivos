<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nombre_carpeta', 'contenido', 'imagen'])]
class Archivo extends Model
{
    use HasFactory;

    protected $table = 'archivos';
}