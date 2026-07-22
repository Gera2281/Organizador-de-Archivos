<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nombre_carpeta_principal', 'descripcion'])]
class Escuela extends Model
{
    use HasFactory;

    protected $table = 'carpetas';
}
