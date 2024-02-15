<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
        {!! \App\Models\Config::find(1)->app_name_abv !!} | @yield('title')
</title>
<link rel="shortcut icon" href="{{ asset(\App\Models\Config::find(1)->favicon) }}" type="image/x-icon"/>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/select2/dist/css/select2.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/AdminLTE.min.css') }}">
<!-- adminlte Skins. -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/skins/_all-skins.min.css') }}">
<!-- Morris chart -->
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/jvectormap/jquery-jvectormap.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/iCheck/square/blue.css') }}">

<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- CSS Custom -->
<link rel="stylesheet" href="{{ asset('assets/adminlte/documentation/style.css') }}">
<!-- jQuery 3 -->
<script src="{{ asset('assets/adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/inputmask.min.js') }}"></script>
<script src="{{ asset('js/inputmask.numeric.extensions.min.js') }}"></script>
<script src="{{ asset('js/jquery.inputmask.min.js')}}"></script>
<!-- MAskMoney -->
<style>
        .link_menu_page{ color:#222d32; }
        .caixa-alta { text-transform:uppercase; }
        .caixa-baixa { text-transform:lowercase; }
        .input-text-center{ text-align:center; }
</style>

@yield('layout_css')
