@extends('layouts.AdminLTE.index')
@section('title', 'Agregar Depósito')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Agregar Depósito</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_nombre" class="form-label">Nombre del Depósito</label>
                            <input type="text" id="txt_nombre" name="txt_nombre" class="form-control" placeholder="Ingrese nombre">
                        </div>
                        <div class="col-md-6">
                            <label for="cbx_sucursal" class="form-label">Sucursal</label>
                            <select id="cbx_sucursal" name="cbx_sucursal" class="form-control">
                                <option value="">Seleccione una sucursal</option>
                                <option value="1">Sucursal Central</option>
                                <option value="2">Sucursal Norte</option>
                                <option value="3">Sucursal Sur</option>
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
