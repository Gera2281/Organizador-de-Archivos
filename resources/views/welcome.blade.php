@extends('partials.head')

@section('contentido')
<link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">ORGANIZADOR - JGCLL</h1>
        </div>
    </div>
</div>
<div class="container my-4">
    <div class="search-container d-flex align-items-center gap-2">
        <div class="input-group search-input-group flex-grow-1">
            <span class="input-group-text bg-transparent border-0 d-flex align-items-center">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="search-input" class="form-control" placeholder="Buscar carpetas...">
            <button class="btn btn-filter dropdown-toggle d-flex align-items-center gap-2" type="button"
                data-bs-toggle="dropdown" aria-expanded="false" id="filter-dropdown-btn">
                <i class="bi bi-sliders"></i> <span id="filter-btn-text">Filtrar</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom border-0 shadow mt-2">
                <li>
                    <h6 class="dropdown-header fw-bold text-dark">Filtrar por: </h6>
                </li>
                <li>
                    <a class="dropdown-item dropdown-item-custom" href="#" data-filter="descripcion" id="filter-descripcion-item">
                        <i class="bi bi-card-text me-2 text-primary"></i> Descripcion
                    </a>
                </li>
                <li>
                    <a class="dropdown-item dropdown-item-custom" href="#" data-filter="nombre_carpeta_principal" id="filter-nombre-item">
                        <i class="bi bi-hash me-2 text-success"></i> Nombre de la Carpeta
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item dropdown-item-custom text-danger fw-semibold" href="#" data-filter="all" id="filter-clear-item">
                        <i class="bi bi-trash me-2"></i> Limpiar Filtros
                    </a>
                </li>
            </ul>
        </div>
        <!--boton para crear carpetas -->
        <div class="d-flex align-items-center gap-0">
            <a href="{{ route('escuelas.creaRR') }}" class="btn btn-filter" type="button" style="border-radius: 15px;" title="Agregar nueva carpeta">
                <span id="aggregate-btn-text"></span>
                <i class="bi bi-folder-plus fs-3 text-primary"></i>
            </a>
        </div>
    </div>
    <div id="schools-container" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4 justify-content-center">
        @forelse ($escuelas as $escuela)
        <div class="col school-card-wrapper" 
             data-escuela-id="{{ $escuela->id }}" 
             data-escuela-numero="{{ $escuela->nombre_carpeta_principal }}">
            <a href="{{ route('escuelas.show', $escuela->id) }}" class="text-decoration-none text-dark">
                <div class="card folder-card h-100 position-relative shadow-sm border-0 text-center bg-white">
                    <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                        <div class="position-relative d-inline-block">
                            <i class="bi bi-folder-fill" style="font-size: 4.5rem; color: rgba(7, 0, 147, 0.59);"></i>
                        </div>
                        <span class="text-muted fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <strong>{{ $escuela->nombre_carpeta_principal }}</strong></span>
                        <span class="text-muted mt-1 d-block" style="font-size: 0.7rem; opacity: 0.85;">{{ $escuela->descripcion }}</span>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5 w-100" id="no-results-box">
            <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3 fw-medium">No se encontraron carpetas con la información proporcionada.</p>
        </div>
        @endforelse
    </div>

    <!-- Menú contextual personalizado -->
    <div id="context-menu" class="dropdown-menu dropdown-menu-custom shadow border-0" style="display: none; position: absolute;">
        <h6 class="dropdown-header fw-bold">Opciones</h6>
        <a class="dropdown-item dropdown-item-custom" href="#" id="context-open">
            <i class="bi bi-folder2-open me-2"></i>Abrir
        </a>
        <a class="dropdown-item dropdown-item-custom" href="#" id="context-edit">
            <i class="bi bi-pencil me-2"></i>Editar
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item dropdown-item-custom text-danger" href="#" id="context-delete">
            <i class="bi bi-trash me-2"></i>Eliminar
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('scripts/welcome_buscador.js') }}"></script>
@endpush