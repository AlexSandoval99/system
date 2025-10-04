@extends('layouts.AdminLTE.index')
@section('title', 'Ventas')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="card border-primary">
                <div class="card-header d-flex justify-content-between">
                    <h5>Ventas</h5>
                    <div class="ibox-content pull-right">
                        <a href="{{ url('voucher/create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Sucursal</th>
                                <th>Fecha</th>
                                <th>Condición</th>
                                <th>RUC</th>
                                <th>Razón Social</th>
                                <th>Estado</th>
                                <th>Monto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                            <tr>
                                <td>{{$voucher->id}}</td>
                                <td><span class="label label-{{ config('constants.type_purchases_label.' . $voucher->voucher_type) }}">{{ config('constants.type_purchases.'. $voucher->voucher_type) }}</span></td>
                                <td>{{$voucher->branch_id ? $voucher->branch->name : ''}}</td>
                                <td>{{$voucher->date->format('d/m/Y')}}</td>
                                <td>{{ config('constants.invoice_condition.'. $voucher->voucher_condition) }}</td>
                                <td>{{$voucher->ruc}}</td>
                                <td>{{$voucher->razon_social}}</td>
                                <td><span class="label label-{{ config('constants.invoice_status_label.' . $voucher->status) }}">{{ config('constants.invoice_status.'. $voucher->status) }}</span></td>
                                <td>{{ number_format($voucher->amount, 0, ',', '.') }}</td>
                                <td>
                                    <a href="#"><i class="fa fa-info-circle"></i></a>
                                    <a href="#"><i class="fa fa-file"></i></a>
                                    <a href="#"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            {{-- <tr>
                                <td colspan="9" class="text-center">No hay ventas registradas</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
