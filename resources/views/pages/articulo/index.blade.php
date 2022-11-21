@extends('layouts.AdminLTE.index')
@section('title', 'Articulo ')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-tools">
        <a href="{{ url('articulo/create') }}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar</a> 
      </div> 
        <table class="table table-striped table-condensed table-hover">
          <thead>
              <tr>
                <th>Nombre</th> 
                <th class="text-center">Codigo Barra</th> 
                <th class="text-center">Acciones</th> 
              </tr>
          </thead>
          <tbody>
            @foreach($articulos as $articulo)
                <tr>
                    <td>{{ $articulo->name }}</td>
                    <td class="text-center">{{ $articulo->barcode }}</td>
                    <td class="text-center">
                      <a href="{{ url('branches/' . $articulo->id . '/edit') }}"><i class="fa fa-pencil-alt"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
      </div>
  </div>
</div>                    
@endsection
