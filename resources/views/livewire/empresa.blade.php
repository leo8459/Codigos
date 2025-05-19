{{-- ------------- RAÍZ ÚNICA DEL COMPONENTE ------------- --}}
<div>

    {{-- Tarjeta con la tabla --------------------------------------------------- --}}
    <div class="container-fluid mt-3">
        <div class="card shadow-sm rounded">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0">Listado de {{ $titulo ?? 'Registros' }}</h5>

                @isset($rutaCrear)
                    <a href="{{ route($rutaCrear) }}" class="btn btn-success btn-sm">Nueva Empresa</a>
                @endisset
            </div>

            <div class="card-body p-3">
                {{-- Alertas --}}
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Tabla --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                @foreach ($columnas as $columna)
                                    <th>{{ $columna }}</th>
                                @endforeach
                                {{-- <th class="text-center">Acciones</th> --}}
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($registros as $registro)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    @foreach ($campos as $campo)
                                        <td>{{ data_get($registro, $campo) }}</td>
                                    @endforeach

                                    {{-- <td class="text-center">
                                        <button class="btn btn-warning btn-sm"
                                                wire:click="editar({{ $registro->id }})">
                                            Editar
                                        </button>

                                        @if ($eliminar)
                                            <button class="btn btn-danger btn-sm"
                                                    wire:click="eliminar({{ $registro->id }})"
                                                    onclick="return confirm('¿Eliminar este registro?')">
                                                Eliminar
                                            </button>
                                        @endif
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($columnas) + 2 }}" class="text-center">
                                        Sin registros disponibles
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if (method_exists($registros, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $registros->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal de edición ------------------------------------------------------ --}}
    <div class="modal fade" wire:ignore.self id="modalEditarEmpresa" tabindex="-1"
         aria-labelledby="modalEditarEmpresaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form wire:submit.prevent="actualizar" class="modal-content">
                {{-- Mantiene el id en el DOM --}}
                <input type="hidden" wire:model="empresa_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarEmpresaLabel">Editar Empresa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Nombre --}}
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" wire:model.defer="nombre">
                        @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Sigla --}}
                    <div class="form-group">
                        <label>Sigla</label>
                        <input type="text" class="form-control" wire:model.defer="sigla">
                        @error('sigla') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Código Cliente --}}
                    <div class="form-group">
                        <label>Código Cliente</label>
                        <input type="text" class="form-control" wire:model.defer="codigo_cliente">
                        @error('codigo_cliente') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</div> {{-- -------- FIN RAÍZ ÚNICA -------- --}}

{{-- Scripts --------------------------------------------------------------- --}}
@push('scripts')
    {{-- Si tu layout ya incluye jQuery/Bootstrap 4.6 quita estas dos líneas --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Escucha los browser-events enviados desde Livewire
        window.addEventListener('abrir-modal-edicion', () => {
            $('#modalEditarEmpresa').modal('show');
        });

        window.addEventListener('cerrar-modal-edicion', () => {
            $('#modalEditarEmpresa').modal('hide');
        });
    </script>
@endpush
