@extends('layouts.AdminLTE.index')
@section('title', 'Informe de Producción')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Informe de Producción</h5>
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
                                <option value="1">Sucursal Central</option>
                                <option value="2">Sucursal Este</option>
                                <option value="3">Sucursal Norte</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary me-2">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                        <button type="button" class="btn btn-success">
                            <i class="fa fa-file-pdf"></i> Descargar PDF
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Sucursal</th>
                                <th>Orden Producción</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01/09/2025</td>
                                <td>Central</td>
                                <td>OP-00125</td>
                                <td>Cortinas Blackout</td>
                                <td>250</td>
                                <td><span class="badge bg-success">Finalizado</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>02/09/2025</td>
                                <td>Sucursal Este</td>
                                <td>OP-00126</td>
                                <td>Cortinas Roller</td>
                                <td>100</td>
                                <td><span class="badge bg-warning">En proceso</span></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center">No hay registros disponibles</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
