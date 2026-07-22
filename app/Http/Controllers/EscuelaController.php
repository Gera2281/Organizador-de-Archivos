<?php

namespace App\Http\Controllers;

use App\Models\Escuela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EscuelaController extends Controller
{
    public function index()
    {
        // Obtener todas las escuelas iniciales
        $escuelas = Escuela::all();
        return view('welcome', compact('escuelas'));
    } 

    public function search(Request $request)
    {
        $query = $request->input('query');
        $filter = $request->input('filter');

        $dbQuery = Escuela::query();

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

        $escuelas = $dbQuery->get();

        return response()->json($escuelas);
    }

    public function CrearCarpetasEscuelas()
    {
        return view('Escuelas.Crear');
    }

    public function ValidarCarpetasEscuelas(Request $request)
    {
        $request->validate([     //Validar que los campos no esten vacios y max caracteres
            'nombre_carpeta_principal' => 'required|max:30|unique:carpetas,nombre_carpeta_principal',
            'descripcion' => 'required|max:30',
        ]);

        $escuela = new Escuela();
        $escuela->nombre_carpeta_principal = $request->nombre_carpeta_principal;
        $escuela->descripcion = $request->descripcion;
        //$escuela->user_id = Auth::id();  //Guardar el usuario que creo la carpeta
        $escuela->save();

        // Crear la carpeta en public/archivos
        $folderName = $escuela->nombre_carpeta_principal;
        $path = public_path('archivos/' . $folderName);

        File::makeDirectory($path, 0755, true, true);

        return redirect('/');
    }

    public function show(Escuela $escuela)
    {
        // Obtener la carpeta de la escuela en public/archivos
        $archivosPath = public_path('archivos');

        // Buscar la carpeta de la escuela
        $escuelaCarpeta = (string)$escuela->nombre_carpeta_principal;
        $rutaCarpeta = $archivosPath . '/' . $escuelaCarpeta;

        if (!File::isDirectory($rutaCarpeta)) {
            return redirect('/')->with('error', 'Carpeta de escuela no encontrada');
        }

        // Obtener el contenido de la carpeta
        $rutaCarpeta = $archivosPath . '/' . $escuelaCarpeta;
        $contenido = [];

        if (File::isDirectory($rutaCarpeta)) {
            // Obtener tanto archivos como directorios
            $items = File::files($rutaCarpeta);
            $subdirectorios = File::directories($rutaCarpeta);

            foreach ($items as $item) {
                $contenido[] = [
                    'nombre' => basename($item),
                    'ruta' => 'archivos/' . $escuelaCarpeta . '/' . basename($item),
                    'tipo' => 'file'
                ];
            }

            foreach ($subdirectorios as $subdirectorio) {
                $contenido[] = [
                    'nombre' => basename($subdirectorio),
                    'ruta' => 'archivos/' . $escuelaCarpeta . '/' . basename($subdirectorio),
                    'tipo' => 'dir'
                ];
            }
        }

        return view('Escuelas.show', compact('escuela', 'contenido', 'escuelaCarpeta'));
    }
}
