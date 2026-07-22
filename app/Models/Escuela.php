<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['numero_escuela', 'ctt'])]
class Escuela extends Model
{
    use HasFactory;

    protected $table = 'escuelas';
}
