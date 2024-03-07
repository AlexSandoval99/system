@extends('layouts.AdminLTE.index')
@section('title', 'Pedido Compras')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="btn-group pull-right">
                    <a href="{{ url('wish-purchase/create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
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
                            <th>SU</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->number }}</td>
                                <td>{{ $purchase->branch->name }}</td>
                                <td>{{ $purchase->date }}</td>
                                <td>
                                    <span class="label label-{{ config('constants.purchase-status-label.' . $purchase->status) }}">{{ config('constants.purchase-status.'. $purchase->status) }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown" style="display: inline-block;">
                                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            Acción
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right">
                                            <li>
                                                    <a href="{{ url('wish-purchase-budgets/' . $purchase->id . '/wish-purchase-budgets-approved') }}"><i class="fa fa-user"></i> Ver Presupuestos Aprobados</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('wish-purchase/' . $purchase->id) }}"><i class="fa fa-info-circle"></i>Ver</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('wish-purchase/' . $purchase->id . '/charge-purchase-budgets') }}"><i title="Anclar Presupuestos" class="fa fa-upload"></i>Anclar Presupuestos</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('wish-purchase/' . $purchase->id . '/confirm-purchase-budgets') }}"><i class="fa fa-user"></i> Ir a Confirmar Presupuestos</a>                                            
                                            </li>
                                            <li>
                                                <a href="{{ url('wish-purchase/' . $purchase->id . '/pdf') }}" target="_blank"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                            </li>
                                        </ul>
                                    </div>
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
