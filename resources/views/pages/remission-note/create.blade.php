@extends('layouts.AdminLTE.index')
@section('title', 'Agregar Nota de Remisión')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Agregar Nota de Remisión</h5>
            </div>
            <div class="card-body">
                <form>
                    {{-- Datos principales --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="txt_sucursal" class="form-label">Sucursal</label>
                            <select id="txt_sucursal" name="txt_sucursal" class="form-control">
                                <option value="">Seleccione sucursal</option>
                                <option value="1">Central</option>
                                <option value="2">Sucursal 2</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cbx_puntoExpedicion" class="form-label">Punto de Expedición</label>
                            <select id="cbx_puntoExpedicion" name="cbx_puntoExpedicion" class="form-control">
                                <option value="">Seleccione punto</option>
                                <option value="1">Punto 1</option>
                                <option value="2">Punto 2</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cbx_timbrado" class="form-label">Timbrado</label>
                            <select id="cbx_timbrado" name="cbx_timbrado" class="form-control">
                                <option value="">Seleccione timbrado</option>
                                <option value="1">Timbrado 001</option>
                                <option value="2">Timbrado 002</option>
                            </select>
                        </div>
                    </div>

                    {{-- Datos del cliente --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cbx_cliente" class="form-label">Cliente</label>
                            <select id="cbx_cliente" name="cbx_cliente" class="form-control">
                                <option value="">Seleccione cliente</option>
                                <option value="1">Cliente 1</option>
                                <option value="2">Cliente 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="txt_ruc" class="form-label">RUC</label>
                            <input type="text" id="txt_ruc" name="txt_ruc" class="form-control" placeholder="Ingrese RUC">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_razon_social" class="form-label">Razón Social</label>
                            <input type="text" id="txt_razon_social" name="txt_razon_social" class="form-control" placeholder="Ingrese Razón Social">
                        </div>
                    </div>

                    {{-- Datos de la nota --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="txt_nroRemision" class="form-label">Nro. Remisión</label>
                            <input type="text" id="txt_nroRemision" name="txt_nroRemision" class="form-control" placeholder="Ingrese Nro. Remisión">
                        </div>
                        <div class="col-md-4">
                            <label for="txt_fecha" class="form-label">Fecha</label>
                            <input type="date" id="txt_fecha" name="txt_fecha" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="txt_direccion" class="form-label">Dirección</label>
                            <input type="text" id="txt_direccion" name="txt_direccion" class="form-control" placeholder="Dirección de entrega">
                        </div>
                    </div>

                    {{-- Datos del vehículo --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="txt_vehiculo" class="form-label">Vehículo</label>
                            <select id="txt_vehiculo" name="txt_vehiculo" class="form-control">
                                <option value="">Seleccione vehículo</option>
                                <option value="1">Toyota - ABC123</option>
                                <option value="2">Nissan - XYZ789</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="txt_chofer" class="form-label">Chofer</label>
                            <input type="text" id="txt_chofer" name="txt_chofer" class="form-control" placeholder="Nombre del chofer">
                        </div>
                        <div class="col-md-4">
                            <label for="txt_documento" class="form-label">Documento Chofer</label>
                            <input type="text" id="txt_documento" name="txt_documento" class="form-control" placeholder="Nro. Documento">
                        </div>
                    </div>

                    {{-- Detalles de artículos --}}
                    <h6>Artículos</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cbx_articulo" class="form-label">Artículo</label>
                            <select id="cbx_articulo" name="cbx_articulo" class="form-control">
                                <option value="">Seleccione artículo</option>
                                <option value="1">Tela Roja</option>
                                <option value="2">Tela Azul</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="txt_cantidad" class="form-label">Cantidad</label>
                            <input type="number" id="txt_cantidad" name="txt_cantidad" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary">Agregar</button>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center">No hay artículos agregados</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Observación --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_observacion" class="form-label">Observación</label>
                            <textarea id="txt_observacion" name="txt_observacion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    {{-- Botones --}}
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
