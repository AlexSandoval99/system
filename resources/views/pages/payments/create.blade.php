@extends('layouts.AdminLTE.index')

@section('title', 'Registrar Cobro')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Registrar Cobro</h5>
    </div>
    <div class="card-body">
        {{ Form::open(['id' => 'formCobro']) }}

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Sucursal</label>
                {{ Form::select('branch_id', $branches, null, ['class' => 'form-control', 'placeholder' => 'Seleccione', 'id' => 'branch_id']) }}
            </div>
            <div class="col-md-3">
                <label>Punto de Expedición</label>
                <select class="form-control" name="expedicion" id="expedicion"></select>
            </div>
            <div class="col-md-3">
                <label>Caja</label>
                {{ Form::select('caja_id', $cash_boxes, null, ['class'=>'form-control', 'id'=>'caja_id']) }}
            </div>
            <div class="col-md-3">
                <label>N° Recibo</label>
                <input type="text" class="form-control" name="voucher_number" id="voucher_number">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Fecha Recibo</label>
                <input type="date" class="form-control" name="fecha" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label>Cliente</label>
                <select class="form-control" id="cliente_id" name="cliente_id"></select>
            </div>
            <div class="col-md-3">
                <label>RUC</label>
                <input type="text" id="ruc" class="form-control" name="ruc">
            </div>
            <div class="col-md-3">
                <label>Razón Social</label>
                <input type="text" id="razon_social" class="form-control" name="razon_social">
            </div>
        </div>

        <!-- 🔹 FACTURA A COBRAR -->
        <div class="card mt-3">
            <div class="card-header">Detalle de Cuotas</div>
            <div class="card-body p-2">
                <div class="col-md-4">
                    <label>Factura a Cobrar</label>
                    <select class="form-control" id="factura_id" name="factura_id">
                        <option value="">Seleccione una factura</option>
                    </select>
                </div>
                <table class="table table-sm" id="tabla_cuotas">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vencimiento</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4" class="text-center">Seleccione una factura</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 🔹 DETALLE COBRO -->
        <div class="card mt-3">
            <div class="card-header">Cuotas Seleccionadas</div>
            <div class="card-body p-2">
                <table class="table table-bordered" id="tabla_detalle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Factura</th>
                            <th>Cuota</th>
                            <th>Monto a Cobrar</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- 🔹 FORMAS DE PAGO -->
        <div class="card mt-3">
            <div class="card-header">Formas de Pago</div>
            <div class="card-body p-2">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Forma de Pago</label>
                        <select class="form-control" id="forma_pago">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>N° Comprobante</label>
                        <input type="text" id="nro_comprobante" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Monto</label>
                        <input type="number" id="monto_pago" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn btn-success" onclick="agregarFormaPago()">Agregar</button>
                    </div>
                </div>
                <table class="table table-bordered" id="tabla_pagos">
                    <thead>
                        <tr>
                            <th>Forma</th>
                            <th>Comprobante</th>
                            <th>Monto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Observación -->
        <div class="row mt-3">
            <div class="col-md-12">
                <label>Observación</label>
                <textarea name="observacion" class="form-control"></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary me-2">Guardar</button>
            <a href="{{ route('payments') }}" class="btn btn-danger">Cancelar</a>
        </div>

        {{ Form::close() }}
    </div>
</div>
@endsection

@section('layout_js')
<script>
    $(document).ready(function(){

        $("#branch_id").on("change", function()
        {
            let branchId = $(this).val();
            $.ajax({
                url: '{{ url('ajax/expedition') }}',
                type: 'GET',
                data: { branch_id: branchId },
                success: function(data)
                {
                    let select = $('#expedicion');
                    select.empty();
                    select.append('<option value="">Seleccione Punto Expedicion </option>')
                    $.each(data, function(id, item) {
                        select.append('<option value="' + item.id + '">' + item.establecimiento + '-' + item.expedicion + '</option>');
                    });

                    let selectNota = $('#expedicion_nota');
                    selectNota.empty();
                    selectNota.append('<option value="">Seleccione Punto Expedicion </option>')
                    $.each(data, function(id, item) {
                        selectNota.append('<option value="' + item.id + '">' + item.establecimiento + '-' + item.expedicion + '</option>');
                    });
                },
                error:function(){
                    swal({
                        title: "SISTEMA",
                        text: "No recupera el punto expedicion",
                        icon: "info",
                        button: "OK",
                    });
                }
            })
        });

        $('#expedicion').on("change", function(){
            let expedicion = $(this).val();
            $.ajax({
                url: '{{url('ajax/timbrado')}}',
                type: 'GET',
                data: {
                    expedicion : expedicion,
                    voucher_type : 3
                },
                success: function(data)
                {
                    $('#timbrado').val(data.timbrado);
                    $('#voucher_number').val(data.numero);
                    $('#vig_timbrado').val(data.vig_timbrado);
                    $('#id_timb').val(data.id_timbrado)
                },
                error:function(){
                    swal({
                        title: "SISTEMA",
                        text: "No recupera el timbrado",
                        icon: "info",
                        button: "OK",
                    });
                }
            })
        });

        // Cliente con select2 y AJAX
        $("#cliente_id").select2({
            language: 'es',
            minimumInputLength: 2,
            ajax: {
                url: '{{ url('ajax/clients') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) { return { results: data.items }; }
            },
            templateSelection: function (client) {
                $("#ruc").val(client.ruc);
                $("#razon_social").val(client.name);

                // 🚀 cuando elijo cliente, cargar sus facturas
                cargarFacturas(client.id);

                return client.name;
            }
        });

        // Cuando selecciona factura → cargar cuotas
        $("#factura_id").on("change", function(){
            let facturaId = $(this).val();
            if(facturaId){
                cargarCuotas(facturaId);
            }
        });

        $('#formCobro').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: '{{ route('payments.store') }}',
                type: "POST",
                data: new FormData(this),
                dataType:'JSON',
                contentType: false,
                processData: false,
                success: function(data) {
                    swal({title:"Éxito", text:data.message, icon:"success", timer:2000, buttons:false})
                    .then(()=>{ window.location.href = data.redirect; });
                },
                error: function(xhr){
                    swal("Error", "Ocurrió un error al guardar el cobro", "error");
                }
            });
        });

    });

    // 🚀 Nueva función: cargar facturas de un cliente
    function cargarFacturas(clienteId){
        $.get('/ajax/invoices-by-client/'+clienteId, function(data){
            let facturaSelect = $("#factura_id");
            facturaSelect.empty();
            facturaSelect.append('<option value="">Seleccione una factura</option>');

            if(data.length === 0){
                facturaSelect.append('<option value="">Sin facturas disponibles</option>');
            } else {
                $.each(data, function(i, factura){
                    facturaSelect.append(`<option value="${factura.id}">${factura.fecha}' - '${factura.numero}</option>`);
                });
            }
        });
    }

    // 🚀 Cargar cuotas vía AJAX (igual que ya tienes)
    function cargarCuotas(facturaId){

        $.get('/ajax/voucher-collects/'+facturaId, function(data){
            let tbody = $("#tabla_cuotas tbody");
            tbody.empty();
            if(data.length === 0){
                tbody.append('<tr><td colspan="4" class="text-center">No hay cuotas pendientes</td></tr>');
            } else {
                $.each(data, function(i, cuota){
                    tbody.append(`
                        <tr>
                            <td>${i+1}</td>
                            <td>${cuota.vencimiento}</td>
                            <td>${cuota.monto}</td>
                            <td>${cuota.residue}</td>
                            <td>
                                ${
                                    cuota.residue > 0
                                    ? `<button type="button" class="btn btn-sm btn-primary"
                                            onclick="agregarCobro(${cuota.id}, '${cuota.factura}', ${cuota.residue}, ${cuota.cuota})">
                                            Cobrar
                                    </button>`
                                    : `<span class="text-muted">Pagada</span>`
                                }
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    // Agregar cuota seleccionada al detalle
    function agregarCobro(id, factura, saldo,cuota){
        $("#tabla_detalle tbody").append(`
            <tr>
                <td>${id}</td>
                <td>${factura}</td>
                <td>
                    <input type="hidden" name="cuota_id[]" value="${id}">
                    ${cuota}
                </td>
                <td><input type="number" class="form-control" name="monto_cuota[]" value="${saldo}"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">X</button></td>
            </tr>
        `);
    }

    // Agregar forma de pago
    function agregarFormaPago(){
        let forma = $("#forma_pago").val();
        let nro = $("#nro_comprobante").val();
        let monto = $("#monto_pago").val();

        if(!monto || monto <= 0){
            swal("Aviso", "Debe ingresar un monto válido", "info");
            return;
        }

        $("#tabla_pagos tbody").append(`
            <tr>
                <td>
                    ${forma}
                    <input type="hidden" name="forma_pago[]" value="${forma}">
                </td>
                <td>
                    ${nro}
                    <input type="hidden" name="nro_comprobante[]" value="${nro}">
                </td>
                <td>
                    ${monto}
                    <input type="hidden" name="monto_pago[]" value="${monto}">
                </td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">X</button></td>
            </tr>
        `);

        // limpiar inputs
        $("#nro_comprobante").val('');
        $("#monto_pago").val('');
    }
</script>
@endsection
