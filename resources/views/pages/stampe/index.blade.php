@extends('layouts.AdminLTE.index')
@section('title', 'Timbrado')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header d-flex justify-content-between">
                <h5>Timbrados</h5>
                <a href="/timbrado/create" class="btn btn-success">Agregar</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Número</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>12345678</td>
                            <td>01/01/2025</td>
                            <td>31/12/2025</td>
                            <td><span class="badge bg-info">Activo</span></td>
                            <td>
                                <a class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i> Editar</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">No hay timbrados registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
