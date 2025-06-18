<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Historial de Eventos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Eventos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <input type="text" wire:model.debounce.500ms="searchTerm" class="form-control mr-2" placeholder="Buscar código...">

                        <select wire:model="searchUserId" class="form-control" style="max-width: 250px;">
                            <option value="">Todos los usuarios</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Acción</th>
                                    <th>Descripción</th>
                                    <th>Código</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($admisiones as $evento)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $evento->accion }}</td>
                                        <td>{{ $evento->descripcion }}</td>
                                        <td>{{ $evento->codigo }}</td>
                                        <td>{{ $evento->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $evento->user->name ?? 'No asignado' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No se encontraron eventos.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {{ $admisiones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
