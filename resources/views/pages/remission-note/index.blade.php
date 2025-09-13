@extends('layouts.AdminLTE.index')
@section('title', 'Notas de Remisión')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header d-flex justify-content-between">
                <h5>Notas de Remisión</h5>
                <a href="#" class="btn btn-success">Agregar</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Fecha Remisión</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Cliente Ejemplo</td>
                            <td>Camión ABC-123</td>
                            <td>02/09/2025</td>
                            <td><span class="badge bg-info">Pendiente</span></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Ver</a>
                                <a href="#" class="btn btn-warning btn-sm">Editar</a>
                                <a href="#" class="btn btn-danger btn-sm">Anular</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">No hay registros</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
