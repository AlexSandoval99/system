@extends('layouts.AdminLTE.index')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header text-white text-center">
                <h4 class="mb-0">🔒 Cambiar Contraseña</h4>
            </div>
            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group mb-5">
                        <label for="email" class="form-label fw-bold">Correo electrónico</label>
                        <input id="email" type="email"
                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}"
                               placeholder="ejemplo@correo.com" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-key"></i> Enviar enlace para cambiar contraseña
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted text-center small">
                Ingresa tu correo para recibir el enlace de restablecimiento.
            </div>
        </div>
    </div>
</div>
@endsection
