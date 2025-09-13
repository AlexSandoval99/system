@extends('layouts.AdminLTE.index')
@section('title', 'Registrar Cobro')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card border-primary">
                <div class="card-header">
                    <h5>Registrar Cobro</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            <label>Sucursal</label>
                            <select class="form-control">
                                <option>Central</option>
                                <option>Sucursal 1</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Punto de Expedición</label>
                            <select class="form-control">
                                <option>001-001</option>
                                <option>001-002</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>N° Recibo</label>
                            <input type="text" class="form-control" placeholder="Ingrese nro recibo">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>Fecha Recibo</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Caja</label>
                            <select class="form-control">
                                <option>Caja Central</option>
                                <option>Caja Secundaria</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Cliente</label>
                            <select class="form-control">
                                <option>Juan Pérez</option>
                                <option>Ana Gómez</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>RUC</label>
                            <input type="text" class="form-control" placeholder="Ingrese RUC">
                        </div>
                        <div class="col-md-8">
                            <label>Razón Social</label>
                            <input type="text" class="form-control" placeholder="Ingrese razón social">
                        </div>
                    </div>

                    <h6 class="mt-3">Detalle de Cobro</h6>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label>Factura a Cobrar</label>
                            <select class="form-control">
                                <option>Factura 001</option>
                                <option>Factura 002</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Saldo Factura</label>
                            <input type="text" class="form-control" value="0">
                        </div>
                        <div class="col-md-3">
                            <label>Monto a Cobrar</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-success w-100"><i class="fa fa-plus"></i> Agregar</button>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Factura</th>
                                <th>Monto</th>
                                <th>Forma de Pago</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">Sin detalles agregados</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>Forma de Pago</label>
                            <select class="form-control">
                                <option>Efectivo</option>
                                <option>Transferencia</option>
                                <option>Cheque</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>N° Comprobante</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Observación</label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn btn-success me-2">Guardar</button>
                        <button class="btn btn-danger">Cancelar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
