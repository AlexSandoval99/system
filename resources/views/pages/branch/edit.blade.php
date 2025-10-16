@extends('layouts.AdminLTE.index')
@section('title', 'Editar Sucursal')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            {{ Form::open(['route' => ['branch.update', $branches->id], 'method' => 'PUT']) }}
            <div class="ibox-content">
                @include('partials.messages')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Nombre</label><br>
                        <input id="name" class="form-control" name="name" type="text"  value="{{ old('name', $branches->name) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-footer">
                <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                <a href="{{ url('branch') }}" class="btn btn-sm btn-danger">Cancelar</a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
