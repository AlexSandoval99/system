@extends('layouts.AdminLTE.index')
@section('title', 'Editar Materia Prima')
@section('content')
{{ Form::open(['route' => ['raw-materials.update', $materials->id], 'method' => 'PUT']) }}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    @include('partials.messages')
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Nombre</label><br>
                            <input class="form-control" name="description" type="text"  value="{{ old('description', $materials->description) }}">
                        </div>
                            <div class="form-group col-md-6">
                                <label>Estado</label>
                                {{ Form::select('status', config('constants.status') ,old('brand_id',$materials->status), ['class' => 'form-control selectpicker']) }}
                            </div>
                    </div>
                </div>
            </div>
            <div class="ibox-footer">
                <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                <a href="{{ url('materials') }}" class="btn btn-sm btn-danger">Cancelar</a>
            </div>
        </div>
    </div>
{{ Form::close() }}
@endsection
