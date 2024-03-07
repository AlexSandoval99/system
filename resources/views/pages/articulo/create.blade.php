@extends('layouts.AdminLTE.index')
@section('title', 'AGREGAR ARTICULO ')
@section('content')
{{ Form::open(['route' => 'articulo.store']) }}
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                @include('partials.messages')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Nombre</label><br>
                        <input id="name" class="form-control" name="name" type="text" value="{{--{{ old('name', $articulo->name) }}--}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Cod Barra</label><br>
                        <input id="barcode" name="barcode" class="form-control" type="text" value="{{--{{ old('name', $articulo->name) }}--}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Precio</label><br>
                        <input id="price" name="price" class="form-control" type="text" value="{{--{{ old('name', $articulo->name) }}--}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Marca</label>
                        {{ Form::select('marca_id', $brand ,request()->marca_id, ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder'  => 'Seleccione un equipo']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h2>Configuraciones</h2>
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab1">Configuración de Materias</a></li>
                            <li><a data-toggle="tab" href="#tab2">Configuración de Control</a></li>
                            <li><a data-toggle="tab" href="#tab3">Configuración de Calidad</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab1" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="material">Material</label><br>
                                        {{ Form::select('material', $materials, null, ['class' => 'form-control', 'id' => 'material', 'placeholder' => 'Seleccione un material']) }}
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cantidad">&nbsp;</label><br>
                                        <input id="cantidad" name="cantidad" class="form-control" type="number" placeholder="Cantidad">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>&nbsp;</label><br>
                                        <button type="button" class="btn btn-primary" id="agregarMaterial"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @csrf
                                        <table class="table" id="tabla" style="display: none;">
                                            <thead>
                                                <tr>
                                                    <th>Material</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaMateriales">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>                                               
                            <div id="tab2" class="tab-pane fade">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="material">Etapa Produccion</label><br>
                                        {{ Form::select('stage', $stages, null, ['class' => 'form-control', 'id' => 'stage', 'placeholder' => 'Seleccione una Etapa']) }}
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>&nbsp;</label><br>
                                        <button type="button" class="btn btn-primary" id="AddStage"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @csrf
                                        <table class="table" id="tablaStage" style="display: none;">
                                            <thead>
                                                <tr>
                                                    <th>Etapa de Produccion</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaStage">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="tab3" class="tab-pane fade">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="ibox-footer">
            <input type="submit" class="btn btn-sm btn-success" value="Guardar">
            <a href="{{ url('articulo') }}" class="btn btn-sm btn-danger">Cancelar</a>
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection
@section('layout_js')
<script>
    var count = 0;
   $(document).ready(function(){
        $('#agregarMaterial').click(function(){
            var materialSeleccionado = $('#material').val();
            var materialSeleccionadoText = $("select[name='material'] option:selected").text();
            var cantidad = $('#cantidad').val();
            var fila = '<tr><td><input type="hidden" name="materiales[]" value="' + materialSeleccionado + '">' + materialSeleccionadoText + '</td><td><input type="hidden" name="cantidades[]" value="' + cantidad + '">' + cantidad + '</td><td><a href="javascript:;" onClick="removeRow(this, ' + count + ');"><i style="font-size:17px;" class="fa fa-times"></i></a></td></tr>';
            $('#tablaMateriales').append(fila);
            count++;
            console.log(count);
            if(count > 0)
            {
                $('#tabla').show();
            }
        });

        $('#AddStage').click(function(){
            var stage = $('#stage').val();
            var stageText = $("select[name='stage'] option:selected").text();
            var fila = '<tr><td><input type="hidden" name="stages[]" value="' + stage + '">' + stageText + '</td><td><a href="javascript:;" onClick="removeRow(this, ' + count + ');"><i style="font-size:17px;" class="fa fa-times"></i></a></td></tr>';
            $('#tablaStage').append(fila);
            count++;
            if(count > 0)
            {
                $('#tablaStage').show();
            }
        });

    });
    // Función para eliminar la fila al hacer clic en el icono de eliminar
    function removeRow(link, rowIndex) {
        $(link).closest('tr').remove();
        count--; // Decrementar el contador de filas
        if (count <= 0) {
            $('#tabla').hide(); // Ocultar la tabla si no hay filas
        }
        var mensaje = 'Se eliminó un registro';
        mostrarMensajeTemporal(mensaje, 3000); // 5000 milisegundos = 5 segundos
    }

    function mostrarMensajeTemporal(mensaje, duracion) {
        var mensajeElemento = $('<div class="mensaje-temporal">' + mensaje + '</div>');
        $('body').append(mensajeElemento);
        setTimeout(function() {
            mensajeElemento.fadeOut('slow', function() {
                $(this).remove();
            });
        }, duracion);
    }
 </script>
@endsection
@section('layout_css')
<style>
    .mensaje-temporal {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    z-index: 9999;
}
</style>
@endsection