@extends('layouts.AdminLTE.index')
@section('title', 'Detalle de Timbrado')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <a href="{{ url('stampeds') }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left"></i> Volver
            </a>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">N° de Timbrado</h6>
                        <p class="fs-5 fw-semibold">{{ $stamped->number }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Fecha Inicio</h6>
                        <p class="fs-5 fw-semibold">
                            {{ $stamped->from_date ? $stamped->from_date->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Fecha Fin</h6>
                        <p class="fs-5 fw-semibold">
                            {{ $stamped->until_date ? $stamped->until_date->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Estado</h6>
                        @if ($stamped->status == 1)
                            <span class="badge bg-success px-3 py-2 fs-6">Activo</span>
                        @else
                            <span class="badge bg-danger px-3 py-2 fs-6">Inactivo</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted mb-1">Observación</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $stamped->observation ?: 'Sin observaciones' }}
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <small class="text-muted">
                    <i class="fa fa-user"></i> Registrado por:
                    <strong>{{ optional($stamped->user)->name ?? 'Usuario desconocido' }}</strong>
                </small>
            </div>
        </div>
    </div>
</div>

@endsection

@section('layout_css')
<style>
    .card {
        border-radius: 0.5rem;
    }
    .border {
        border-color: #e2e3e5 !important;
    }
    .fs-5 {
        font-size: 1.05rem !important;
    }
    .fw-semibold {
        font-weight: 600 !important;
    }
</style>
@endsection
