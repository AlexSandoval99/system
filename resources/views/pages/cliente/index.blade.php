@extends('layouts.AdminLTE.index')
@section('title', 'Cliente ')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-tools">
        <a href="{{ url('cliente/create') }}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar</a> 
      </div> 
        <table class="table table-striped table-condensed table-hover">
          <thead>
              <tr>
                <th>Nombre</th> 
                <th class="text-center">Apellido</th>
                <th class="text-center">CI</th>
                <th class="text-center">Telefono</th> 
                <th class="text-center">Acciones</th> 
              </tr>
          </thead>
          <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->name }}</td>
                    <td class="text-center">{{ $cliente->apellido }}</td>
                    <td class="text-center">{{  number_format($cliente->ci, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $cliente->phone }}</td>
                    <td class="text-center">
                      <a href="{{ url('branches/' . $cliente->id . '/edit') }}"><i class="fa fa-pencil-alt"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
      </div>
  </div>
</div>                    
@endsection
