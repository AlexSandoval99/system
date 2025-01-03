@extends('layouts.AdminLTE.index')
@section('title', 'Control de Calidad')
@section('content')
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a href="{{ url('production-control-quality') }}" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> Volver</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-3"><b>Nro° Pedido:</b></div>
                                <div class="col-md-9">{{ $control->id }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Cilente:</b></div>
                                <div class="col-md-9">{{ $control->client->fullname }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Sucursal:</b></div>
                                <div class="col-md-9">{{ $control->branch->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Solicitado por:</b></div>
                                <div class="col-md-9">{{ $control->user->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Fecha:</b></div>
                                <div class="col-md-9">{{ $control->date}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><b>Fecha Creación:</b></div>
                                <div class="col-md-9">{{ $control->created_at->format('d/m/Y H:m:s') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox float-e-margins" id="div_details">
        <div class="ibox-title">
            <h3>Items</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                    </div>
                    <div class="ibox-content table-responsive no-padding">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Parametro de Calidad</th>
                                    <th>Articulo</th>
                                    <th>Cantidad Total</th>
                                    <th>Cantidad Aprovada</th>
                                    <th>Observacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($control->production_quality_control_details as $res)
                                    <tr>
                                        <td>{{ $res->production_qualitie->name }}</td>
                                        <td>{{ $res->articulo->name }}</td>
                                        <td>{{ $res->quantity }}</td>
                                        <td>{{ $res->residue }}</td>
                                        <td>{{ $res->observation }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
