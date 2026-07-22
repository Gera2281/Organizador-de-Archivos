<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('escuelas', 'carpetas');

        Schema::table('carpetas', function (Blueprint $table) {
            $table->renameColumn('numero_escuela', 'nombre_carpeta_principal');
            $table->renameColumn('ctt', 'descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('carpetas', function (Blueprint $table) {
            $table->renameColumn('nombre_carpeta_principal', 'numero_escuela');
            $table->renameColumn('descripcion', 'ctt');
        });

        Schema::rename('carpetas', 'escuelas');
    }
};