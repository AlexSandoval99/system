@extends('layouts.AdminLTE.index')
@section('title', 'Control de Produccion')
@section('content')
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a href="{{ url('production-control') }}" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> Volver</a>
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
                                    <th>Etapa</th>
                                    <th>Articulo</th>
                                    <th>Cantidad Total</th>
                                    <th>Cantidad Aprobada</th>
                                    <th>Observacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($control->production_control_details as $res)
                                    <tr>
                                        <td>{{ $res->production_stage->name }}</td>
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
                {{-- <div class="tabs-container">
                    <ul class="nav nav-tabs fs-3">
                        <li class="active"><a data-toggle="tab" href="#seccion1" onclick="ChangeTab1();"><h5>Primera Etapa </h5></a></li>
                        <li class=""><a data-toggle="tab" href="#seccion2" onclick="ChangeTab2();"><h5>Segunda Etapa </h5></a></li>
                        <li class=""><a data-toggle="tab" href="#seccion3" onclick="ChangeTab3();"><h5>Tercera Etapa </h5></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="seccion1">
                            <div class="panel-body table-responsive" id="div_sec3">
                                <table class="table table-stripped" data-limit-navigation="8" data-sort="true" data-paging="true" data-filter=#filter1>
                                    <thead>
                                        <tr>
                                            <th>Articulos</th>
                                            <th>Cantidad</th>
                                            <th>Etapa</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
