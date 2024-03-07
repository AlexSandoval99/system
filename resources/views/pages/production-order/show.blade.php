@extends('layouts.AdminLTE.index')
@section('title', 'Orden de Produccion')
@section('content')
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a href="{{ url('production-order') }}" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> Volver</a>
                    </div>
                </div>
                <br>
                <div class="row">                        
                    <div class="col-md-12">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-3"><b>Nro° Orden:</b></div>
                                <div class="col-md-9">{{ $production_order->id }}</div>
                            </div>  
                            <div class="row">
                                <div class="col-md-3"><b>Solicitado por:</b></div>
                                <div class="col-md-9">{{ $production_order->user->name }}</div>
                            </div> 
                            <div class="row">
                                <div class="col-md-3"><b>Cliente:</b></div>
                                <div class="col-md-9">{{ $wish_production->client->fullname}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Observación:</b></div>
                                <div class="col-md-9">{{ $production_order->observation ?? '' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Fecha Creación:</b></div>
                                <div class="col-md-9">{{ $production_order->created_at->format('d/m/Y H:m:s') }}</div>
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
                            <th>Articulo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($production_order->production_order_details as $detail)
                            <tr>
                               <td>{{ $detail->articulo->name }}</td>
                               <td>{{ $detail->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>        
</div>
@endsection
