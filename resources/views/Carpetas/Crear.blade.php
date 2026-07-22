@extends('partials.head')
@section('title', 'SIIE')

@section('contentido')
<div class="container mt-4">
    <div class="p-3 mb-4 bg-light border rounded-3">
        <div class="d-flex align-items-center">
            <a href="/" class="btn btn-outline-secondary me-3" title="Atrás">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0">Crear Carpeta Principal</h1>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('carpetas.guardar') }}" method="POST" class="card p-4">
                @csrf
                <div class="mb-3"><label for="nombre_carpeta_principal" class="form-label">Nombre de Carpeta</label><input type="text" class="form-control" id="nombre_carpeta_principal" name="nombre_carpeta_principal" maxlength="30" required></div>
                <div class="mb-3"><label for="descripcion" class="form-label">Descripcion</label><input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="30" required></div>
                <button type="submit" class="btn btn-primary w-100">Crear Carpeta</button>
            </form>
        </div>
    </div>
</div>
@endsection