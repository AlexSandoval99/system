@extends('layouts.AdminLTE.index')
@section('title', 'Agregar Punto de Expedicion')
@section('content')
<div>
    {{ Form::open(['route' => 'voucher_boxes.store']) }}
        <div class="ibox-content">
            @include('partials.messages')
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Nombre Caja</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" autofocus>
                </div>
                <div class="form-group col-md-4">
                    <label>Número Caja</label>
                    <input class="form-control" type="text" name="voucher_number" value="{{ old('voucher_number') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Sucursal</label>
                    {{ Form::select('branch_id', $branches, old('branch_id'), ['class' => 'form-control']) }}
                </div>
                <div class="form-group col-md-4">
                    <label>Timbrado Factura</label>
                    {{ Form::select('stamped_id', $stampeds, old('stamped_id'), ['placeholder' => 'Seleccione Timbrado', 'class' => 'form-control', 'select2']) }}
                </div>
                <div class="form-group col-md-4">
                    <label>Desde Nro. Factura</label>
                    <input class="form-control" type="text" name="from_invoice_number" value="{{ old('from_invoice_number') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Hasta Nro. Factura</label>
                    <input class="form-control" type="text" name="until_invoice_number" value="{{ old('until_invoice_number') }}">
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            <input type="submit" class="btn btn-sm btn-success" value="Guardar">
            <a href="{{ url('voucher_boxes') }}" class="btn btn-sm btn-danger">Cancelar</a>
        </div>
    {{ Form::close() }}
</div>
@endsection
