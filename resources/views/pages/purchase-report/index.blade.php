@extends('layouts.AdminLTE.index')

@section('title', 'Reporte de Compras')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Reporte de Compras</h5>
                <div class="justify-content-end align-items-right">
                    {{-- 🔹 Botón de descarga Excel siempre visible --}}
                    <a href="{{ route('purchase-report.excel', request()->all()) }}" class="btn btn-success">
                        <i class="fa fa-file-excel"></i> Descargar
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- 🔹 Select de proceso --}}
                    <div class="col-md-3">
                        <label>Proceso</label>
                        <select name="proceso" id="proceso" class="form-control">
                            <option value="">Seleccione un proceso</option>
                            <option value="1" {{ request('proceso') == '1' ? 'selected' : '' }}>Pedido de Compra</option>
                            <option value="2" {{ request('proceso') == '2' ? 'selected' : '' }}>Presupuesto</option>
                            <option value="3" {{ request('proceso') == '3' ? 'selected' : '' }}>Orden de Compra</option>
                            <option value="4" {{ request('proceso') == '4' ? 'selected' : '' }}>Factura de Compra</option>
                            <option value="5" {{ request('proceso') == '5' ? 'selected' : '' }}>Inventario</option>
                        </select>
                    </div>

                    {{-- 🔹 Filtros FACTURA (proceso = 4) --}}
                    <div id="factura-section" style="display: none;">
                        <form action="{{ route('purchase-report.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-3 align-items-end">
                            <input type="hidden" name="proceso" value="4">

                            <div class="col-md-3">
                                <label>Proveedor</label>
                                <select name="provider_id" id="purchases_provider_id" class="form-control">
                                    <option value="">Seleccione un proveedor</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Rango de Fecha</label>
                                <input type="text" name="date_range" class="form-control date_range text-center"
                                    placeholder="Rango de fecha" value="{{ request()->date_range }}" autocomplete="off">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-search"></i>
                                </button>
                                <a href="{{ route('purchase-report.index', ['proceso' => 4]) }}" class="btn btn-danger me-2">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- 🔹 Filtros PEDIDO (proceso = 1) --}}
                    <div id="pedido-section" style="display: none;">
                        <form action="{{ route('purchase-report.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-3 align-items-end">
                            <input type="hidden" name="proceso" value="1">

                            <div class="col-md-3">
                                <label>Rango de Fecha</label>
                                <input type="text" name="date_range" class="form-control date_range text-center"
                                    placeholder="Rango de fecha" value="{{ request()->date_range }}" autocomplete="off">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-search"></i>
                                </button>
                                <a href="{{ route('purchase-report.index', ['proceso' => 1]) }}" class="btn btn-danger me-2">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- 🔹 Filtros ORDEN (proceso = 3) --}}
                    <div id="orden-section" style="display: none;">
                        <form action="{{ route('purchase-report.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-3 align-items-end">
                            <input type="hidden" name="proceso" value="3">

                            <div class="col-md-3">
                                <label>Proveedor</label>
                                <select name="provider_id" id="orders_provider_id" class="form-control">
                                    <option value="">Seleccione un proveedor</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Rango de Fecha</label>
                                <input type="text" name="date_range" class="form-control date_range text-center"
                                    placeholder="Rango de fecha" value="{{ request()->date_range }}" autocomplete="off">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-search"></i>
                                </button>
                                <a href="{{ route('purchase-report.index', ['proceso' => 3]) }}" class="btn btn-danger me-2">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- 🔹 Tabla de resultados --}}
@if(isset($purchases) && $purchases->count())
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                @if(request('proceso') == 1)
                    <th>ID</th><th>Sucursal</th><th>Fecha</th><th>Estado</th>
                @endif

                @if(request('proceso') == 3)
                    <th>Nro°</th><th>Proveedor</th><th>Sucursal</th><th>Fecha</th><th>Estado</th>
                @endif

                @if(request('proceso') == 4)
                    <th>Sucursal</th><th>Fecha</th><th>Condición</th><th>Tipo</th>
                    <th>Número</th><th>RUC</th><th>Proveedor</th><th>Monto</th><th>Estado</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
                <tr>
                    @if(request('proceso') == 1)
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->branch->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                        <td><span class="label label-{{ config('constants.purchase-status-label.' . $purchase->status) }}">{{ config('constants.purchase-status.' . $purchase->status) }}</span></td>
                    @endif

                    @if(request('proceso') == 3)
                        <td>{{ $purchase->number }}</td>
                        <td>{{ $purchase->provider->name ?? '-' }}</td>
                        <td>{{ $purchase->branch->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                        <td><span class="label label-{{ config('constants.purchase-status-label.' . $purchase->status) }}">{{ config('constants.purchase-status.' . $purchase->status) }}</span></td>
                    @endif

                    @if(request('proceso') == 4)
                        <td>{{ $purchase->branch->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                        <td>{{ config('constants.invoice_condition.'. $purchase->condition) }}</td>
                        <td><span class="label label-{{ config('constants.type_purchases_label.' . $purchase->type) }}">{{ config('constants.type_purchases.'. $purchase->type) }}</span></td>
                        <td>{{ $purchase->number }}</td>
                        <td>{{ $purchase->ruc }}</td>
                        <td>{{ $purchase->provider->name ?? '-' }}</td>
                        <td class="text-right">{{ number_format($purchase->amount, 2, ',', '.') }}</td>
                        <td><span class="label label-{{ config('constants.purchase-status-label.' . $purchase->status) }}">{{ config('constants.purchase-status.' . $purchase->status) }}</span></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">{{ $purchases->appends(request()->except('page'))->links() }}</div>
@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('layout_js')
<script>
$(document).ready(function() {
    $('.date_range').daterangepicker({ autoUpdateInput: false, locale: { cancelLabel: 'Clear' }});
    $('.date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    function toggleFilters() {
        const proceso = $("#proceso").val();
        $("#factura-section").toggle(proceso === "4");
        $("#pedido-section").toggle(proceso === "1");
        $("#orden-section").toggle(proceso === "3");
    }
    toggleFilters();

    $("#proceso").change(function() {
        toggleFilters();
        const proceso = $(this).val();
        if (proceso) window.location.href = "{{ route('purchase-report.index') }}?proceso=" + proceso;
    });

    function initProviderSelect(selector, selectedProvider) {
        let $providerSelect = $(selector);
        if ($providerSelect.length) {
            $providerSelect.select2({
                language: 'es', placeholder: 'Seleccione un proveedor',
                allowClear: true, minimumInputLength: 2,
                ajax: { url: '{{ url('ajax/purchases_providers') }}', dataType: 'json', delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({ results: data.items })
                },
                templateResult: repo => repo.loading ? repo.text : repo.name + ' | ' + repo.ruc,
                templateSelection: repo => repo.name ? repo.name + ' | ' + repo.ruc : repo.text
            });
            if (selectedProvider) {
                let opt = new Option(selectedProvider.name + " | " + selectedProvider.ruc, selectedProvider.id, true, true);
                $providerSelect.append(opt).trigger('change');
            }
        }
    }

    initProviderSelect("#purchases_provider_id", @json(isset($selectedProvider) && request('proceso') == 4 ? $selectedProvider : null));
    initProviderSelect("#orders_provider_id", @json(isset($selectedProvider) && request('proceso') == 3 ? $selectedProvider : null));
});
</script>
@endsection











