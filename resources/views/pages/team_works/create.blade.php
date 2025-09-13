@extends('layouts.AdminLTE.index')
@section('title', 'Registrar Equipo')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-success">
            <div class="card-header">
                <h5>Registrar Equipo</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre del equipo">
                        </div>
                        <div class="col-md-6">
                            <label for="trabajo" class="form-label">Trabajo</label>
                            <input type="text" class="form-control" id="trabajo" placeholder="Tipo de trabajo">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Guardar</button>
                        <a href="#" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
