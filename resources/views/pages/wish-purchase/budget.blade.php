@extends('layouts.AdminLTE.index')
@section('title', 'Ingreso de Presupuesto')
@section('content')
{{ Form::open(['id' => 'form', 'url' => route('budget.store', ['id' => $wishPurchase->id, 'token' => $wishPurchase->token])]) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Ingresar Presupuesto</h5>
                </div>
                <div class="ibox-content pb-0">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Pedido Número</label>
                            <input class="form-control" name="wish_number" type="text" value="{{ $wishPurchase->number }}" readonly>
                            <input class="form-control" name="wish_id" type="hidden" value="{{ $wishPurchase->id }}" >
                        </div>
                        <div class="form-group col-md-4">
                            <label>Fecha del Pedido</label>
                            <input class="form-control" type="text" value="{{ $wishPurchase->date }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sucursal</label>
                            <input class="form-control" type="text" value="{{ $wishPurchase->branch->name }}" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Proveedor</label>
                            <input class="form-control" name="name" type="text" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label>RUC</label>
                            <input class="form-control" name="ruc" type="text" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h3>Detalle del Pedido</h3>
        </div>
        <div class="ibox-content table-responsive no-padding">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-right">Cód</th>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio Unitario</th>
                        <th class="text-right">Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wishPurchase->wish_purchase_details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-right">{{ $detail->material_id }}</td>
                            <td>{{ $detail->description }}</td>
                            <td>{{ $detail->presentation }}</td>
                            <td class="text-right" >{{ $detail->quantity }}</td>
                            <input type="hidden" name="quantities[{{ $detail->material_id }}]" value="{{ $detail->quantity }}">
                            <td class="text-right">
                                <input type="number" step="0.01" class="form-control price-input" name="prices[{{ $detail->material_id }}]" placeholder="Ingrese precio" >
                            </td>
                            <td class="text-right" id="total_price_{{ $detail->material_id }}">0.00</td>
                            <input type="hidden" name="total_prices[{{ $detail->material_id }}]" id="total_price_input_{{ $detail->material_id }}" value="0.00">

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="ibox-footer">
        <input type="submit" class="btn btn-sm btn-success" value="Enviar Presupuesto">
        <a href="{{ url('/') }}" class="btn btn-sm btn-danger">Cancelar</a>
    </div>
{{ Form::close() }}
@endsection

@section('layout_js')
    <script>
        // Calcula el precio total dinámicamente al ingresar precios unitarios
        $(document).on('input', '.price-input', function () {
            const row = $(this).closest('tr');
            const quantity = parseFloat(row.find('td:nth-child(5)').text());
            const price = parseFloat($(this).val());
            const total = isNaN(price) || isNaN(quantity) ? 0 : quantity * price;

            // Formatea el total para mostrarlo en la tabla
            const totalFormatted = $.number(total, 2, ',', '.');
            const materialId = $(this).attr('name').match(/\[(\d+)\]/)[1]; // Extrae el material_id

            // Actualiza el valor visible en el <td>
            row.find(`#total_price_${materialId}`).text(totalFormatted);

            // Actualiza el valor en el campo oculto <input type="hidden">
            row.find(`#total_price_input_${materialId}`).val(total.toFixed(2));
        });

        // Envía el formulario vía AJAX
        $('#form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    swal({
                        title: "¡Éxito!",
                        text: "Presupuesto enviado correctamente.",
                        icon: "success",
                        button: "OK",
                    }).then(() => {
                        window.location.href = "{{ url('/') }}";
                    });
                },
                error: function(data) {
                    swal({
                        title: "Error",
                        text: "Hubo un problema al enviar el presupuesto. Por favor, intente nuevamente.",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });
    </script>
@endsection
