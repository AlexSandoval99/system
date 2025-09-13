@extends('layouts.AdminLTE.index')
@section('title', 'Materia Prima ')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="card border-primary">
                <div class="card-header d-flex justify-content-between">
                    <h5>Pedidos de Materia Prima</h5>
                    <button class="btn btn-success">Agregar</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Depósito</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01/09/2025</td>
                                <td>Depósito Central</td>
                                <td><span class="badge bg-info">Pendiente</span></td>
                                <td><a class=""><i class="fa fa-pencil-alt"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
