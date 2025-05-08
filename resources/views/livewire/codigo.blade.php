<div>
    <form wire:submit.prevent="generar" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Cantidad de Códigos a Generar</label>
                <input type="number" wire:model="cantidad" class="form-control" min="1">
            </div>
            <div class="col-md-4">
                <label>Seleccionar Sufijo</label>
                <select wire:model="sufijo" class="form-control">
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

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

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
