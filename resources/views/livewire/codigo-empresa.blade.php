<div class="container-fluid mt-4">
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Generador de Códigos</h5>
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="generar" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="empresa_id" class="form-label">Empresa</label>
                        <select wire:model="empresa_id" class="form-control" required>
                            <option value="">-- Seleccione una empresa --</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">
                                    {{ $empresa->sigla }} ({{ $empresa->codigo_cliente }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" wire:model="cantidad" min="1" max="10000000000" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Generar Códigos</button>
                    </div>
                </div>
            </form>

            <hr>

            <h5>Listado de Códigos Generados</h5>
            <div class="table-responsive mt-3">
               <table class="table table-bordered table-hover">
    <thead>
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
                <td colspan="5" class="text-center">No hay códigos generados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

            </div>
        </div>
    </div>
</div>
