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
                <form>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" id="numero" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" id="inicio" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="fin" class="form-label">Fecha Fin</label>
                            <input type="date" id="fin" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea id="observacion" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Guardar</button>
                        <a href="/timbrado" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
