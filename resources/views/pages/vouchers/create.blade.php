@extends('layouts.AdminLTE.index')
@section('title', 'Registro de Ventas')
@section('content')

<div class="card">
    <div class="card-header">
        <h5>Agregar Documento</h5>
    </div>
    <div class="card-body">
        <!-- Tipo de Documento -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Tipo de Documento</label>
                <select class="form-control" id="tipoDocumento" onchange="mostrarCampos()">
                    <option value="">Seleccione</option>
                    <option value="factura">Factura</option>
                    <option value="nota_credito">Nota de Crédito</option>
                    <option value="nota_remision">Nota de Remisión</option>
                </select>
            </div>
        </div>

        <!-- Campos Comunes -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Sucursal</label>
                <select class="form-control"></select>
            </div>
            <div class="col-md-3">
                <label>Cliente</label>
                <select class="form-control"></select>
            </div>
            <div class="col-md-3">
                <label>RUC</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Razón Social</label>
                <input type="text" class="form-control">
            </div>
        </div>

        <!-- FACTURA -->
        <div id="facturaCampos" style="display:none;">
            <h6>Datos de Factura</h6>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Punto de Expedición</label>
                    <select class="form-control"></select>
                </div>
                <div class="col-md-3">
                    <label>Nro. Timbrado</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Vigencia Timbrado</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Nro. Factura</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-3 mt-2">
                    <label>Fecha Documento</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3 mt-2">
                    <label>Condición</label>
                    <select class="form-control">
                        <option>Contado</option>
                        <option>Crédito</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- NOTA DE CRÉDITO -->
        <div id="notaCreditoCampos" style="display:none;">
            <h6>Datos de Nota de Crédito</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Punto de Expedición</label>
                    <select class="form-control"></select>
                </div>
                <div class="col-md-4">
                    <label>Nro. Timbrado</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Vigencia Timbrado</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Nro. Nota de Credito</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Fecha Documento</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Factura Asociada</label>
                    <select class="form-control"></select>
                </div>
            </div>
        </div>

        <!-- NOTA DE REMISIÓN -->
        <div id="notaRemisionCampos" style="display:none;">
            <h6>Datos de Nota de Remisión</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nro. Nota</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Fecha Documento</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-6 mt-2">
                    <label>Dirección Entrega</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-md-6 mt-2">
                    <label>Transportista</label>
                    <input type="text" class="form-control">
                </div>
            </div>
        </div>

        <!-- Items -->
        <h6>Items</h6>
        <div class="row mb-3">
            <div class="col-md-5">
                <label>Artículo</label>
                <select class="form-control"></select>
            </div>
            <div class="col-md-3">
                <label>Cantidad</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Monto</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-success w-100">+</button>
            </div>
        </div>

        <!-- Tabla Items -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center">No hay items cargados</td>
                </tr>
            </tbody>
        </table>

        <!-- Observación -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Observación</label>
                <textarea class="form-control"></textarea>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary me-2">Guardar</button>
            <button class="btn btn-danger">Cancelar</button>
        </div>
    </div>
</div>

<script>
function mostrarCampos() {
    let tipo = document.getElementById('tipoDocumento').value;
    document.getElementById('facturaCampos').style.display = (tipo === 'factura') ? 'block' : 'none';
    document.getElementById('notaCreditoCampos').style.display = (tipo === 'nota_credito') ? 'block' : 'none';
    document.getElementById('notaRemisionCampos').style.display = (tipo === 'nota_remision') ? 'block' : 'none';
}
</script>

@endsection
