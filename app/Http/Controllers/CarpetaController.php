<?php

namespace App\Http\Controllers;

use App\Models\Carpeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CarpetaController extends Controller
{
    public function index()
    {
        // Obtener todas las carpetas iniciales
        $carpetas = Carpeta::all();
        return view('welcome', compact('carpetas'));
    } 

    public function search(Request $request)
    {
        $query = $request->input('query');
        $filter = $request->input('filter');

        $dbQuery = Carpeta::query();

        if ($query) {
            if ($filter === 'descripcion') {
                $dbQuery->where('descripcion', 'LIKE', '%' . $query . '%');
            } elseif ($filter === 'nombre_carpeta_principal') {
                $dbQuery->where('nombre_carpeta_principal', 'LIKE', '%' . $query . '%');
            } else {
                $dbQuery->where(function($q) use ($query) {
                    $q->where('descripcion', 'LIKE', '%' . $query . '%')
                      ->orWhere('nombre_carpeta_principal', 'LIKE', '%' . $query . '%');
                });
            }
        }

        $carpetas = $dbQuery->get();

        return response()->json($carpetas);
    }

    public function CrearCarpetas()
    {
        return view('Carpetas.Crear');
    }

    public function ValidarCarpetas(Request $request)
    {
        $request->validate([     //Validar que los campos no esten vacios y max caracteres
            'nombre_carpeta_principal' => 'required|max:30|unique:carpetas,nombre_carpeta_principal',
            'descripcion' => 'required|max:30',
        ]);

        $carpeta = new Carpeta();
        $carpeta->nombre_carpeta_principal = $request->nombre_carpeta_principal;
        $carpeta->descripcion = $request->descripcion;
        //$carpeta->user_id = Auth::id();  //Guardar el usuario que creo la carpeta
        $carpeta->save();

        // Crear la carpeta en public/archivos
        $folderName = $carpeta->nombre_carpeta_principal;
        $path = public_path('archivos/' . $folderName);

        File::makeDirectory($path, 0755, true, true);

        return redirect('/');
    }

    public function show(Carpeta $carpeta)
    {
        // Obtener la carpeta principal en public/archivos
        $archivosPath = public_path('archivos');

        // Buscar la carpeta principal
        $nombreCarpeta = (string)$carpeta->nombre_carpeta_principal;
        $rutaCarpeta = $archivosPath . '/' . $nombreCarpeta;

        if (!File::isDirectory($rutaCarpeta)) {
            return redirect('/')->with('error', 'Carpeta principal no encontrada');
        }

        // Obtener el contenido de la carpeta
        $rutaCarpeta = $archivosPath . '/' . $nombreCarpeta;
        $contenido = [];

        if (File::isDirectory($rutaCarpeta)) {
            // Obtener tanto archivos como directorios
            $items = File::files($rutaCarpeta);
            $subdirectorios = File::directories($rutaCarpeta);

            foreach ($items as $item) {
                $contenido[] = [
                    'nombre' => basename($item),
                    'ruta' => 'archivos/' . $nombreCarpeta . '/' . basename($item),
                    'tipo' => 'file'
                ];
            }

            foreach ($subdirectorios as $subdirectorio) {
                $contenido[] = [
                    'nombre' => basename($subdirectorio),
                    'ruta' => 'archivos/' . $nombreCarpeta . '/' . basename($subdirectorio),
                    'tipo' => 'dir'
                ];
            }
        }

        return view('Carpetas.show', compact('carpeta', 'contenido', 'nombreCarpeta'));
    }
}
