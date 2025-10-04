@extends('layouts.AdminLTE.index')
@section('title', 'Reporte de Ventas')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Reporte de Ventas</h5>
            </div>
            <div class="card-body">

                <!-- Formulario de filtros -->
                <form action="{{ route('sales-report.index') }}" method="GET" class="mb-3">
                    <div class="row g-2">

                        <!-- Select de cliente -->
                        <div class="col-md-4">
                            <label>Cliente</label>
                            <select class="form-control" name="client_id" id="client_id"></select>
                        </div>

                        <!-- Periodo -->
                        <div class="col-md-4">
                            <label>Periodo</label>
                            <select name="periodo" id="periodo" class="form-control">
                                <option value="">Todos los periodos</option>
                                <option value="diario" {{ request('periodo') == 'diario' ? 'selected' : '' }}>Diario</option>
                                <option value="mensual" {{ request('periodo') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                <option value="anual" {{ request('periodo') == 'anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>

                        <!-- Fechas Diario -->
                        <div class="col-md-8" id="diario-container" style="display: none;">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label>Desde</label>
                                    <input type="text" name="fecha_desde" id="fecha_desde" class="form-control" autocomplete="off" value="{{ request('fecha_desde') }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Hasta</label>
                                    <input type="text" name="fecha_hasta" id="fecha_hasta" class="form-control" autocomplete="off" value="{{ request('fecha_hasta') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Mes Mensual -->
                        <div class="col-md-4" id="mes-container" style="display: none;">
                            <label>Mes</label>
                            <select name="mes" id="mes" class="form-control">
                                @php
                                    $meses = [
                                        1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                                        7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
                                    ];
                                @endphp
                                @foreach($meses as $num=>$nombre)
                                    <option value="{{ $num }}" {{ request('mes') == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Año Mensual y Anual -->
                        <div class="col-md-4" id="anio-container" style="display: none;">
                            <label>Año</label>
                            <select name="anio" id="anio" class="form-control">
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for($i = $currentYear; $i >= $currentYear-10; $i--)
                                    <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="col-12 d-flex mt-2">
                            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                            <a href="{{ route('sales-report.index') }}" class="btn btn-secondary">Limpiar</a>
                        </div>

                    </div>
                </form>

                <!-- Tabla de resultados -->
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>RUC</th>
                            <th>Fecha</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ optional($sale->client)->razon_social ?? 'Sin cliente' }}</td>
                                <td>{{ optional($sale->client)->ruc ?? '-' }}</td>
                                <td>{{ $sale->date }}</td>
                                <td>{{ $sale->total ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron ventas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-3">
                    {{ $sales->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('layout_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"/>

<script>
$(document).ready(function() {

    // --- Select2 Cliente ---
    $("#client_id").select2({
        language: 'es',
        placeholder: 'Seleccione un cliente',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ url("ajax/clients") }}',
            dataType: 'json',
            delay: 250,
            method: 'GET',
            data: function(params) { return { q: params.term }; },
            processResults: function(data) {
                return {
                    results: data.items.map(function(client) {
                        return { id: client.id, text: client.name + ' | ' + client.ruc };
                    })
                };
            }
        },
        templateResult: function(client) { return client.text || client.id; },
        templateSelection: function(client) { return client.text || client.id; }
    });

    // --- Mostrar campos según periodo ---
    function togglePeriodos() {
        let periodo = $('#periodo').val();
        $('#diario-container, #mes-container, #anio-container').hide();
        $('#fecha_desde, #fecha_hasta, #mes, #anio').val('');

        if(periodo === 'diario') $('#diario-container').show();
        if(periodo === 'mensual') { $('#mes-container').show(); $('#anio-container').show(); }
        if(periodo === 'anual') $('#anio-container').show();
    }
    $('#periodo').change(togglePeriodos);
    togglePeriodos(); // Inicial al cargar

    // --- Datepicker Diario ---
    $('#fecha_desde, #fecha_hasta').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

});
</script>
@endsection




