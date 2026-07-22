<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Carpeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArchivosController extends Controller
{

    public function CrearCarpetasA(Carpeta $carpeta)
    {
        return view('Archivos.CrearCarpeta', compact('carpeta'));
    }

    public function ValidarCarpetasA(Request $request, Carpeta $carpeta)
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

        // Obtener la carpeta principal en public/archivos
        $archivosPath = public_path('archivos');
        $nombreCarpeta = (string)$carpeta->nombre_carpeta_principal;
        $rutaCarpeta = $archivosPath . '/' . $nombreCarpeta;

        if (File::isDirectory($rutaCarpeta)) {
            // Crear la carpeta dentro de la carpeta de la escuela
            $folderName = $archivo->nombre_carpeta;
            $path = public_path('archivos/' . $nombreCarpeta . '/' . $folderName);
            File::makeDirectory($path, 0755, true, true);
        }

        return redirect()->route('carpetas.show', $carpeta->id);
    }

    public function show(Carpeta $carpeta, $subcarpeta)
    {
        $archivosPath = public_path('archivos');
        $nombreCarpeta = (string)$carpeta->nombre_carpeta_principal;
        $rutaCarpeta = $archivosPath . '/' . $nombreCarpeta . '/' . $subcarpeta;

        if (!File::isDirectory($rutaCarpeta)) {
            return redirect()->route('carpetas.show', $carpeta->id)->with('error', 'Carpeta no encontrada');
        }

        $contenido = [];
        $items = File::files($rutaCarpeta);
        $subdirectorios = File::directories($rutaCarpeta);

        foreach ($items as $item) {
            $contenido[] = [
                'nombre' => basename($item),
                'ruta'   => 'archivos/' . $nombreCarpeta . '/' . $subcarpeta . '/' . basename($item),
                'tipo'   => 'file'
            ];
        }

        foreach ($subdirectorios as $subdirectorio) {
            $contenido[] = [
                'nombre' => basename($subdirectorio),
                'ruta'   => 'archivos/' . $nombreCarpeta . '/' . $subcarpeta . '/' . basename($subdirectorio),
                'tipo'   => 'dir'
            ];
        }

        // Buscamos el registro de la carpeta en la base de datos
        $archivoDb = Archivo::where('nombre_carpeta', $subcarpeta)->first();
        $contenidoReal = $archivoDb ? $archivoDb->contenido : 'Sin descripción';

        // Creamos un objeto simple para la vista (nombre de la carpeta y escuela)
        $archivo = (object)[
            'id'             => $archivoDb ? $archivoDb->id : null,
            'nombre_carpeta' => $subcarpeta,
            'contenido'      => $contenidoReal,
            'carpeta_id'     => $carpeta->id,
            'carpeta'        => $carpeta,
        ];

        return view('Archivos.VerCarpeta', compact('archivo', 'contenido', 'carpeta', 'subcarpeta'));
    }
    
    
    public function AggArchivos(Carpeta $carpeta)
    {
        return view('Archivos.Creararchivo', compact('carpeta'));
    }

    public function ValidarArchivos(Request $request, Carpeta $carpeta)
    {
        $request->validate([ //Valida que los campos requeridos
            'documento'      => 'required|mimes:jpeg,png,webp,jpg,pdf|max:2048',
        ]);

        $archivo = new Archivo();
        $nombreCarpeta = (string)$carpeta->nombre_carpeta_principal;

        if ($request->hasFile('documento')) { //Si subieron un archivo
            $file = $request->file('documento');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Guarda físicamente el archivo en la carpeta de la escuela
            $file->move(public_path('archivos/' . $nombreCarpeta), $filename);
            
            // Guarda la ruta en la base de datos
            $archivo->imagen = 'archivos/' . $nombreCarpeta . '/' . $filename;
            
            // Llenamos los campos obligatorios de la tabla archivos para evitar un error
            $archivo->nombre_carpeta = $filename;
            $archivo->contenido      = 'Archivo subido';
        }
        
        $archivo->save();

        return redirect()->route('carpetas.show', $carpeta->id);
    }
}