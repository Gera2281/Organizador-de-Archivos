@extends('partials.head')
@section('title', 'SIIE')

@section('contentido')
<div class="container mt-4">
    <div class="p-3 mb-4 bg-light border rounded-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('carpetas.show', $carpeta->id) }}" class="btn btn-outline-secondary me-3" title="Atrás">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0">Agregar Nuevo Archivo</h1>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('archivos.guardar', $carpeta->id) }}" method="POST" class="card p-4" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="documento" class="form-label">Selecciona un Documento</label>
                    <input type="file" class="form-control" id="documento" name="documento" accept="image/jpeg, image/png, image/webp, application/pdf">
                </div>
                <button type="submit" class="btn btn-primary w-100">Crear Archivo</button>
            </form>
        </div>
    </div>
</div>
@endsection