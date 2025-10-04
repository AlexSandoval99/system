@extends('layouts.AdminLTE.index')
@section('title', 'Cobros')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card border-primary">
                <div class="card-header d-flex justify-content-between">
                    <h5>Listado de Cobros</h5>
                    <a href="payments/create" class="btn btn-success">Agregar</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Sucursal</th>
                                <th>Fecha</th>
                                <th>N° Recibo</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($payments as $payment)
                                <tr>
                                    <td>{{$payment->id}}</td>
                                    <td>{{$payment->branch->name}}</td>
                                    <td>{{$payment->date->format('d/m/Y')}}</td>
                                    <td>{{$payment->voucher_fullnumber}}</td>
                                    <td>{{$payment->amount}}</td>
                                    <td><span class="label label-{{ config('constants.invoice_status_label.' . $voucher->status) }}">{{ config('constants.invoice_status.'. $voucher->status) }}</span></td>
                                    <td>
                                        <a href="#"><i class="fa fa-info-circle"></i></a>
                                        <a href="#"><i class="fa fa-file"></i></a>
                                    </td>
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
