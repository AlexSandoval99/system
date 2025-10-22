@extends('layouts.AdminLTE.index')
@section('title', 'Reportes de Compras')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Reportes de Compras</h5>
    </div>
    <div class="card-body">
        <div class="row" style="margin-bottom: 3%">
            <div class="col-md-6">
                <label>Seleccione un reporte</label>
                <select id="submodulo" class="form-control">
                    <option value="">Seleccione...</option>
                    @foreach($subModulos as $key => $nombre)
                        <option value="{{ $key }}">{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Contenedor de formularios -->
        <div id="formularios">
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <button id="btnExcel" class="btn btn-success me-2" disabled>
                        <i class="fa fa-file-excel"></i> Exportar Excel
                    </button>
                    <button id="btnPdf" class="btn btn-danger" disabled>
                        <i class="fa fa-file-pdf"></i> Exportar PDF
                    </button>
                </div>
            </div>
            <!-- 🟢 Pedido -->
            <div id="form-pedido" class="formulario" style="display:none;">
                <form method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Buscar</label>
                            <input type="text" name="buscar" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                        <div class="col-md-3" style="margin-top: 2%">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nro°</th>
                                <th>SU</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-pedido"></tbody>
                    </table>
                </form>
            </div>
            <div id="form-orden" class="formulario" style="display:none;">
                <form method="GET" action="{{ route('wish-purchases.show_multiple') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Buscar</label>
                            <input type="text" name="buscar" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Proveedor</label>
                            <select name="proveedor_id" class="form-control">
                                <option value="">Seleccione</option>
                                {{-- cargar proveedores --}}
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nro°</th>
                                <th>Proveedor</th>
                                <th>Sucursal</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-orden"></tbody>
                    </table>
                </form>
            </div>

            <!-- 🟢 Factura -->
            <div id="form-factura" class="formulario" style="display:none;">
                <form method="GET" action="{{ route('wish-purchases.show_multiple') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nro Factura</label>
                            <input type="text" name="nro_factura" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Tipo Documento</label>
                            <select class="form-control" name="type" id="type">
                                <option value="1">Factura</option>
                                <option value="2">Nota Credito</option>
                                <option value="3">Recibo</option>
                            </select>
                        </div>
                        <div class="col-md-2" style="margin-top: 2%">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Sucursal</th>
                                <th>Fecha</th>
                                <th>Condición</th>
                                <th>Tipo</th>
                                <th>Numero</th>
                                <th>Ruc</th>
                                <th>Proveedor</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-factura"></tbody>
                    </table>
                </form>
            </div>

            <!-- 🟢 Inventario -->
            <div id="form-inventario" class="formulario" style="display:none;">
                <form method="GET" action="{{ route('wish-purchases.show_multiple') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Artículo</label>
                            <input type="text" name="articulo" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Sucursal</label>
                            <select name="sucursal_id" class="form-control">
                                <option value="">Seleccione</option>
                                {{-- cargar sucursales --}}
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nro°</th>
                                <th>SU</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-inventario"></tbody>
                    </table>
                </form>
            </div>

            <!-- 🟢 Existencia -->
            <div id="form-existencia" class="formulario" style="display:none;">
                <form method="GET" action="{{ route('wish-purchases.show_multiple') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Depósito</label>
                            <select name="deposito_id" class="form-control">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Artículo</label>
                            <input type="text" name="articulo" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nro°</th>
                                <th>SU</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-existencia"></tbody>
                    </table>
                </form>
            </div>

            <!-- 🟢 Libro -->
            <div id="form-libro" class="formulario" style="display:none;">
                <form method="GET" action="{{ route('wish-purchases.show_multiple') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nro°</th>
                                <th>SU</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-libro"></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('layout_js')
<script>
$(document).ready(function(){
    // Muestra/Oculta formularios
    $('#submodulo').on('change', function(){
        let selected = $(this).val();
        $('.formulario').hide();
        if(selected) {
            $('#form-' + selected).show();
        }
    });

    // Captura envío de todos los formularios
    $('form').on('submit', function(e){
        e.preventDefault();

        let form = $(this);
        let tipo = $('#submodulo').val();
        let tbody_id = '#tbody-' + tipo;

        // Serializa los campos + agrega tipo
        let datos = form.serialize() + '&tipo=' + tipo;

        $.ajax({
            url: '{{ route("reportes.ajax-compras") }}',
            type: 'GET',
            data: datos,
            beforeSend: function(){
                $(tbody_id).html('<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>');
            },
            success: function(resp){
                $(tbody_id).html(resp.tbody);
            },
            error:function(){
                swal({
                    title: "SISTEMA",
                    text: "Contacte con el administrador",
                    icon: "error",
                    button: "OK",
                });
            }
        });
    });

    // ✅ Activar botones solo cuando haya un submódulo seleccionado
    $('#submodulo').on('change', function(){
        let selected = $(this).val();
        $('#btnExcel, #btnPdf').prop('disabled', !selected);
    });

    // ✅ Función para exportar (Excel o PDF)
    function exportar(tipoArchivo) {
        let tipo = $('#submodulo').val();
        if (!tipo) {
            swal("SISTEMA", "Seleccione un tipo de reporte primero", "warning");
            return;
        }

        // Toma los filtros del formulario activo
        let form = $('#form-' + tipo + ' form');
        let datos = form.serialize() + '&tipo=' + tipo + '&export=' + tipoArchivo;

        // Redirige (descarga directa)
        let url = '{{ route("reportes.exportar") }}' + '?' + datos;
        window.open(url, '_blank');
    }

    // Botones de exportación
    $('#btnExcel').on('click', function() { exportar('excel'); });
    $('#btnPdf').on('click', function() { exportar('pdf'); });

});
</script>
@endsection
