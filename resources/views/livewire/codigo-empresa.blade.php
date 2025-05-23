{{-- resources/views/livewire/generador-codigos.blade.php --}}
<div class="container-fluid mt-4"
     x-data="{ accion: '' }"                  {{-- controla qué formulario se muestra --}}
>
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">🎯 Generador de Códigos</h4>
        </div>

        <div class="card-body">

            {{-- COMBO BOX DE ACCIONES --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Seleccione una acción</label>
                <select x-model="accion" class="form-select">
                    <option value="">-- Elegir --</option>
                    <option value="generar">🧾 Generar Códigos</option>
                    <option value="reimprimir">🔁 Reimprimir Códigos</option>
                    <option value="reporte">📊 Generar Reporte PDF</option>
                </select>
            </div>

            {{-- FORMULARIO: GENERAR --}}
            <form wire:submit.prevent="generar"
                  x-show="accion === 'generar'"
                  x-transition
                  class="mb-4 border rounded p-3"
            >
                <h6 class="text-primary fw-bold mb-3">🧾 Generar Códigos</h6>
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
                               min="1" max="10000000000"
                               class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100">
                            🧾 Generar
                        </button>
                    </div>
                </div>
            </form>

            {{-- FORMULARIO: REIMPRIMIR --}}
            <form method="POST" action="{{ route('codigos.reimprimir') }}"
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

            {{-- FORMULARIO: REPORTE --}}
            <form method="POST" action="{{ route('codigos.reporte.generar') }}"
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

            {{-- TABLA DE CÓDIGOS --}}
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
                                <td>{{ $loop->iteration }}</td>
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

                {{ $codigos->links() }}
            </div>
        </div>
    </div>
</div>
