@extends('layouts.AdminLTE.index')
@section('title', 'Proveedor')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Pedido de Compras</h5>
                <div class="btn-group pull-right">
                    <a href="{{ url('wish-purchase/create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <div class="ibox-content pb-0">
                <div class="row">
                    <form method="GET">
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control" name="s" placeholder="Buscar" value="{{ request()->s }}">
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::select('purchases_provider_id', $purchases_providers, request()->purchases_provider_id, ['placeholder' => 'Seleccione Proveedor', 'class' => 'form-control', 'select2']) }}
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary" name="filter" value="1"><i class="fa fa-search"></i></button>
                            @if(request()->filter)
                                <a href="{{ url('purchases') }}" class="btn btn-warning"><i class="fa fa-times"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="ibox-content table-responsive no-padding">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>SU</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->branch->name }}</td>
                                <td>{{ $purchase->date }}</td>
                                <td>{{ $purchase->provider->name }}</td>
                                <td>
                                    <span class="label label-{{ config('constants.purchase-status-label.' . $purchase->status) }}">{{ config('constants.purchase-status.'. $purchase->status) }}</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ url('wish-purchase/' . $purchase->id . '/pdf') }}" target="_blank"><i class="fa fa-file-pdf"></i></a>
                                    <a href="{{ url('wish-purchase/' . $purchase->id) }}"><i class="fa fa-info-circle"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $purchases->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
