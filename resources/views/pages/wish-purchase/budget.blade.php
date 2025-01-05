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
                            {{ Form::select('provider_id', $provider, request()->provider_id, ['placeholder' => 'Seleccione Proveedor', 'class' => 'form-control', 'select2']) }}
                            {{-- <input class="form-control" name="name" type="text" value=""> --}}
                        </div>
                        <div class="input-group-append col-md-1" style="margin-top:27px; margin-left:-20px;">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProviderModal"><i class="fa fa-plus"></i></button>
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
<div class="modal fade" id="addProviderModal" tabindex="-1" role="dialog" aria-labelledby="addProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProviderModalLabel">Agregar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProviderForm">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="provider_name">Nombre</label>
                            <input type="text" class="form-control" id="provider_name" name="name" required autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="provider_ruc">RUC</label>
                            <input type="text" class="form-control" id="provider_ruc" name="ruc" required autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="provider_name">Direccion</label>
                            <input type="text" class="form-control" id="provider_address" name="address" required autocomplete="off">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="provider_name">Telefono</label>
                            <input type="text" class="form-control" id="provider_phone" name="phone" required autocomplete="off">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
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

        $(document).on('submit', '#addProviderForm', function (e) {
            e.preventDefault();
            const form = $(this);
            const data = form.serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('provider.store') }}",
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        const provider = response.provider;
                        const newOption = new Option(provider.name, provider.id, false, true);
                        $('select[name="provider_id"]').append(newOption).trigger('change');

                        $('#addProviderModal').modal('hide');
                        form[0].reset();

                        swal("Éxito", response.message, "success");
                    } else {
                        swal("Error", response.message, "error");
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = "Se encontraron los siguientes errores:\n";

                        for (let field in errors) {
                            errorMessage += `- ${errors[field][0]}\n`;
                        }

                        swal("Error de validación", errorMessage, "error");
                    } else {
                        swal("Error", "Ocurrió un error inesperado.", "error");
                    }
                }
            });
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
