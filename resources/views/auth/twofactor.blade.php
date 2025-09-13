@extends('layouts.AdminLTE.index')
@section('content')
<div class="container" style="max-width:420px">
    <h3 class="mb-3">Verificación en 2 pasos</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <p>Te enviamos un código de 6 dígitos a tu correo. Ingrésalo para continuar.</p>

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label d-block">Código</label>

            @php
                $oldCode = old('code', '');
                $isInvalid = $errors->has('code');
            @endphp

            <div class="otp-group" data-length="6" aria-label="Ingreso de código en 6 dígitos">
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="1"
                        autocomplete="one-time-code"
                        class="otp-input form-control{{ $isInvalid ? ' is-invalid' : '' }}"
                        data-index="{{ $i }}"
                        value="{{ $oldCode[$i] ?? '' }}"
                    >
                @endfor
            </div>
            <input type="hidden" name="code" id="code" value="{{ old('code') }}">
            @error('code')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember_device" name="remember_device" value="1">
            <label class="form-check-label" for="remember_device">
                Recordar este dispositivo por 30 días
            </label>
        </div>

        <button class="btn btn-primary w-100" type="submit">Verificar</button>
    </form>

    <form method="POST" action="{{ route('2fa.resend') }}" class="mt-3">
        @csrf
        <button class="btn btn-link p-0">Reenviar código</button>
    </form>
</div>
@endsection
@section('layout_css')
    <style>
        .otp-group{
            display:flex;
            /* si usas Bootstrap 4 y "gap" no aplica, dejamos fallback con el selector de abajo */
            gap:10px;
        }
        .otp-input{
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: .02em;
            border-radius: .5rem;
            line-height: 56px;
            padding: 0;
        }
        .otp-input + .otp-input{ /* fallback de separación para BS4 */
            margin-left:10px;
        }
        .otp-input:focus{
            box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
        }
    </style>
@endsection
@section('layout_js')
    <script>
        (function(){
            var inputs = Array.prototype.slice.call(document.querySelectorAll('.otp-input'));
            var hidden = document.getElementById('code');

            function syncHidden(){
                var code = inputs.map(function(i){ return (i.value || '').replace(/\D/g,''); }).join('');
                hidden.value = code;
            }

            inputs.forEach(function(input, idx){
                input.addEventListener('input', function(e){
                    // solo números, tomar el último dígito tipeado
                    var v = e.target.value.replace(/\D/g,'');
                    e.target.value = v.slice(-1);

                    if (e.target.value && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                        inputs[idx + 1].select();
                    }
                    syncHidden();
                });

                input.addEventListener('keydown', function(e){
                    if (e.key === 'Backspace' && !input.value && idx > 0) {
                        inputs[idx - 1].focus();
                        inputs[idx - 1].select();
                    } else if (e.key === 'ArrowLeft' && idx > 0) {
                        inputs[idx - 1].focus();
                    } else if (e.key === 'ArrowRight' && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                });

                input.addEventListener('paste', function(e){
                    e.preventDefault();
                    var paste = (e.clipboardData || window.clipboardData).getData('text') || '';
                    paste = paste.replace(/\D/g,'').slice(0, inputs.length);

                    for (var i = 0; i < inputs.length; i++) {
                        inputs[i].value = paste[i] || '';
                    }
                    // enfocar el último llenado o el último input
                    var lastIdx = Math.min(paste.length, inputs.length) - 1;
                    if (lastIdx >= 0) { inputs[lastIdx].focus(); inputs[lastIdx].select(); }
                    syncHidden();
                });
            });

            // si hay old('code') rellenamos el hidden correcto (y enfocamos el primero vacío)
            syncHidden();
            var firstEmpty = inputs.find(function(i){ return !i.value; }) || inputs[0];
            if (firstEmpty) firstEmpty.focus();

            // validación rápida al enviar (opcional, el servidor valida igual)
            var form = document.querySelector('form[action="{{ route('2fa.verify') }}"]');
            if (form) {
                form.addEventListener('submit', function(e){
                    syncHidden();
                    if (hidden.value.length !== inputs.length) {
                        // mostrar estilo inválido en todos
                        inputs.forEach(function(i){ i.classList.add('is-invalid'); });
                        e.preventDefault();
                    }
                });
            }
        })();
    </script>
@endsection
