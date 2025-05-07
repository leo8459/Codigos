<div>
    <form wire:submit.prevent="generar">
        <div class="form-group">
            <label>Cantidad de Códigos a Generar</label>
            <input type="number" wire:model="cantidad" class="form-control" min="1">
        </div>

        <button type="submit" class="btn btn-primary mt-2">Generar</button>
    </form>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">
            {{ session('message') }}
        </div>
    @endif
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Código de Barras</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\App\Models\Code::latest()->take(20)->get() as $codigo)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $codigo->codigo }}</td>
                <td><img src="{{ asset('storage/' . $codigo->barcode) }}" height="50"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
</div>

