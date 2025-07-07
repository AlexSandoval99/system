@extends('layouts.AdminLTE.index')
@section('title', 'Punto de Expedicion')
@section('content')
<div class="card">
    <div class="btn-group pull-right">
        <a href="{{ route('voucher_boxes.create') }}" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Agregar
        </a>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('voucher_boxes.index') }}" class="form-inline mb-3">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            </div>
            <div class="form-group mr-2">
            </div>
            <button type="submit" class="btn btn-teal">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Punto Expedición</th>
                    <th>#</th>
                    <th>Caja</th>
                    <th>Timbrado</th>
                    <th>Estado</th>
                    <th style="width: 80px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boxes as $box)
                    <tr>
                        <td>001</td>
                        <td>{{ $box->name }}</td>
                        <td>{{ $box->voucher_fullnumber }}</td>
                        <td>{{ $box->name }}</td>
                        <td>{{ $box->stamped->number }}</td>
                        <td>
                            @if (!$box->deleted_at)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('voucher_boxes.show', $box->id) }}" class="text-info" title="Ver">
                                <i class="fa fa-info-circle"></i>
                            </a>
                            <a href="{{ route('voucher_boxes.edit', $box->id) }}" class="text-primary ml-2" title="Editar">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $boxes->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
