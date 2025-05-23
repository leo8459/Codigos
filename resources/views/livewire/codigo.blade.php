{{-- resources/views/livewire/codigos.blade.php --}}
<div
    x-data="{ accion: '' }"       {{-- estado Alpine para mostrar el formulario correcto --}}
    x-cloak                     {{-- oculta parpadeo hasta que cargue Alpine --}}
>
    {{-- SELECTOR DE ACCIONES --------------------------------------------------- --}}
    <div class="mb-4">
        <label class="form-label fw-bold">Seleccione una acción</label>
        <select x-model="accion" class="form-select">
            <option value="">-- Elegir --</option>
            <option value="generar">🧾 Generar Códigos</option>
            <option value="reimprimir">🔁 Reimprimir Códigos</option>
            <option value="reporte">📊 Exportar Resumen PDF</option>
        </select>
    </div>

    {{-- FORMULARIO GENERAR ------------------------------------------------------ --}}
    <form wire:submit.prevent="generar"
          x-show="accion === 'generar'"
          x-transition
          class="mb-4 border rounded p-3"
    >
        <h6 class="text-primary fw-bold mb-3">🧾 Generar Códigos</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Cantidad de Códigos</label>
                <input type="number" wire:model="cantidad" class="form-control" min="1">
            </div>
            <div class="col-md-4">
                <label class="form-label">Seleccionar Sufijo</label>
                <select wire:model="sufijo" class="form-select">
                    <option value="SRZ">SRZ</option>
                    <option value="CIJ">CIJ</option>
                    <option value="TDD">TDD</option>
                    <option value="TJA">TJA</option>
                    <option value="LPB">LPB</option>
                    <option value="SRE">SRE</option>
                    <option value="ORU">ORU</option>
                    <option value="CBB">CBB</option>
                    <option value="POI">POI</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Generar</button>
            </div>
        </div>
    </form>

    {{-- FORMULARIO REIMPRIMIR --------------------------------------------------- --}}
    <form wire:submit.prevent="reimprimirPDF"
          x-show="accion === 'reimprimir'"
          x-transition
          class="mb-4 border border-warning rounded p-3"
    >
        <h6 class="text-warning fw-bold mb-3">🔁 Reimprimir Códigos</h6>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Códigos separados por coma</label>
                <input type="text" wire:model="codigosReimprimir"
                       class="form-control"
                       placeholder="Ej: EN000123LPB, EN000124LPB">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-warning w-100">
                    <i class="fas fa-print"></i> Reimprimir Seleccionados
                </button>
            </div>
        </div>
    </form>

    {{-- FORMULARIO REPORTE ------------------------------------------------------ --}}
    <form x-show="accion === 'reporte'"
          x-transition
          class="mb-4 border border-info rounded p-3"
    >
        <h6 class="text-info fw-bold mb-3">📊 Exportar Resumen PDF</h6>
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" wire:model="fechaInicio" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" wire:model="fechaFin" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Filtrar por IATA</label>
                <select wire:model="filtroSufijo" class="form-select">
                    <option value="">-- Todos --</option>
                    <option value="SRZ">SRZ</option>
                    <option value="CIJ">CIJ</option>
                    <option value="TDD">TDD</option>
                    <option value="TJA">TJA</option>
                    <option value="LPB">LPB</option>
                    <option value="SRE">SRE</option>
                    <option value="ORU">ORU</option>
                    <option value="CBB">CBB</option>
                    <option value="POI">POI</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                {{-- Botón independiente: llama al método exportarPDF --}}
                <button wire:click.prevent="exportarPDF" class="btn btn-danger w-100">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
            </div>
        </div>
    </form>

    {{-- MENSAJES ---------------------------------------------------------------- --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    {{-- TABLA DE RESULTADOS ----------------------------------------------------- --}}
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            Últimos Códigos Generados
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($codigos as $codigo)
                        <tr>
                            <td>{{ $loop->iteration + ($codigos->currentPage() - 1) * $codigos->perPage() }}</td>
                            <td>{{ $codigo->codigo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $codigos->links() }}
        </div>
    </div>
</div>
