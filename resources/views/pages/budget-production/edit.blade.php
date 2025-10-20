@extends('layouts.AdminLTE.index')
@section('title', 'Editar Presupuesto de Produccion')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            {{ Form::open(['route' => ['budget-production.update'], 'method' => 'PUT']) }}
            <div class="ibox-content">
                @include('partials.messages')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Cliente</label>
                        <input type="hidden" name="id_presupuesto" value="{{$budget_production->id}}">
                        <input type="text" name="client" value="{{$budget_production->client->fullname}}" id="client" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Sucursal</label>
                        <input type="text" name="branch" value="{{$budget_production->branch->name}}" id="branch" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Fecha Pedido</label>
                        <input class="form-control" type="text" name="date_ped" value="{{$budget_production->date->format('d/m/Y')}}" readonly>
                        <input class="form-control" type="hidden" name="total_amount" id="total" readonly>
                    </div>
                </div>
                <div class="ibox-content table-responsive no-padding" id="detail_product">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="text-right">Cód</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Precio</th>
                                <th class="text-right">SubTotal</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_detail"></tbody>
                    </table>
                </div>
            </div>
            <div class="ibox-footer">
                <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                <a href="{{ url('budget-production') }}" class="btn btn-sm btn-danger">Cancelar</a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
@section('layout_js')
    <script>
        var invoice_items_array = [];
        $(document).ready(function ()
        {
            @foreach($budget_production->budget_production_details as $budget)

            addToTable(
                        {{$budget->articulo_id}},
                        '{{config('constants.description.' . $budget->description)}}',
                        {{$budget->quantity}},
                        '{{$budget->description}}',
                        '{{$budget->articulo->name}}',
                        {{$budget->articulo->price}});
            @endforeach
        });

        function addToTable(id, name, quantity, description,product_name,price)
        {
            invoice_items_array.push(id);
            let subtotal = price * quantity;
            $('#tbody_detail').append('<tr>' +
                    '<td class="text-right">' + id +' <input type="hidden" name="detail_product_id[]" value="' + id + '"></td>' +
                    '<td>' + product_name + '<input type="hidden" name="detail_product_name[]" value="' + product_name + '"></td>' +
                    '<td class="text-center"> <input type="text" name="quantity_product[]" value="' + $.number(quantity, 0, ',', '.') + '" onkeyup="updateSubtotal($(this))"></td>' +
                    '<td class="text-center"><input style="width:150px;" type="text" name="detail_product_amount[]" onchange="addToTable($(this))" value="' + $.number(price, 0, ',', '.')  + '" onkeyup="updateSubtotal($(this))" autocomplete="off"></td>'+
                    '<td class="text-right subtotal">'  + $.number(subtotal, 0, ',', '.') + '</td>'+
                    '<td class="text-right"><a href="javascript:;" onClick="removeRow(this, '+ id +');"><i style="font-size:17px;" class="fa fa-times"></i></a></td>' +
                '</tr>');
                calculateTotal();
        }

        function removeRow(t, product_id)
        {
            $(t).parent().parent().remove();
            invoice_items_array.splice($.inArray(product_id, invoice_items_array), 1 );
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

        function updateSubtotal(input)
        {
            var quantity = parseFloat(input.closest('tr').find('input[name="quantity_product[]"]').val().replace(/\./g, '').replace(',', '.')) || 0;
            var amount = parseFloat(input.closest('tr').find('input[name="detail_product_amount[]"]').val().replace(/\./g, '').replace(',', '.')) || 0;
            var subtotal = quantity * amount;
            input.closest('tr').find('.subtotal').text($.number(subtotal, 0, ',', '.'));
            calculateTotal();
        }
        function calculateTotal() {
            var total = 0;
            $('.subtotal').each(function() {
                var subtotalText = $(this).text().replace(/\./g, '').replace(',', '.').replace(/[^\d.-]/g, '');
                var subtotal = parseFloat(subtotalText) || 0;
                total += subtotal;
            });
            console.log(total);
            $('#total').val($.number(total, 0, ',', '.'));
        }

    </script>
@endsection

