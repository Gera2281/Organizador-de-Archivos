<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArchivosController extends Controller
{

    public function CrearCarpetasA(\App\Models\Escuela $escuela)
    {
        return view('Archivos.CrearCarpeta', compact('escuela'));
    }

    public function ValidarCarpetasA(Request $request, \App\Models\Escuela $escuela)
    {
        $request->validate([     //Validar que los campos no esten vacios y max caracteres
            'nombre_carpeta' => 'required|max:50|unique:archivos,nombre_carpeta',
            'contenido' => 'required|max:50',
        ]);

        $archivo = new Archivo();
        $archivo->nombre_carpeta = $request->nombre_carpeta; //Darle valor al objeto
        $archivo->contenido = $request->contenido;
        //$archivo->user_id = Auth::id();  //Guardar el usuario que creo la carpeta
        $archivo->save();

        // Obtener la carpeta de la escuela en public/archivos
        $archivosPath = public_path('archivos');
        $escuelaCarpeta = (string)$escuela->nombre_carpeta_principal;
        $rutaCarpeta = $archivosPath . '/' . $escuelaCarpeta;

        if (File::isDirectory($rutaCarpeta)) {
            // Crear la carpeta dentro de la carpeta de la escuela
            $folderName = $archivo->nombre_carpeta;
            $path = public_path('archivos/' . $escuelaCarpeta . '/' . $folderName);
            File::makeDirectory($path, 0755, true, true);
        }

        return redirect()->route('escuelas.show', $escuela->id);
    }

    public function show(\App\Models\Escuela $escuela, $carpeta)
    {
        $archivosPath = public_path('archivos');
        $numeroCarpeta = (string)$escuela->nombre_carpeta_principal;
        $rutaCarpeta = $archivosPath . '/' . $numeroCarpeta . '/' . $carpeta;

        if (!File::isDirectory($rutaCarpeta)) {
            return redirect()->route('escuelas.show', $escuela->id)->with('error', 'Carpeta no encontrada');
        }

        $contenido = [];
        $items = File::files($rutaCarpeta);
        $subdirectorios = File::directories($rutaCarpeta);

        foreach ($items as $item) {
            $contenido[] = [
                'nombre' => basename($item),
                'ruta'   => 'archivos/' . $numeroCarpeta . '/' . $carpeta . '/' . basename($item),
                'tipo'   => 'file'
            ];
        }

        foreach ($subdirectorios as $subdirectorio) {
            $contenido[] = [
                'nombre' => basename($subdirectorio),
                'ruta'   => 'archivos/' . $numeroCarpeta . '/' . $carpeta . '/' . basename($subdirectorio),
                'tipo'   => 'dir'
            ];
        }

        // Buscamos el registro de la carpeta en la base de datos
        $archivoDb = \App\Models\Archivo::where('nombre_carpeta', $carpeta)->first();
        $contenidoReal = $archivoDb ? $archivoDb->contenido : 'Sin descripción';

        // Creamos un objeto simple para la vista (nombre de la carpeta y escuela)
        $archivo = (object)[
            'id'             => $archivoDb ? $archivoDb->id : null,
            'nombre_carpeta' => $carpeta,
            'contenido'      => $contenidoReal,
            'escuela_id'     => $escuela->id,
            'escuela'        => $escuela,
        ];

        return view('Archivos.VerCarpeta', compact('archivo', 'contenido', 'escuela', 'carpeta'));
    }
    
    
    public function AggArchivos(\App\Models\Escuela $escuela)
    {
        return view('Archivos.Creararchivo', compact('escuela'));
    }

    public function ValidarArchivos(Request $request, \App\Models\Escuela $escuela)
    {
        $request->validate([ //Valida que los campos requeridos
            'documento'      => 'required|mimes:jpeg,png,webp,jpg,pdf|max:2048',
        ]);

        $archivo = new Archivo();
        $escuelaCarpeta = (string)$escuela->nombre_carpeta_principal;

        if ($request->hasFile('documento')) { //Si subieron un archivo
            $file = $request->file('documento');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Guarda físicamente el archivo en la carpeta de la escuela
            $file->move(public_path('archivos/' . $escuelaCarpeta), $filename);
            
            // Guarda la ruta en la base de datos
            $archivo->imagen = 'archivos/' . $escuelaCarpeta . '/' . $filename;
            
            // Llenamos los campos obligatorios de la tabla archivos para evitar un error
            $archivo->nombre_carpeta = $filename;
            $archivo->contenido      = 'Archivo subido';
        }
        
        $archivo->save();

        return redirect()->route('escuelas.show', $escuela->id);
    }
}