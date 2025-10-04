@extends('layouts.AdminLTE.index')
@section('title', 'Reportes de Producción')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Reportes de Producción</h5>
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

        <div id="filtrosContainer"></div>
    </div>
</div>
@endsection

@section('layout_js')
<script>
    $(document).ready(function(){
        $('#submodulo').on('change', function(){
            let sub = $(this).val();
            if(sub){
                $.get(`/reportes/filtros/produccion/${sub}`, function(html){
                    $('#filtrosContainer').html(html);
                });
            } else {
                $('#filtrosContainer').html('');
            }
        });
    });
</script>
@endsection
