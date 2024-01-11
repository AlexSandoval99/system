@extends('layouts.AdminLTE.index')
@section('title', 'Pedido Compras')
@section('content')
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a href="{{ url('wish-purchase') }}" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> Volver</a>
                    </div>
                </div>
                <br>
                <div class="row">                        
                    <div class="col-md-12">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-3"><b>Nro° Pedido:</b></div>
                                <div class="col-md-9">{{ $wish_purchase->number }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Sucursal:</b></div>
                                <div class="col-md-9">{{ $wish_purchase->branch->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Solicitado por:</b></div>
                                <div class="col-md-9">{{ $wish_purchase->user->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Fecha:</b></div>
                                <div class="col-md-9">{{ $wish_purchase->date}}</div>
                            </div>                                
                            <div class="row">
                                <div class="col-md-3"><b>Observación:</b></div>
                                <div class="col-md-9">{{ $wish_purchase->observation ?? '' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Estado:</b></div>
                                <div class="col-md-9"><span class="label label-{{ config('constants.purchases-order-status-label.' .$wish_purchase->status) }}">{{ config('constants.purchases-order-status.' .$wish_purchase->status) }}</span></div>
                            </div>
                            @if ($wish_purchase->status ==2)
                                <div class="row">
                                    <div class="col-md-3"><b>Motivo:</b></div>
                                    <div class="col-md-9">{{ $wish_purchase->reason_deleted}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><b>Fecha Eliminacion:</b></div>
                                    <div class="col-md-9">{{ $wish_purchase->date_deleted ? $wish_purchase->date_deleted : '-' }}</div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-3"><b>Fecha Creación:</b></div>
                                <div class="col-md-9">{{ $wish_purchase->created_at->format('d/m/Y H:m:s') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">                
        <div class="col-md-12">
            <div class="ibox-content table-responsive no-padding">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Cód</th>
                            <th>Producto</th>
                            <th>Presentación</th>
                            <th>Descripción</th>
                            <th class="text-right">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wish_purchase->purchases_order_details as $details)
                            <tr>
                                <td>{{ $details->material_id }}</td>
                                <td>
                                    {{ $details->material->description }}
                                </td>
                                <td>{{  config('constants.presentation.'. $details->presentation) }}</td>
                                <td>{{ $details->description ?? '' }}</td>
                                <td class="text-right">{{ number_format($details->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>        
</div>
@endsection
