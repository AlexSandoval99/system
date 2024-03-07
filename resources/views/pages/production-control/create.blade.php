@extends('layouts.AdminLTE.index')
@section('title', 'Control de Produccion')
@section('content')
<div class="row">
    {{ Form::open(['id' => 'form']) }}
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Agregar Control de Produccion</h5>
                        </div>
                        <div class="ibox-content pb-0">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>Numero Orden</label>
                                    <input class="form-control" type="text" name="number_order" id="number_order" placeholder="Numero Orden" autofocus>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Fecha</label>
                                    <input class="form-control" type="text" name="date" value="{{ old('date', date('d/m/Y')) }}" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <br>
                                    <button type="button" class="btn btn-primary" name="button_search" id="button_search"><i class="fa fa-search"></i> BUSCAR ORDEN</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox float-e-margins" id="div_details">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Cliente</label>
                            <input type="text" name="client" value="" id="client" class="form-control" readonly>
                            <input type="hidden" name="client_id" value="" id="client_id">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sucursal</label>
                            <input type="text" name="branch" value="" id="branch" class="form-control" readonly>
                            <input type="hidden" name="branch_id" value="" id="branch_id" >
                        </div>
                        <div class="form-group col-md-2">
                            <label>Fecha Pedido</label>
                            <input class="form-control" type="text" name="date_ped" id="date_ped" readonly>
                            <input class="form-control" type="hidden" name="total_amount" id="total" readonly>
                        </div>
                    </div>
                </div><br><br>
                <div class="ibox-title">
                    <h3>Items</h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabs-container">
                            <ul class="nav nav-tabs fs-3">
                                <li class="active"><a data-toggle="tab" href="#seccion1" onclick="ChangeTab1();"><h5>Primera Etapa </h5></a></li>
                                <li class=""><a data-toggle="tab" href="#seccion2" onclick="ChangeTab2();"><h5>Segunda Etapa </h5></a></li>
                                <li class=""><a data-toggle="tab" href="#seccion3" onclick="ChangeTab3();"><h5>Tercera Etapa </h5></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="seccion1">
                                    <div class="panel-body table-responsive" id="div_sec1">
                                        <table class="table table-stripped" >
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Articulo</th>
                                                    <th>Cantidad</th>
                                                    <th>Etapa</th>
                                                    <th>OBS:</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_detail1"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> 
                            <div class="tab-content">
                                <div class="tab-pane" id="seccion2">
                                    <div class="panel-body table-responsive" id="div_sec2">
                                        <table class="table table-stripped" data-limit-navigation="8" data-sort="true" data-paging="true" data-filter=#filter1>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Articulo</th>
                                                    <th>Cantidad</th>
                                                    <th>Etapa</th>
                                                    <th>OBS:</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_detail2"></tbody>

                                        </table>
                                    </div>
                                </div>
                            </div> 
                            <div class="tab-content">                                              
                                <div class="tab-pane" id="seccion3">
                                    <div class="panel-body table-responsive" id="div_sec3">
                                        <table class="table table-stripped" data-limit-navigation="8" data-sort="true" data-paging="true" data-filter=#filter1>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Articulos</th>
                                                    <th>Cantidad</th>
                                                    <th>Etapa</th>
                                                    <th>OBS:</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_detail3"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>         
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-footer" id="div_footer">
                <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                <a href="{{ url('budget-production') }}" class="btn btn-sm btn-danger">Cancelar</a>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Control</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="etapa">Etapa Verificada:</label>
                                    <br>
                                    <td><span id="stage_name"></span></td>
                                    <input type="checkbox" id="etapa" name="etapa">
                                </div>
                                <div class="col-md-6">
                                    <label for="total">Cantidad Total:</label>
                                    <input type="text" id="total_quantity" name="total" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="observacion">Observación:</label>
                                    <input type="text" id="observacion" name="observacion" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="cantidad_controlada">Cantidad Controlada:</label>
                                    <input type="text" id="cantidad_controlada" name="cantidad_controlada" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        {{ Form::close() }}
</div>
@endsection
@section('layout_css')
<style>
    #div_provider_data, #div_invoice_detail, #div_invoice_header, #div_image, #div_note_credits{
            position: relative;
            margin: auto;
            width: 100%;
            border: 3px solid #C8C4C4;
            padding: 10px;
            border-radius: 5px;
        }
</style>
@endsection

@section('layout_js')
    <script>
        var invoice_items_array = [];
        $(document).ready(function ()
        {

            $('#form').submit(function(e)
            {
                $('input[type="submit"]').prop('disabled', true);
                e.preventDefault();
                $.ajax({
                    url: '{{ route('budget-production-store') }}',
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        redirect ("{{ url('budget-production') }}");
                    },
                    error: function(data){
                        laravelErrorMessages(data);
                        $('input[type="submit"]').prop('disabled', false);
                    }
                });
            });

            $('#articulo_id').change(function() {
                var articuloId = $(this).val();
                // Realizar una solicitud al servidor para obtener el precio del artículo
                $.ajax({
                    url: '{{ url('ajax/articulo') }}',
                    method: 'GET',
                    data: { articulo_id: articuloId },
                    success: function(response) {
                        if(response.items)
                        {
                            $('#price').val(response.items.price);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // Manejar el error si es necesario
                    }
                });
            });

            $("#button_search").click(function() {
                Search_order();
            });

            $("#btn_expiration_date").click(function() {
                AddExpirationDetail();
            });

            $('#number_ped').keypress(function(e){
                if (e.keyCode == 13)
                {
                    $("#button_search").click();
                    e.preventDefault();
                    return false;
                }
            });

            $("#button_add_product").click(function() {
                addProduct();
            });
            $(document).ready(function(){
                $(document).on('click', '.open-modal', function () {
                    var stageName = $(this).data('stage');
                    var quantity = $(this).data('quantity');

                    // Poblar el modal con los datos
                    $('#stage_name').text(stageName);
                    $('#total_quantity').val(quantity);
                });
            });

            loadDate();
            
        });

        function ChangeTab1()
        {
                $('#div_sec1').show();
                $('#div_sec3').hide();
                $('#div_sec2').hide();
                
        }
        function ChangeTab2()
        {
                $('#div_sec2').show();
                $('#div_sec1').hide();
                $('#div_sec3').hide();
                
        }
        
        function ChangeTab3()
        {
                $('#div_sec3').show();
                $('#div_sec1').hide();
                $('#div_sec2').hide();
                
        }
        function loadDate()
        {
            $(".date").datepicker({
                format: 'dd/mm/yyyy',
                language: 'es',
                autoclose: true,
                todayBtn: true,
                todayBtn: "linked",
                daysOfWeekDisabled: [0]
            });

            $("[period-data-mask]").inputmask({
                alias: 'decimal',
                groupSeparator: '.',
                radixPoint: ',',
                autoGroup: true,
                allowMinus: false,
                rightAlign: false,
                digits: 0,
                removeMaskOnSubmit: true,
            });

            $("[date-mask]").inputmask({
                alias: 'date'
            });

            $(".date").datepicker({
                format: 'dd/mm/yyyy hh:ii',
                language: 'es',
                autoclose: true,
                todayBtn: true,
            });

            $("[date-mask]").inputmask({
                alias: 'date'
            });
        }

         function addProduct()
        {
            var product_name              = $("select[name='articulo_id'] option:selected").text();
            var product_id                = $("select[name='articulo_id']").val();
            var price                     = $("#price").val();
            var product_description       = $("#products_description").val();
            var product_quantity          = $("input[name='products_quantity']").val().replace(/\./g, '');
            product_quantity              = (product_quantity > 0 ? product_quantity : 1);

            if(product_id!='' && product_quantity!='')
            {
                if($.inArray(product_id, invoice_items_array) != '-1')
                {
                    if(confirm('Ya existe el producto, desea continuar?'))
                    {
                        var description = product_description ? product_description : product_name;
                        var subtotal = product_quantity * price;
                        addToTable(product_id, description, product_quantity, product_description,price,subtotal);
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    var description = product_description ? product_description : product_name;
                    var subtotal = product_quantity * price;
                    addToTable(product_id, description, product_quantity,product_description,product_name,price,subtotal);
                }

                $('#articulo_id').val(null).trigger('change');
                $("#products_description").val('');
                $("input[name='products_quantity']").val('');

            }
            else
            {
                swal({
                    title: "SISTEMA",
                    text: "Hay campos vacíos",
                    icon: "warning",
                    button: "OK",
                });
                return false;
            }
        }

        function loadPeriodDataMaskDecimal()
        {
            $("[period-data-mask-decimal]").inputmask({
                alias: 'decimal',
                groupSeparator: '.',
                radixPoint: ',',
                autoGroup: true,
                allowMinus: false,
                rightAlign: true,
                digits: 2,
                removeMaskOnSubmit: true,
            });
        }

        function removeRow(detail_id)
        {
            invoice_items_array = jQuery.grep(invoice_items_array, function(value) {
                return value != detail_id;
            });

            $('input[name^="order_detail_id[]"]').each(function ()
            {
                if($(this).val() == detail_id)
                {
                    $(this).parent().remove();
                }
            });

            calculateIva();
        }

        changeStatus();
        function changeStatus()
        {
            $("#div_details, #div_deposito, #div_footer").hide();

            $("#number_ped").prop("readonly", false);
            $("#button_search").show();
        }
        function Search_order()
        {
            var number_order    = $("#number_order").val();
            var conteo           = 0;
            $('#tbody_detail1').html('');

            if(number_order != '')
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ajax.control-production') }}',
                    type: "GET",
                    data: { number_order : number_order, sesion:1},
                    success: function(data) {
                        $.each(data.items, function(index, element) {
                            invoice_items_array.push(element.product_id);

                            $('#tbody_detail1').append(     
                            '<tr>' +
                                '<td>' + element.product_id + '</td>' +
                                '<td>' + element.product_name + '</td>' +
                                '<td>' + $.number(element.quantity, 0, ',', '.') + '</td>' +
                                '<td>' + element.stage_name + '</td>' +
                                '<td></td>' +
                                '<td><i class="fa fa-info-circle open-modal" data-toggle="modal" data-target="#myModal" data-stage="' + element.stage_name + '" data-quantity="' + element.quantity + '"></i></td>' +
                                '<input type="hidden" name="detail_id[]" value="' + element.id + '">' +
                                '<input type="hidden" name="detail_product_id[]" value="' + element.product_id + '">' +
                                '<input type="hidden" name="detail_product_name[]" value="' + element.product_name + '">' +
                            '</tr>');
                            conteo++;
                            $('#branch_id').val(element.branch_id);
                            $('#branch').val(element.branch);
                            $('#date_ped').val(element.date);
                            $('#client_id').val(element.client_id);
                            $('#client').val(element.client);
                        });
                        if(conteo>0)
                        {
                            
                            $("#div_details, #div_footer").show();
                            $("#number_ped").prop("readonly", true);
                            $("#button_search").hide();
                            $("[select2]").select2({
                                language: 'es'
                            });
                        }else
                        {
                            swal({
                                title: "SISTEMA",
                                text: "No existe Pedido!!",
                                icon: "info",
                                button: "OK",
                            });
                            return false;
                        }
                    },
                    error: function(data){
                        laravelErrorMessages(data);
                    }
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ajax.control-production') }}',
                    type: "GET",
                    data: { number_order : number_order, sesion:2},
                    success: function(data) {
                        $.each(data.items, function(index, element) {
                            invoice_items_array.push(element.product_id);

                            $('#tbody_detail2').append(     
                            '<tr>' +
                                '<td>' + element.product_id + '</td>' +
                                '<td>' + element.product_name + '</td>' +
                                '<td>' + $.number(element.quantity, 0, ',', '.') + '</td>' +
                                '<td>' + element.stage_name + '</td>' +
                                '<td></td>' +

                                '<td><i class="fa fa-info-circle open-modal" data-toggle="modal" data-target="#myModal" data-stage="' + element.stage_name + '" data-quantity="' + element.quantity + '"></i></td>' +
                                '<input type="hidden" name="detail_id[]" value="' + element.id + '">' +
                                '<input type="hidden" name="detail_product_id[]" value="' + element.product_id + '">' +
                                '<input type="hidden" name="detail_product_name[]" value="' + element.product_name + '">' +
                            '</tr>');
                        });
                    },
                    error: function(data){
                        laravelErrorMessages(data);
                    }
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ajax.control-production') }}',
                    type: "GET",
                    data: { number_order : number_order, sesion:3},
                    success: function(data) {
                        $.each(data.items, function(index, element) {
                            invoice_items_array.push(element.product_id);
                            $('#tbody_detail3').append(     
                            '<tr>' +
                                '<td>' + element.product_id + '</td>' +
                                '<td>' + element.product_name + '</td>' +
                                '<td>' + $.number(element.quantity, 0, ',', '.') + '</td>' +
                                '<td>' + element.stage_name + '</td>' +
                                '<td></td>' +
                                '<td><i class="fa fa-info-circle open-modal" data-toggle="modal" data-target="#myModal" data-stage="' + element.stage_name + '" data-quantity="' + element.quantity + '"></i></td>' +
                                '<input type="hidden" name="detail_id[]" value="' + element.id + '">' +
                                '<input type="hidden" name="detail_product_id[]" value="' + element.product_id + '">' +
                                '<input type="hidden" name="detail_product_name[]" value="' + element.product_name + '">' +
                            '</tr>');
                        });
                    },
                    error: function(data){
                        laravelErrorMessages(data);
                    }
                });


            }
            else
            {
                swal({
                    title: "SISTEMA",
                    text: "Hay campos vacíos",
                    icon: "warning",
                    button: "OK",
                });
                return false;
            }
        }


    </script>
@endsection

