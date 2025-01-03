@extends('layouts.AdminLTE.index')
@section('title', 'Vista de Presupuestos Cargados')
@section('layout_css')
    <style>
        .button-container {
            text-align: center;
            border: 2px solid #126f16;
        }
        .button-container a {
            border: 1px solid #126f16;
        }
        .image-box {
            margin-bottom: 30px;
            border: 3px solid #126f16;
            display: inline-block;
        }
        .image-box img {
            width: 400px;
            height: 500px;
        }
        p {
            font-size: 14px;
        }
    </style>
@endsection
@section('content')
        <div class="ibox-content">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Detalles de Presupuesto N# {{$purchase_budget->id}}</h3>
                        <div class="row">
                            <div class="col-md-2">
                                <p>Fecha:</p>
                                <p>Empresa: </p>
                                <p>RUC: </p>
                            </div>
                            <div class="col-md-10">
                                <p>{{$purchase_budget->date}}</p>
                                <p> {{$purchase_budget->name}}</p>
                                <p> {{$purchase_budget->ruc}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h3>Productos Solicitados Según Solicitud Nro #{{$purchase_budget->wish_purchase->number}}</h3>
                        <div class="table">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th width="80%">Producto</th>
                                        <th width="10%">Cantidad</th>
                                        <th width="10%">Precio</th>
                                        <th width="10%">Total Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchase_budget->budget_purchase_details as $purchase_budget_detail)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$purchase_budget_detail->material->description}}</td>
                                            <td>{{$purchase_budget_detail->quantity}}</td>
                                            <td>{{ number_format($purchase_budget_detail->price,0,',','.')}}</td>
                                            <td>{{number_format($purchase_budget_detail->total_price,0,',','.')}}</td>
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
@section('layout_js')
    <script>

    </script>
@endsection
