@extends('layouts.AdminLTE.index')
@section('title', 'Agregar Caja')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Agregar Caja</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_nombre" class="form-label">Nombre de la Caja</label>
                            <input type="text" id="txt_nombre" name="txt_nombre" class="form-control" placeholder="Ingrese nombre">
                        </div>
                        <div class="col-md-6">
                            <label for="txt_sucursal" class="form-label">Sucursal</label>
                            <select id="txt_sucursal" name="txt_sucursal" class="form-control">
                                <option value="">Seleccione una sucursal</option>
                                <option value="1">Sucursal Central</option>
                                <option value="2">Sucursal Norte</option>
                                <option value="3">Sucursal Sur</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cbx_puntoExpedicion" class="form-label">Punto de Expedición</label>
                            <select id="cbx_puntoExpedicion" name="cbx_puntoExpedicion" class="form-control">
                                <option value="">Seleccione un punto</option>
                                <option value="1">Punto 1</option>
                                <option value="2">Punto 2</option>
                                <option value="3">Punto 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Guardar</button>
                        <button type="reset" class="btn btn-danger">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
