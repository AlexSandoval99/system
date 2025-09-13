@extends('layouts.AdminLTE.index')
@section('title', 'Depósitos')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header d-flex justify-content-between">
                <h5>Depósitos</h5>
                <button class="btn btn-success">Agregar</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Depósito Central</td>
                            <td>Sucursal Principal</td>
                            <td><span class="badge bg-info">Activo</span></td>
                            <td>
                                <a class="btn btn-primary btn-sm">Editar</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center">No hay depósitos registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
