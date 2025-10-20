@extends('layouts.AdminLTE.index')
@section('title', 'Editar Timbrado')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Editar Timbrado</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('stampeds.update', $stamped->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text"  name="number" class="form-control" value="{{ $stamped->number }}">
                        </div>
                        <div class="col-md-4">
                            <label for="inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" name="from" class="form-control" value="{{ $stamped->from_date->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="fin" class="form-label">Fecha Fin</label>
                            <input type="date" name="end" class="form-control" value="{{ $stamped->until_date->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-1">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea id="observacion" rows="3" name="observation" class="form-control">{{ $stamped->observation }}</textarea>
                    </div>
                    <div style="margin-top: 2%">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Actualizar</button>
                            <a href="/timbrado" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
