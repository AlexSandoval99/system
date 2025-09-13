@extends('layouts.AdminLTE.index')
@section('title', 'Informe de Compras')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Informe de Compras</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="txt_inicio_fecha" class="form-label">Fecha Inicio</label>
                            <input type="date" id="txt_inicio_fecha" name="txt_inicio_fecha" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="txt_fecha_fin" class="form-label">Fecha Fin</label>
                            <input type="date" id="txt_fecha_fin" name="txt_fecha_fin" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="cbx_sucursal" class="form-label">Sucursal</label>
                            <select id="cbx_sucursal" name="cbx_sucursal" class="form-control">
                                <option value="">Seleccione una sucursal</option>
                                <option value="1">Sucursal 1</option>
                                <option value="2">Sucursal 2</option>
                                <option value="3">Sucursal 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary me-2">Buscar</button>
                        <button type="button" class="btn btn-danger">Descargar PDF</button>
                    </div>
                </form>

                <hr>

                <h6>Resultados</h6>
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Sucursal</th>
                            <th>Proveedor</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>01/09/2025</td>
                            <td>Central</td>
                            <td>Proveedor XYZ</td>
                            <td>2.500.000</td>
                            <td><span class="badge bg-success">Aprobado</span></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">No hay resultados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
