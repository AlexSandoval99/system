@extends('layouts.AdminLTE.index')
@section('title', 'Recepción de Orden de Compra')
@section('content')
    <div class="row">
            <div class="ibox-tools">
                <a href="{{ url('purchase-movement/create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
            </div>
            <form method="GET">
                <div class="row">
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control" name="s" placeholder="Buscar" value="{{ request()->s }}">
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control" name="purchase_order_number" placeholder="Buscar Nro. OC" value="{{ request()->purchase_order_number }}">
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::select('deposits_id', $deposits, request()->deposits_id, ['placeholder' => 'Seleccione Deposito', 'class' => 'form-control', 'select2']) }}
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary" name="filter" value="1"><i class="fa fa-search"></i></button>
                        @if(request()->filter)
                        <a href="{{ url('purchases-movements') }}" class="btn btn-warning"><i class="fa fa-times"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="ibox-content table-responsive no-padding">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-right">Numero OC</th>
                        <th class="text-center">Factura</th>
                        <th>Deposito</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases_movements as $purchases_movement)
                        <tr>
                            <td>{{ $purchases_movement->id }}</td>
                            <td class="text-center">{{ $purchases_movement->created_at->format('d/m/Y') }}</td>
                            <td class="text-right">{{ $purchases_movement->purchases_movement_details()->first() ? $purchases_movement->purchases_movement_details()->first()->purchases_order_detail->purchases_order->number : ''}}</td>
                            <td class="text-center">{{ $purchases_movement->invoice_number }}</td>
                            <td>{{ $purchases_movement->deposit->name }}</td>
                            <td class="text-right">
                                <a href="{{ url('purchases-movements/' . $purchases_movement->id) }}"><i class="fa fa-info-circle"></i></a>
                                @permission('purchases-movements.delete')
                                        <a href="{{ url('purchases-movements/' . $purchases_movement->id . '/delete') }}"><i class="fa fa-trash"></i></a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $purchases_movements->appends(request()->query())->links() }}
    </div>
@endsection
