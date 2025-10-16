@extends('layouts.AdminLTE.index')
@section('title', 'Movimientos de Caja')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content pb-0">
                <div class="row">
                    <form id="filter-form">
                        <div class="form-group col-md-3">
                            <label for="cash_box_id">Caja</label>
                            {{ Form::select('cash_box_id', $cash_boxes, request()->cash_box_id, ['placeholder' => 'Seleccione Caja', 'class' => 'form-control select2', 'id' => 'cash_box_id']) }}
                        </div>

                        <div class="form-group col-md-3">
                            <label>Fecha</label>
                            <input type="text" name="date_range"  class="form-control date_range text-center"  placeholder="Rango de fecha" value="{{ request()->date_range }}" autocomplete="off" date-range-mask>
                        </div>

                        <div class="form-group col-md-3" style="margin-top: 2%">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </form>

                    <div class="col-md-12 mt-3">
                        <table class="table table-bordered" id="movements-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Usuario</th>
                                    <th>Caja</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="7" class="text-center">Seleccione una caja y un rango de fechas</td></tr>
                            </tbody>
                        </table>

                        <div id="totals" class="mt-4" style="display:none;">
                            <h5><strong>Totales</strong></h5>
                            <p>💰 Ingresos: <span id="total-ingresos" class="text-success font-weight-bold">0</span></p>
                            <p>💸 Egresos: <span id="total-egresos" class="text-danger font-weight-bold">0</span></p>
                            <p>🧾 Saldo final: <span id="saldo-final" class="font-weight-bold">0</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        // Inicializa el selector de rango de fechas
        $('.date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Limpiar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
                weekLabel: 'S',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            }
        });

        // Actualiza el campo al aplicar el rango
        $('.date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            $(this).data('from', picker.startDate.format('YYYY-MM-DD'));
            $(this).data('to', picker.endDate.format('YYYY-MM-DD'));
        });

        // Limpia el campo si se cancela
        $('.date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $(this).removeData('from').removeData('to');
        });

        // Envío AJAX
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();

            let cashBoxId = $('#cash_box_id').val();
            let from = $('.date_range').data('from');
            let to = $('.date_range').data('to');

            if (!cashBoxId || !from || !to) {
                toastr.warning('Debe seleccionar la caja y un rango de fechas válido');
                return;
            }

            $.ajax({
                url: "{{ route('cashbox.movements.filter') }}",
                method: "GET",
                data: {
                    cash_box_id: cashBoxId,
                    from: from,
                    to: to
                },
                dataType: "json",
                beforeSend: function() {
                    $('#movements-table tbody').html('<tr><td colspan="7" class="text-center text-info">Cargando datos...</td></tr>');
                },
                success: function(data) {
                    let tbody = $('#movements-table tbody');
                    tbody.empty();

                    if (data.movements.length === 0) {
                        tbody.html('<tr><td colspan="7" class="text-center">No hay movimientos para este rango</td></tr>');
                        $('#totals').hide();
                        return;
                    }

                    $.each(data.movements, function (i, m) {
                        let tipo = m.type == 1 ? 'Ingreso' : 'Egreso';
                        let color = m.type == 1 ? 'label-success' : 'label-danger';
                        tbody.append(`
                            <tr>
                                <td>${new Date(m.created_at).toLocaleDateString()}</td>
                                <td>${m.cash_box_concept ? m.cash_box_concept.name : ''}</td>
                                <td><span class="label ${color}">${tipo}</span></td>
                                <td class="text-right">${parseFloat(m.amount).toLocaleString()}</td>
                                <td>${m.user ? m.user.name : ''}</td>
                                <td>${m.cash_box ? m.cash_box.name : ''}</td>
                                <td>${m.observation ?? ''}</td>
                            </tr>
                        `);
                    });

                    // Mostrar totales
                    $('#totals').show();
                    $('#total-ingresos').text(data.totals.ingresos.toLocaleString());
                    $('#total-egresos').text(data.totals.egresos.toLocaleString());
                    $('#saldo-final').text(data.totals.saldo.toLocaleString());
                },
                error: function(xhr) {
                    console.error(xhr);
                    toastr.error('Error al cargar los movimientos');
                }
            });
        });
    });
</script>
@endsection
