<div class="container mt-4">
    <h3>Crear Nueva Empresa</h3>

    @if (session()->has('message'))
        <div class="alert alert-success mt-2">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="guardar">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model="nombre" class="form-control">
        </div>
        <div class="form-group">
            <label>Sigla</label>
            <input type="text" wire:model="sigla" class="form-control">
        </div>
        <div class="form-group">
            <label>Código Cliente</label>
            <input type="text" wire:model="codigo_cliente" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Empresa</button>
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">Volver al Listado</a>
    </form>
</div>
