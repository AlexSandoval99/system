@extends('layouts.AdminLTE.index')
@section('title', 'Gestión de Usuarios')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Usuarios</h5>
                <div class="d-flex justify-content-end">
                    <a href="{{ url('usuarios/create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí iría la carga dinámica -->
                        <tr>
                            <td>Alex Sandoval</td>
                            <td>alexhumberto.s.i@gmail.com</td>
                            <td>Administrador</td>
                            <td>Central</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>juan</td>
                            <td>juan@gmail.com</td>
                            <td>Operador</td>
                            <td>Sucursal 2</td>
                            <td><span class="badge bg-danger">Inactivo</span></td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
