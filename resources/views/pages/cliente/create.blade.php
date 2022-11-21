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
                        <input id="name" name="name" type="text"  value="{{ old('name') }}"> 
                    </div>
                </div>
                <div class="row">
                <div class="form-group col-md-2">
                    <label>Apellido</label><br>
                    <input id="apellido" name="apellido" type="text"  value="{{ old('apellido')}}"> 
                </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label>CI</label><br>
                        <input id="ci" name="ci" type="text"  value="{{ old('ci')}}"> 
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label>Telefono</label><br>
                        <input id="phone" name="phone" type="text"  value="{{ old('phone')}}"> 
                    </div>
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