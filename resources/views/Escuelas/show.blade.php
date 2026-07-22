@extends('partials.head')

@section('contentido')
<style>
    @keyframes slideInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    /* Animación para el modal */
    .modal.fade .modal-dialog {
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform: scale(0.9) translateY(-20px);
    }
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }
    .file-card, .folder-card {
        animation: slideInUp 0.6s cubic-bezier(0.25, 0.8, 0.25, 1) both;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .folder-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        cursor: pointer;
    }
    .file-icon {
        font-size: 3rem;
    }
    .folder-icon {
        color: #5d78ff;
    }
    .file-generic-icon {
        color: #6c757d;
    }
    .card-title {
        font-weight: 500;
        font-size: 0.9rem;
        line-height: 1.2;
        max-height: 2.4em; /* line-height * 2 */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-all;
    }
</style>

<div class="container mt-4">
    <div class="p-3 mb-4 bg-light border rounded-3">
        <div class="d-flex align-items-center">
            <a href="/" class="btn btn-outline-secondary me-3" title="Atrás">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0">Escuela {{ $escuela->numero_escuela }}</h1>
                <p class="text-muted mb-0">CTT: {{ $escuela->ctt }}</p>
            </div>
            <!-- boton para crear archivos -->
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('archivos.crearArch', $escuela->id) }}" class="btn btn-filter" type="button" style="border-radius: 15px;" title="Agregar nuevo archivo">
                <span id="aggregate-btn-text"></span>
                <i class="bi bi-file-earmark-plus fs-3 text-primary"></i>
            </a>
        </div>
        <!--boton crear carpeta-->
        <div class="d-flex align-items-center gap-0">
            <a href="{{ route('archivos.creaRR', $escuela->id) }}" class="btn btn-filter" type="button" style="border-radius: 15px;" title="Agregar nueva carpeta">
                <span id="aggregate-btn-text"></span>
                <i class="bi bi-folder-plus fs-3 text-primary"></i>
            </a>
        </div>
        </div>
    </div>

    @if(empty($contenido))
        <div class="text-center py-5 my-5">
            <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
            <h3 class="mt-3 text-muted">Esta carpeta está vacía</h3>
            <p class="text-muted">Aún no se han agregado archivos o carpetas.</p>
        </div>
    @else
        @php
            $contenido = collect($contenido)->sort(function ($a, $b) {
                if ($a['tipo'] === 'dir' && $b['tipo'] !== 'dir') {
                    return -1; // Directorio A antes que archivo B
                }
                if ($a['tipo'] !== 'dir' && $b['tipo'] === 'dir') {
                    return 1; // Archivo A después de directorio B
                }
                return strcasecmp($a['nombre'], $b['nombre']); // Orden alfabético para nombres
            });
        @endphp
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            @foreach($contenido as $index => $item)
                <div class="col">
                    <div class="card h-100 text-center @if($item['tipo'] == 'dir') folder-card @else file-card @endif" style="animation-delay: {{ $index * 50 }}ms;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            @if($item['tipo'] == 'dir')
                                <a href="{{ route('archivos.carpeta', ['escuela' => $escuela->id, 'carpeta' => $item['nombre']]) }}"
                                   class="text-decoration-none text-reset d-flex flex-column align-items-center">
                                    <i class="bi bi-folder-fill file-icon folder-icon mb-2"></i>
                                    <div class="card-title mt-2" title="{{ $item['nombre'] }}">{{ Str::limit($item['nombre'], 20) }}</div>
                                    <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill fw-normal" style="font-size: 0.7rem;">Carpeta</span>
                                </a>
                            @else
                                @php
                                    $extension = strtolower(pathinfo($item['nombre'], PATHINFO_EXTENSION));
                                    $iconClass = 'bi-file-earmark-text';
                                    $iconColor = 'file-generic-icon';
                                    switch ($extension) {
                                        case 'pdf':
                                            $iconClass = 'bi-file-earmark-pdf-fill';
                                            $iconColor = 'text-danger';
                                            break;
                                        case 'doc':
                                        case 'docx':
                                            $iconClass = 'bi-file-earmark-word-fill';
                                            $iconColor = 'text-primary';
                                            break;
                                        case 'xls':
                                        case 'xlsx':
                                            $iconClass = 'bi-file-earmark-excel-fill';
                                            $iconColor = 'text-success';
                                            break;
                                        case 'txt':
                                        case 'pub':
                                            $iconClass = 'bi-filetype-txt';
                                            $iconColor = 'text-secondary';
                                            break;
                                        case 'pptx':
                                        case 'ppt':
                                            $iconClass = 'bi-filetype-pptx';
                                            $iconColor = 'text-warning';
                                            break;
                                        case 'zip':
                                        case 'rar':
                                            $iconClass = 'bi-file-earmark-zip-fill';
                                            $iconColor = 'text-muted';
                                            break;
                                        case 'lnk':
                                            $iconClass = 'bi-file-earmark-minus-fill';
                                            $iconColor = 'text-info';
                                            break;
                                        case 'bmp':
                                        case 'gif':
                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':
                                            $iconClass = 'bi-file-earmark-image-fill';
                                            $iconColor = 'text-info';
                                            break;
                                        case 'accdb':
                                            $iconClass = 'bi-database-fill';
                                            $iconColor = 'text-primary';
                                            break;
                                        case 'exe':
                                            $iconClass = 'bi-filetype-exe';
                                            $iconColor = 'text-danger';
                                            break;
                                        case 'mp3':
                                        case 'wav':
                                            $iconClass = 'bi-file-earmark-music-fill';
                                            $iconColor = 'text-primary';
                                            break;
                                        case 'mp4':
                                        case 'avi':
                                        case 'mkv':
                                            $iconClass = 'bi-file-earmark-play-fill';
                                            $iconColor = 'text-success';
                                            break;
                                    }
                                @endphp
                                <i class="bi {{ $iconClass }} file-icon {{ $iconColor }} mb-2"></i>
                                <div class="card-title mt-2" title="{{ $item['nombre'] }}">{{ Str::limit($item['nombre'], 20) }}</div>
                                <div class="mt-auto pt-2 d-flex gap-2 w-100 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#previewModal" data-src="{{ asset($item['ruta']) }}" data-title="{{ $item['nombre'] }}">
                                        <i class="bi bi-eye"></i> Ver
                                    </button>
                                    <a href="{{ asset($item['ruta']) }}" download="{{ $item['nombre'] }}" class="btn btn-sm btn-secondary" title="Descargar archivo">
                                        <i class="bi bi-download"></i> Descargar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal de Vista Previa -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Vista Previa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!--vista previa del navegador para img, doc, pdf-->
                <iframe id="previewFrame" src="" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const previewModal = document.getElementById('previewModal');
    if (previewModal) {
        const iframe = document.getElementById('previewFrame');
        const modalTitle = document.getElementById('previewModalLabel');

        previewModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const src = button.getAttribute('data-src');
            const title = button.getAttribute('data-title');
            
            let finalSrc = src;
            const extension = title ? title.split('.').pop().toLowerCase() : '';
            
            if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(extension)) {
                finalSrc = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(src)}`;
            }
            modalTitle.textContent = 'Vista Previa: ' + title;
            iframe.setAttribute('src', finalSrc);
        });

        previewModal.addEventListener('hidden.bs.modal', function () {
            iframe.setAttribute('src', '');
        });
    }
});
</script>
@endpush
@endsection
