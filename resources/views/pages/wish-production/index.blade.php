@extends('layouts.AdminLTE.index')
@section('title', 'Pedido Ventas')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="btn-group pull-right">
                    <a href="{{ url('wish-production/create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <div class="ibox-content pb-0">
                <div class="row">
                    <form method="GET">
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control" name="p" placeholder="Buscar" value="{{ request()->p }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="ibox-content table-responsive no-padding">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Nro°</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productions as $production)
                            <tr>
                                <td>{{ $production->id }}</td>
                                <td>{{ $production->date }}</td>
                                <td>{{ $production->client->razon_social }}</td>
                                <td>
                                    <span class="label label-{{ config('constants.purchase-status-label.' . $production->status) }}">{{ config('constants.purchase-status.'. $production->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('wish-production/' . $production->id) }}"><i class="fa fa-info-circle"></i></a>
                                    <a href="{{ url('wish-production/' . $production->id . '/edit') }}"target="_blank" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $productions->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
