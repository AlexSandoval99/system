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
                        <option value="factura">Factura</option>
                        <option value="nota_credito">Nota de Crédito</option>
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
                        <select class="form-control" name="condicion">
                            <option>Contado</option>
                            <option>Crédito</option>
                        </select>
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
                    <input type="text" id="ruc" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Razón Social</label>
                    <input type="text" id="razon_social" class="form-control">
                </div>
                <!-- 🔹 Panel de Productos Terminados -->
                <div class="col-md-3">
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
            {{-- <h6>Items</h6>
            <div class="row mb-3">
                <div class="col-md-5">
                    <label>Artículo</label>
                    {{Form::select('articulo_id', $articulos, null,['class'=>'form-control', 'placeholder'=>'Seleccione'])}}
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
            </div> --}}

            <!-- Tabla Items -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Monto</th>
                        <th>Monto Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tbodyItem">
                </tbody>
            </table>

            <!-- Observación -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Observación</label>
                    <textarea class="form-control"></textarea>
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
                        redirect ("{{ url('vouchers') }}");
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
                                                    <button class="btn btn-xs btn-success" onclick="agregarProducto(${item.id})">
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
                    return client.name + ' | ' + client.ruc;
                }
            });
        });

        function mostrarCampos()
        {
            let tipo = document.getElementById('tipoDocumento').value;
            document.getElementById('facturaCampos').style.display = (tipo === 'factura') ? 'block' : 'none';
            document.getElementById('notaCreditoCampos').style.display = (tipo === 'nota_credito') ? 'block' : 'none';
            document.getElementById('notaRemisionCampos').style.display = (tipo === 'nota_remision') ? 'block' : 'none';
        }
        function agregarProducto(id)
        {
            $.ajax({
                url: '{{ url('ajax/production-order-detail') }}/' + id,
                type: 'GET',
                success: function(data)
                {
                    $.each(data, function(index, data)
                    {
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
                                        ${data.precio}
                                        <input type="hidden" name="precio[]" value="${data.precio}"
                                    </td>
                                    <td>
                                        ${data.total_precio}
                                        <input type="hidden" name="precioTotal[]" value="${data.total_precio}"
                                    </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove()">X</button>
                                </td>
                            </tr>`
                        );
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
                data: {expedicion : expedicion},
                success: function(data)
                {
                    $('#timbrado').val(data.timbrado);
                    $('#voucher_number').val(data.numero);
                    $('#vig_timbrado').val(data.vig_timbrado);
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

    </script>
@endsection
