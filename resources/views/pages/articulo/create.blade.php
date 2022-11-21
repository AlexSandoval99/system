@extends('layouts.AdminLTE.index')
@section('title', 'Articulo ')
@section('content')
  <div class="row">
      <div class="col-lg-12">
          <div class="ibox float-e-margins">
              <div class="ibox-title">
                  <h5>Agregar Articulo</h5>
              </div>
              {{ Form::open(['route' => 'articulo.store']) }}
                  <div class="ibox-content">
                      @include('partials.messages')
                      <div class="row">
                          <div class="form-group col-md-4">
                              <label>Nombre</label><br>
                              <input id="name" name="name" type="text"  value="{{--{{ old('name', $articulo->name) }}--}}"> 
                            </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-2">
                            <label>Cod Barra</label><br>
                            <input id="barcode" name="barcode" type="text"  value="{{--{{ old('name', $articulo->name) }}--}}"> 
                          </div>
                    </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                              <label>Marca</label>
                              {{ Form::select('marca_id', ['nose','ok'] ,request()->marca_id, ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder'  => 'Seleccione un equipo']) }}
                          </div>
                      </div>
                  </div>
                  <div class="ibox-footer">
                      <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                      <a href="{{ url('articulo') }}" class="btn btn-sm btn-danger">Cancelar</a>
                  </div>
                {{ Form::close() }}
          </div>
      </div>
  </div>
@endsection
