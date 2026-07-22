document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const filterDropdownBtn = document.getElementById('filter-dropdown-btn');
    const filterBtnText = document.getElementById('filter-btn-text');
    const schoolsContainer = document.getElementById('schools-container');

    const filterCttItem = document.getElementById('filter-ctt-item');
    const filterNumeroItem = document.getElementById('filter-numero-item');
    const filterClearItem = document.getElementById('filter-clear-item');

    let activeFilter = 'all';
    let searchQuery = '';
    let debounceTimeout = null;

    function setFilter(filterType) {
        activeFilter = filterType;

        filterCttItem.classList.remove('active');
        filterNumeroItem.classList.remove('active');

        if (filterType === 'ctt') {
            filterCttItem.classList.add('active');
            filterBtnText.textContent = 'Filtro: CTT';
            filterDropdownBtn.classList.add('btn-filter-active');
            searchInput.placeholder = 'Buscar por CTT (ej. Ct2514c51)...';
        } else if (filterType === 'numero_escuela') {
            filterNumeroItem.classList.add('active');
            filterBtnText.textContent = 'Filtro: N° Escuela';
            filterDropdownBtn.classList.add('btn-filter-active');
            searchInput.placeholder = 'Buscar por Número de Escuela (ej. 14984984)...';
        } else {
            filterBtnText.textContent = 'Filtrar';
            filterDropdownBtn.classList.remove('btn-filter-active');
            searchInput.placeholder = 'Buscar escuela...';
        }

        performSearch();
    }

    filterCttItem.addEventListener('click', function(e) {
        e.preventDefault();
        setFilter('ctt');
    });

    filterNumeroItem.addEventListener('click', function(e) {
        e.preventDefault();
        setFilter('numero_escuela');
    });

    filterClearItem.addEventListener('click', function(e) {
        e.preventDefault();
        setFilter('all');
    });

    searchInput.addEventListener('input', function() {
        searchQuery = this.value;

        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            performSearch();
        }, 300);
    });

    function showSkeletons() {
        let skeletonsHtml = '';
        for (let i = 0; i < 5; i++) {
            skeletonsHtml += `
                <div class="col skeleton-card">
                    <div class="card folder-card h-100 border-0 text-center bg-white shadow-sm">
                        <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                            <div class="placeholder-glow w-100 text-center">
                                <div class="skeleton-icon mb-2 placeholder"></div>
                                <div class="placeholder col-8 bg-secondary mt-2" style="height: 14px; opacity: 0.15;"></div>
                                <div class="placeholder col-5 bg-secondary mt-1" style="height: 10px; opacity: 0.15;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        schoolsContainer.innerHTML = skeletonsHtml;
    }

    function performSearch() {
        showSkeletons();

        const params = new URLSearchParams({
            query: searchQuery,
            filter: activeFilter
        });

        fetch(`/escuelas/search?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                schoolsContainer.innerHTML = '';

                if (data.length === 0) {
                    schoolsContainer.innerHTML = `
                    <div class="col-12 text-center py-5 w-100" id="no-results-box">
                        <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3 fw-medium">No se encontraron escuelas con la información proporcionada.</p>
                    </div>
                `;
                    return;
                }

                data.forEach((escuela, index) => {
                    const cardHtml = `
                    <div class="col school-card-wrapper" style="animation-delay: ${index * 0.05}s">
                        <div class="card folder-card h-100 position-relative shadow-sm border-0 text-center bg-white">
                            <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="position-relative d-inline-block">
                                    <i class="bi bi-folder-fill" style="font-size: 4.5rem; color: rgba(7, 0, 147, 0.59);"></i>
                                </div>
                                <span class="text-muted fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">ESCUELA
                                    <strong>${escuela.numero_escuela}</strong></span>
                                <span class="text-muted mt-1 d-block" style="font-size: 0.7rem; opacity: 0.85;">CTT: ${escuela.ctt}</span>
                            </div>
                        </div>
                    </div>
                `;
                    schoolsContainer.insertAdjacentHTML('beforeend', cardHtml);
                });
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                schoolsContainer.innerHTML = `
                <div class="col-12 text-center py-5 w-100">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                    <p class="text-danger mt-3 fw-medium">Ocurrió un error al buscar las escuelas. Por favor, intente de nuevo.</p>
                </div>
            `;
            });
    }
});