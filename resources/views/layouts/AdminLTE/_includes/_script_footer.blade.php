<!-- jQuery -->
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/jquery/dist/jquery.js') }}"></script>
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ secure_secure_asset('assets/adminlte/plugins/iCheck/icheck.min.js') }}"></script>

<!-- jQuery UI -->
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Otros scripts (en el orden correcto) -->
<script src="{{ secure_secure_asset('js/jquery.number.2.1.6.min.js') }}"></script>
{{-- <script src="{{ secure_secure_asset('assets/adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script> --}}
<script src="{{ secure_secure_asset('js/jquery.inputmask.min.js') }}"></script>
{{-- <script src="{{ secure_secure_asset('js/inputmask.numeric.extensions.min.js') }}"></script> --}}
<script src="{{ secure_secure_asset('js/jquery.magnific-popup.min.js') }}"></script>

<!-- Librerías adicionales -->
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ secure_secure_asset('assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Librerías específicas -->
<script src="{{ secure_secure_asset('js/sweetAlert/sweetalert.min.js') }}"></script>
<script src="{{ secure_secure_asset('js/jquery.simplyCountable.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ secure_secure_asset('assets/adminlte/dist/js/adminlte.min.js') }}"></script>

<!-- Scripts personalizados -->
<script src="{{ secure_secure_asset('js/main.js') }}"></script>

<!-- Configuración adicional -->
<script type="text/javascript">
  $(document).ready(function () {
    $('#flash_message').delay(2000).fadeOut();
  });
</script>

<!-- Scripts específicos de cada vista -->
@yield('layout_js')
