@extends('layouts.AdminLTE.index')
@section('title', 'Registrar Usuario')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Agregar Usuario</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_nombre" class="form-label">Nombre</label>
                            <input type="text" id="txt_nombre" name="txt_nombre" class="form-control" placeholder="Ingrese nombre">
                        </div>
                        <div class="col-md-6">
                            <label for="txt_email" class="form-label">Email</label>
                            <input type="email" id="txt_email" name="txt_email" class="form-control" placeholder="Ingrese correo">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_password" class="form-label">Contraseña</label>
                            <input type="password" id="txt_password" name="txt_password" class="form-control" placeholder="Ingrese contraseña">
                        </div>
                        <div class="col-md-6">
                            <label for="txt_avatar" class="form-label">Avatar</label>
                            <input type="file" id="txt_avatar" name="txt_avatar" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="cbx_sucursal" class="form-label">Sucursal</label>
                            <select id="cbx_sucursal" name="cbx_sucursal" class="form-control">
                                <option value="">Seleccione sucursal</option>
                                <option value="1">Sucursal Central</option>
                                <option value="2">Sucursal 2</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cbx_equipo" class="form-label">Equipo</label>
                            <select id="cbx_equipo" name="cbx_equipo" class="form-control">
                                <option value="">Seleccione equipo</option>
                                <option value="1">Equipo A</option>
                                <option value="2">Equipo B</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cbx_rol" class="form-label">Rol</label>
                            <select id="cbx_rol" name="cbx_rol" class="form-control">
                                <option value="">Seleccione rol</option>
                                <option value="admin">Administrador</option>
                                <option value="operador">Operador</option>
                                <option value="consulta">Consulta</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Guardar</button>
                        <a href="{{ url('usuarios') }}" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
