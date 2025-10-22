@extends('layouts.AdminLTE.index')

@section('title', 'Cuotas de Compras')
@section('icon_page', 'money')

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Listado de Cuotas de Compras</h3>
    </div>

    <div class="box-body">
        <form method="GET" action="{{ route('purchases_collect') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="provider_id">Proveedor:</label>
                        <select name="provider_id" id="provider_id" class="form-control select2" data-placeholder="Seleccione un proveedor">
                            <option value="">-- Todos --</option>
                            @foreach($providers as $id => $name)
                                <option value="{{ $id }}" {{ request('provider_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 text-left">
                    <button type="submit" class="btn btn-primary" style="margin-top: 6%">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    @if(request()->all())
                        <a href="{{ route('purchases_collect') }}" class="btn btn-default" style="margin-top: 6%">
                            <i class="fa fa-refresh"></i> Limpiar
                        </a>
                    @endif
                </div>
            </div>
            <hr>
        </form>


        <!-- 🧾 Tabla -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Compra Nº</th>
                        <th>Proveedor</th>
                        <th>Cuota</th>
                        <th>Vencimiento</th>
                        <th>Monto</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collects as $collect)
                        <tr>
                            <td>{{ $collect->id }}</td>
                            <td>{{ $collect->purchase->number ?? '-' }}</td>
                            <td>{{ $collect->purchase->provider->name ?? '-' }}</td>
                            <td>{{ $collect->number }}</td>
                            <td>{{ \Carbon\Carbon::parse($collect->expiration)->format('d/m/Y') }}</td>
                            <td class="text-right">{{ number_format($collect->amount, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($collect->residue, 0, ',', '.') }}</td>
                            <td class="text-center"><span class="label label-{{ config('constants.collect_status_label.' . $collect->status) }}">{{ config('constants.collect_status.' . $collect->status) }}</span></td>

                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No hay registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            {{ $collects->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('layout_js')
<script>
$(function(){
    $('.select2').select2({
        language: {
            noResults: function() {
                return "No se encontraron resultados";
            }
        }
    });
});
</script>
@endsection
