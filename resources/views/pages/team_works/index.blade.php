@extends('layouts.AdminLTE.index')
@section('title', 'Equipos')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header d-flex justify-content-between">
                <h5>Gestión de Equipos</h5>
                <a href="#" class="btn btn-success">Agregar</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Trabajo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Máquina A</td>
                            <td>Corte</td>
                            <td><span class="badge bg-info">Activo</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Editar</a>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td colspan="5" class="text-center">No hay equipos registrados</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
