@extends('layouts.AdminLTE.index')
@section('title', 'Registro de Ventas')
@section('content')

<div class="card">
    <div class="card-header">
        <h5>Agregar Documento</h5>
    </div>
    <div class="card-body">
        {{ Form::open(['id' => 'form']) }}
            <!-- Tipo de Documento -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Tipo de Documento</label>
                    <select class="form-control" id="tipoDocumento" name="tipoDocumento" onchange="mostrarCampos()">
                        <option value="1">Factura</option>
                        <option value="2">Nota de Crédito</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Sucursal</label>
                    {{ Form::select('branch_id', $branches, null, ['class' => 'form-control', 'placeholder' => 'Seleccione', 'id' => 'branch_id']) }}
                </div>
            </div>
            <br>
            <!-- FACTURA -->
            <div id="facturaCampos" style="display:none;">
                <h6>Datos de Factura</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Punto de Expedición</label>
                        <select class="form-control" name="expedicion" id="expedicion"></select>
                    </div>
                    <div class="col-md-3">
                        <label>Nro. Timbrado</label>
                        <input type="text" id="timbrado" name="timbrado" class="form-control" autocomplete="off">
                        <input type="hidden" id="id_timb" name="id_timb">
                    </div>
                    <div class="col-md-3">
                        <label>Vigencia Timbrado</label>
                        <input type="text" id="vig_timbrado" name="vig_timbrado" class="form-control" disabled>
                    </div>
                    <div class="col-md-3">
                        <label>Nro. Factura</label>
                        <input type="text" id="voucher_number" name="voucher_number" class="form-control">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label>Fecha Documento</label>
                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label>Condición</label>
                        <select class="form-control" name="condicion" id="condicion" onchange="mostrarIntervalo()">
                            <option value="1">Contado</option>
                            <option value="2">Crédito</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-2" id="div_intervalo" style="display: none;">
                        <label>Intervalo</label>
                         <input type="text" name="intervalo" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Cliente</label>
                    <select class="form-control" name="client_id" id="client_id"></select>
                </div>
                <div class="col-md-3">
                    <label>RUC</label>
                    <input type="text" id="ruc" name="ruc" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Razón Social</label>
                    <input type="text" id="razon_social" name="razon_social" class="form-control">
                </div>
                <!-- 🔹 Panel de Productos Terminados -->
                <div class="col-md-3" id="productosTerminadosPanel">
                    <div class="card">
                        <div class="card-header">
                            <h6>Productos Terminados</h6>
                        </div>
                        <div class="card-body p-2" style="max-height:200px; overflow-y:auto;">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Fecha</th>
                                        <th>Sucursal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="productos_terminados_tbody">
                                    <tr><td colspan="3" class="text-center">Seleccione un cliente</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOTA DE CRÉDITO -->
            <div id="notaCreditoCampos" style="display:none;">
                <h6>Datos de Nota de Crédito</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Punto de Expedición</label>
                        <select class="form-control" name="expedicion_nota" id="expedicion_nota"></select>
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
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mt-2">
                        <label>Factura Asociada</label>
                        <select class="form-control" name="factura_asociada" id="factura_asociada" style="width: 100%"></select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label>Condición</label>
                        <input type="text" id="invoice_condition" name="invoice_condition" class="form-control" disabled>
                        <input type="hidden" id="invoice_id" name="invoice_id">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label>Fecha Factura</label>
                        <input type="text" id="invoice_date" name="invoice_date" class="form-control" disabled>
                    </div>
                    <div class="col-md-2 mt-2">
                        <label>Monto Factura</label>
                        <input type="text" id="invoice_amount" name="invoice_amount" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <!-- Tabla Items -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Exenta</th>
                        <th>IVA 5%</th>
                        <th>IVA 10%</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tbodyItem">
                    <!-- Aquí se insertan las filas dinámicamente -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Subtotales</td>
                        <td class="text-end" id="subExenta" name="exenta">0,00</td>
                        <td class="text-end" id="subIva5" name="iva5">0,00</td>
                        <td class="text-end" id="subIva10" name="iva10">0,00</td>
                        <td class="text-end" id="total" name="total">0,00</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="9" id="ivaInfo" class="fw-bold">
                            IVA 5% 0,00 - IVA 10% 0,00
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Observación -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Observación</label>
                    <textarea id="observacion" name="observacion" class="form-control"></textarea>
                </div>
            </div>
            <br>
            <!-- Botones -->
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary me-2">Guardar</button>
                <button class="btn btn-danger">Cancelar</button>
            </div>
        {{ Form::close() }}
    </div>
</div>
@endsection
@section('layout_js')
    <script>
        $(document).ready(function() {
            mostrarCampos();
            mostrarIntervalo();

            $('#form').submit(function(e) {
                $('input[type="submit"]').prop('disabled', true);
                e.preventDefault();
                $.ajax({
                    url: '{{ route('voucher.store') }}',
                    type: "POST",
                    data: new FormData(this),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            swal({
                                title: "Éxito",
                                text: data.message,
                                icon: "success",
                                timer: 2000,
                                buttons: false
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        }
                    },
                    error: function(data){
                        laravelErrorMessages(data);
                        $('input[type="submit"]').prop('disabled', false);
                    }
                });
            });

            $('#branch_id').select2();

            $("#client_id").select2({
                language: 'es',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ url('ajax/clients') }}',
                    dataType: 'json',
                    // cache: true,
                    method: 'GET',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.items
                        };
                    }
                },
                escapeMarkup: function (markup) { return markup; },
                templateResult: function (client) {
                    if (client.loading) return client.text;

                    var markup = client.name + "<br>" +"<i class='fa fa-id-card'></i> " + client.ruc ;
                    return markup;
                },
                templateSelection: function (client) {
                    $("#ruc").val(client.ruc);
                    $("#razon_social").val(client.name);

                    if (client.id)
                    {
                        let tipo = $("#tipoDocumento").val();
                        if(tipo === '1')
                        {
                            $.ajax({
                                url: '{{ url('ajax/production-orders') }}/' + client.id,
                                type: 'GET',
                                success: function(data) {
                                    let tbody = $("#productos_terminados_tbody");
                                    tbody.empty();

                                    if (data.length === 0)
                                    {
                                        tbody.append('<tr><td colspan="3" class="text-center">No hay productos terminados</td></tr>');
                                    }
                                    else
                                    {
                                        $.each(data, function(i, item) {
                                            tbody.append(
                                                `<tr>
                                                    <td>${item.id}</td>
                                                    <td>${item.date}</td>
                                                    <td>${item.branch}</td>
                                                    <td>
                                                        <button class="btn btn-xs btn-success" onclick="agregarProducto(${item.id},event)">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>`
                                            );
                                        });
                                    }
                                }
                            });
                        }
                    }
                    return client.name + ' | ' + client.ruc;
                }
            });

            $("#factura_asociada").select2({
                language: 'es',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ url('ajax/invoices') }}',
                    dataType: 'json',
                    method: 'GET',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            client_id: $('#client_id').val(),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items
                        };
                    }
                },
                escapeMarkup: function (markup) { return markup; },
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    return repo.text;
                },
                templateSelection: function (repo) {
                    return repo.text;
                }
            }).on("select2:select", function (e) {
                var data_item = e.params.data;

                $('#invoice_condition').val(data_item.condition);
                $('#invoice_date').val(data_item.date);
                $('#invoice_id').val(data_item.id);
                $('#invoice_amount').val($.number(data_item.total, 0, ',', '.'));
                $('#invoice_residue').val($.number(data_item.residue, 0, ',', '.'));

                // 🔹 limpiar tabla antes de cargar
                $("#tbodyItem").html('');
                $("#detail_product_invoice").show();

                $.each(data_item.products, function(index, value) {
                    $('#tbodyItem').append(`
                        <tr>
                            <td class="text-center">${value.id}</td>
                            <td>
                                ${value.name}
                                <input type="hidden" name="articulo[]" value="${value.id}">
                            </td>
                            <td class="text-center">
                                ${value.quantity}
                                <input type="hidden" name="quantity[]" value="${value.quantity}">
                            </td>
                            <td class="text-center">
                                ${value.amount}
                                <input type="hidden" class="precio" name="precio[]" value="${value.amount}">
                            </td>
                            <td class="text-center">
                                ${value.excenta}
                                <input type="hidden" class="exenta" name="exenta[]" value="${value.excenta}">
                            </td>
                            <td class="text-center">
                                ${value.iva5}
                                <input type="hidden" class="iva5" name="iva5[]" value="${value.iva5}">
                            </td>
                            <td class="text-center">
                                ${value.iva10}
                                <input type="hidden" class="iva10" name="iva10[]" value="${value.iva10}">
                            </td>
                            <td class="text-center">${value.subtotal}
                            <input type="hidden" class="subtotal" name="subtotal[]" value="${value.subtotal}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="$(this).closest('tr').remove(); recalcularTotales();">X</button>
                            </td>
                        </tr>
                    `);
                });

                recalcularTotales();
            });

        });

        function mostrarCampos()
        {
            let tipo = document.getElementById('tipoDocumento').value;
            document.getElementById('facturaCampos').style.display = (tipo === '1') ? 'block' : 'none';
            document.getElementById('notaCreditoCampos').style.display = (tipo === '2') ? 'block' : 'none';
            document.getElementById('productosTerminadosPanel').style.display = (tipo === '1') ? 'block' : 'none';
        }

        function mostrarIntervalo()
        {
            let condicion = document.getElementById('condicion').value;
            document.getElementById('div_intervalo').style.display = (condicion === '2') ? 'block' : 'none';
        }

        function agregarProducto(id,event)
        {
            if (event) event.preventDefault();
            $.ajax({
                url: '{{ url('ajax/production-order-detail') }}/' + id,
                type: 'GET',
                success: function(data)
                {
                    $.each(data, function(index, data)
                    {
                        let precioTotal = data.precio * data.quantity || 0;
                        let exenta = 0;
                        let iva5 = 0;
                        let iva10 = precioTotal;
                        $("#tbodyItem").append(
                            `<tr>
                                <td>${data.id}</td>
                                <td>${data.articulo}
                                <input type="hidden" name="articulo[]" value="${data.articulo_id}"
                                </td>

                                <td>
                                    ${data.quantity}
                                    <input type="hidden" name="quantity[]" value="${data.quantity}"

                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm text-end precio" name="precio[]" value="${data.precio}" disabled oninput="recalcularFila(this)">
                                    <input type="hidden" name="precio[]" value="${data.precio}"
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm text-end exenta"
                                        name="exenta[]" value="${exenta}" disabled
                                        oninput="recalcularFila(this)">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm text-end iva5"
                                        name="iva5[]" value="${iva5}" disabled
                                        oninput="recalcularFila(this)">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm text-end iva10"
                                        name="iva10[]" value="${iva10}" disabled
                                        oninput="recalcularFila(this)">
                                </td>
                                <td class="text-end subtotal">${precioTotal}</td>

                                 <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove(); recalcularTotales();">X</button>
                                </td>
                            </tr>`
                        );
                        recalcularTotales();
                    });
                },
                error: function() {
                    swal({
                        title: "SISTEMA",
                        text: "No se pudo recuperar el detalle del artículo.",
                        icon: "info",
                        button: "OK",
                    });
                }
            });
        }
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
                    voucher_type :
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

        function recalcularFila(input) {
            let row = $(input).closest("tr");
            let precio = parseFloat(row.find(".precio").val()) || 0;
            let cantidad = parseFloat(row.find("input[name='quantity[]']").val()) || 0;

            // Montos ingresados manualmente
            let exenta = parseFloat(row.find(".exenta").val()) || 0;
            let iva5 = parseFloat(row.find(".iva5").val()) || 0;
            let iva10 = parseFloat(row.find(".iva10").val()) || 0;

            // Calcular total fila
            let subtotal = (exenta + iva5 + iva10);
            row.find(".subtotal").text(subtotal.toLocaleString('es-PY', { minimumFractionDigits: 2 }));

            // Actualizar totales
            recalcularTotales();
        }

        function recalcularTotales() {
            let totalExenta = 0, totalIva5 = 0, totalIva10 = 0, totalCompra = 0;

            $("#tbodyItem tr").each(function() {
                totalExenta += parseFloat($(this).find(".exenta").val()) || 0;
                totalIva5 += parseFloat($(this).find(".iva5").val()) || 0;
                totalIva10 += parseFloat($(this).find(".iva10").val()) || 0;
                totalCompra += parseFloat($(this).find(".subtotal").text().replace(/\./g, '').replace(',', '.')) || 0;
            });

            // Mostrar en pie de tabla
            $("#subExenta").text(totalExenta.toLocaleString('es-PY', { minimumFractionDigits: 2 }));
            $("#subIva5").text(totalIva5.toLocaleString('es-PY', { minimumFractionDigits: 2 }));
            $("#subIva10").text(totalIva10.toLocaleString('es-PY', { minimumFractionDigits: 2 }));
            $("#totalCompra").text(totalCompra.toLocaleString('es-PY', { minimumFractionDigits: 2 }));

            let iva5calc = totalIva5 / 21;   // base IVA 5% en PY
            let iva10calc = totalIva10 / 11; // base IVA 10% en PY

            $("#ivaInfo").text(`IVA 5% ${iva5calc.toLocaleString('es-PY', { minimumFractionDigits: 2 })} - IVA 10% ${iva10calc.toLocaleString('es-PY', { minimumFractionDigits: 2 })}`);
        }

    </script>
@endsection
