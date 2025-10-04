@extends('layouts.AdminLTE.index')
@section('title', 'Reportes de Compras')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Reportes de Compras</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
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

        <!-- contenedor dinámico -->
        <div id="filtrosContainer"></div>
    </div>
</div>
@endsection

@section('layout_js')
<script>
    $(document).ready(function(){
        $('#submodulo').on('change', function(){
            let sub = $(this).val();
            let html = '';

            switch(sub){
                case 'pedido':
                    html = `
                        <form method="POST" action="{{ route('reportes.compras.excel','pedido') }}">
                            @csrf
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
                                <div class="col-md-1 d-flex align-items-end" style="margin-top: 1%">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-md-1 d-flex" style="margin-top: 1%">
                                    <button class="btn btn-success">
                                        <i class="fa fa-file-excel"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;

                case 'orden':
                    html = `
                        <form method="POST" action="{{ route('reportes.compras.excel','orden') }}">
                            @csrf
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
                                        {{-- aquí cargas proveedores --}}
                                    </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-file-excel"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;

                case 'factura':
                    html = `
                        <form method="POST" action="{{ route('reportes.compras.excel','factura') }}">
                            @csrf
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
                                <div class="col-md-2 mt-2">
                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-file-excel"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;

                case 'inventario':
                    html = `
                        <form method="POST" action="{{ route('reportes.compras.excel','inventario') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Artículo</label>
                                    <input type="text" name="articulo" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Sucursal</label>
                                    <select name="sucursal_id" class="form-control">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-file-excel"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;

                case 'existencia':
                    html = `
                        <form method="POST" action="{{ route('reportes.compras.excel','existencia') }}">
                            @csrf
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
                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-file-excel"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;

                case 'libro':
                    html = `
                        <form method="POST" action="{{ route('reportes.compras.excel','libro') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-success w-100">
                                        <i class="fa fa-file-excel"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;
            }

            $('#filtrosContainer').html(html);
        });
    });
</script>
@endsection
