@extends('layouts.AdminLTE.index')
@section('title', 'Timbrado')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="ibox-tools">
                <div class="btn-group pull-right">
                    <a href="{{ url('stampeds/create') }}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Número</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stampeds as $stamped)
                        <tr>
                            <td>{{$stamped->id}}</td>
                            <td>{{$stamped->number}}</td>
                            <td>{{$stamped->from_date->format('d/m/Y')}}</td>
                            <td>{{$stamped->until_date->format('d/m/Y')}}</td>
                            <td class="text-center"><span class="label label-{{ config('constants.status-label.' . $stamped->status) }}">{{ config('constants.status.' . $stamped->status) }}</td>
                            <td class="text-center">
                                <a href="{{ url('stampeds/' . $stamped->id . '/edit') }}"target="_blank" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                <a href="{{ url('stampeds/' . $stamped->id) }}"><i class="fa fa-info-circle"></i></a>
                        </tr>
                        @endforeach
                        @if($stampeds->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No hay timbrados registrados</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('layout_js')
@if (session('success'))
    <script>
        swal({
            title: "Guardado exitosamente",
            text: '{{session('success')}}',
            icon: "success",
            buttons: false,
            timer: 1500
        });
    </script>
@endif

@if (session('error'))
    <script>
        swal({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545', // rojo Bootstrap
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@endsection
