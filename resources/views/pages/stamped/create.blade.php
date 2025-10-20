@extends('layouts.AdminLTE.index')
@section('title', 'Registrar Timbrado')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-success">
            <div class="card-header">
                <h5>Registrar Timbrado</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('stampeds.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text"  name="number" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" name="from" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="fin" class="form-label">Fecha Fin</label>
                            <input type="date" name="end" class="form-control">
                        </div>
                    </div>

                    <div class="mb-1">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea id="observacion" rows="3" name="observation" class="form-control"></textarea>
                    </div>
                    <div style="margin-top: 2%">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success me-2">Guardar</button>
                            <a href="/timbrado" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
