{{-- resources/views/livewire/codigo-empresa.blade.php --}}
{{-- Cambia el nombre del archivo si tu componente render() llama a otro. --}}

<div class="container-fluid mt-4"
     x-data="{ accion: '' }"     {{-- Alpine.js para alternar formularios --}}
>
    {{-- =================== CARD PRINCIPAL =================== --}}
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class="mb-0">🎯 Generador de Códigos</h4>
        </div>

        <div class="card-body">
            {{-- ========== COMBO DE ACCIONES ========== --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Seleccione una acción</label>
                <select x-model="accion" class="form-select">
                    <option value="">-- Elegir --</option>
                    <option value="generar">🧾 Generar Códigos</option>
                    <option value="reimprimir">🔁 Reimprimir Códigos</option>
                    <option value="reporte">📊 Generar Reporte PDF</option>
                </select>
            </div>

            {{-- ===============================================================
                 FORMULARIO 1: GENERAR CÓDIGOS
                 ===========================================================--}}
            <form wire:submit.prevent="generar"
                  x-show="accion === 'generar'"
                  x-transition
                  class="mb-4 border rounded p-3"
            >
                <h6 class="text-primary fw-bold mb-3">🧾 Generar Códigos</h6>

                {{-- Fila 1: Empresa + Cantidad --}}
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Empresa</label>
                        <select wire:model="empresa_id" class="form-select" required>
                            <option value="">-- Seleccione una empresa --</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">
                                    {{ $empresa->sigla }} ({{ $empresa->codigo_cliente }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" wire:model="cantidad"
                               min="1" max="100000"
                               class="form-control" required>
                    </div>
                    <div class="col-md-3 d-md-none d-block">
                        {{-- Espaciador para responsivo --}}
                    </div>
                </div>

                {{-- Fila 2: Iniciar desde + Reset + Botón --}}
                <div class="row g-3 mt-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Iniciar desde (opcional)</label>
                        <input type="number" wire:model.defer="inicio"
                               min="1" max="99999"
                               class="form-control"
                               placeholder="Ej: 1, 150, 5000">
                    </div>

                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" wire:model.defer="reset"
                                   id="reset" class="form-check-input">
                            <label class="form-check-label" for="reset">
                                Resetear contador
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            🧾 Generar
                        </button>
                    </div>
                </div>
            </form>

            {{-- ===============================================================
                 FORMULARIO 2: REIMPRIMIR CÓDIGOS
                 ===========================================================--}}
            <form method="POST"
                  action="{{ route('codigos.reimprimir') }}"
                  x-show="accion === 'reimprimir'"
                  x-transition
                  class="mb-4 border border-warning rounded p-3"
            >
                @csrf
                <h6 class="text-warning fw-bold mb-3">🔁 Reimprimir Códigos</h6>

                <div class="mb-3">
                    <label class="form-label">Códigos separados por coma</label>
                    <textarea name="codigos" class="form-control" rows="3" required
                              placeholder="Ej: C0003A01550BO, C0003A01551BO"></textarea>
                </div>

                <button type="submit" class="btn btn-outline-warning">
                    📄 Reimprimir PDF
                </button>
            </form>

            {{-- ===============================================================
                 FORMULARIO 3: REPORTE PDF
                 ===========================================================--}}
            <form method="POST"
                  action="{{ route('codigos.reporte.generar') }}"
                  x-show="accion === 'reporte'"
                  x-transition
                  class="mb-4 border border-info rounded p-3"
            >
                @csrf
                <h6 class="text-info fw-bold mb-3">📊 Generar Reporte PDF</h6>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Fecha desde</label>
                        <input type="date" name="desde" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha hasta</label>
                        <input type="date" name="hasta" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Empresa (opcional)</label>
                        <select name="empresa_id" class="form-select">
                            <option value="">-- Todas --</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">
                                    {{ $empresa->sigla }} ({{ $empresa->codigo_cliente }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-info w-100 mt-3">
                    📥 Generar Reporte
                </button>
            </form>

            {{-- ===============================================================
                 TABLA DE RESULTADOS
                 ===========================================================--}}
            <hr>
            <h5 class="mt-4">📌 Listado de Códigos Generados</h5>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Imagen Código de Barras</th>
                            <th>Empresa</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($codigos as $codigo)
                            <tr>
                                <td>{{ $loop->iteration + ($codigos->perPage() * ($codigos->currentPage() - 1)) }}</td>
                                <td>{{ $codigo->codigo }}</td>
                                <td>{!! DNS1D::getBarcodeHTML($codigo->codigo, 'C128') !!}</td>
                                <td>{{ $codigo->empresa->sigla ?? 'Sin Empresa' }}</td>
                                <td>{{ $codigo->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No hay códigos generados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Paginación Bootstrap --}}
                <div class="d-flex justify-content-center">
                    {{ $codigos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
