@extends('layouts.AdminLTE.index')
@section('title', 'Ventas')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="card border-primary">
                <div class="card-header d-flex justify-content-between">
                    <h5>Ventas</h5>
                    <button class="btn btn-success">Agregar</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
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
                            <tr>
                                <td>1</td>
                                <td>Central</td>
                                <td>02/09/2025</td>
                                <td>Contado</td>
                                <td>1234567-8</td>
                                <td>Cliente Ejemplo</td>
                                <td><span class="badge bg-info">Pendiente</span></td>
                                <td>1.200.000</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">PDF</button>
                                    <button class="btn btn-sm btn-danger">Anular</button>
                                </td>
                            </tr>
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
