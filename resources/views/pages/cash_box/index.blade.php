@extends('layouts.AdminLTE.index')

@section('title', 'Cajas')

@section('content')
<div class="card">
    <div class="btn-group pull-right">
        <a href="{{ route('cash_boxes.create') }}" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Agregar
        </a>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('cash_boxes.index') }}" class="form-inline mb-3">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            </div>
            <div class="form-group mr-2">
                {{-- <select name="branch_id" class="form-control">
                    <option value="">Seleccione Punto Expedición</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select> --}}
            </div>
            <button type="submit" class="btn btn-teal">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Punto Expedición</th>
                    <th>Sucursal</th>
                    <th>Observación</th>
                    <th>Estado</th>
                    <th style="width: 80px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cash_boxes as $box)
                    <tr>
                        <td>{{ $box->name }}</td>
                        <td>{{ $box->voucher_box->name ?? '-' }}</td>
                        <td>{{ $box->voucher_box->branch->name ?? '-' }}</td>
                        <td>{{ $box->observation ?? '-' }}</td>
                        <td>
                            @if ($box->status == 1)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('cash_boxes.show', $box->id) }}" class="text-info" title="Ver">
                                <i class="fa fa-info-circle"></i>
                            </a>
                            <a href="{{ route('cash_boxes.edit', $box->id) }}" class="text-primary ml-2" title="Editar">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $cash_boxes->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
