@extends('layouts.AdminLTE.index')
@section('title', 'Cobros')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card border-primary">
                <div class="card-header d-flex justify-content-between">
                    <h5>Listado de Cobros</h5>
                    <a href="cobros/create" class="btn btn-success">Agregar</a>
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
                            <tr>
                                <td>1</td>
                                <td>Central</td>
                                <td>02/09/2025</td>
                                <td>0001-001-000123</td>
                                <td>500.000</td>
                                <td><span class="badge bg-info">Pendiente</span></td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm">Ver</a>
                                    <a href="#" class="btn btn-danger btn-sm">PDF</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
