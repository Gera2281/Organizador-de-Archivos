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
                <h1 class="h3 mb-0">Agregar Nueva Carpeta</h1>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('archivos.guardar-carpeta', $carpeta->id) }}" method="POST" class="card p-4">
                @csrf
                <div class="mb-3">
                    <label for="nombre_carpeta" class="form-label">Nombre de la Carpeta</label>
                    <input type="text" class="form-control @error('nombre_carpeta') is-invalid @enderror" id="nombre_carpeta" name="nombre_carpeta" maxlength="50" value="{{ old('nombre_carpeta') }}" required>
                    @error('nombre_carpeta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <input type="text" class="form-control @error('contenido') is-invalid @enderror" id="contenido" name="contenido" maxlength="50" value="{{ old('contenido') }}" required>
                    @error('contenido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Crear Carpeta</button>
            </form>
        </div>
    </div>
</div>
@endsection