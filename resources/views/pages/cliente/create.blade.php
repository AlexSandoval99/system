@extends('layouts.AdminLTE.index')
@section('title', 'Agregar Cliente ')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            {{ Form::open(['route' => 'cliente.store']) }}
            <div class="ibox-content">
                    @include('partials.messages')
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Nombre</label><br>
                        <input id="name" class="form-control" name="name" type="text"  value="{{ old('first_name') }}">
                    </div>
                <div class="form-group col-md-4">
                    <label>Apellido</label><br>
                    <input id="apellido" class="form-control" name="apellido" type="text"  value="{{ old('last_name')}}">
                </div>
                    <div class="form-group col-md-4">
                        <label>Direccion</label><br>
                        <input id="address" class="form-control" name="address" type="text"  value="{{ old('address')}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>RUC</label><br>
                        <input id="ruc" class="form-control" name="ruc" type="text"  value="{{ old('ruc')}}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Telefono</label><br>
                        <input id="phone" class="form-control" name="phone" type="text"  value="{{ old('phone')}}">
                    </div>
                {{-- <div class="row">
                    <div class="form-group col-md-4">
                        <label>Nacionalidad</label>
                        {{ Form::select('nationalities_id', $nation ,request()->nationalities_id, ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder'  => 'Seleccione una nacionalidad']) }}
                    </div>
                </div> --}}
            </div>
        </div>
                <div class="ibox-footer">
                    <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                    <a href="{{ url('cliente') }}" class="btn btn-sm btn-danger">Cancelar</a>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
