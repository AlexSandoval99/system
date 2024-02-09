<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            size: A4;
            margin: 0px;
            padding: 0px;
        }
        * {
            margin: 0px;
            padding: 0px;
            font-size: 10px;
            font-family: 'dejavu sans';
        }
        html {
            margin: 15px;
        }
        body {
            margin: 0px 5px;
        }
        table {
            border-collapse: collapse;
        }
        .with_border td {
            border: 1px black solid;
            padding: 3px;
        }
        .with_outside_border {
            border: 1px black solid;
        }
        .with_side_border td {
            border-right: 1px black solid;
            border-left: 1px black solid;
            padding: 3px;
            border-bottom: 1px black solid;
        }
        .border_with_padding {
            padding: 3px;
            border: 1px black solid;
        }
    </style>
</head>
<body>
    <div class="border_with_padding">
        <table width="100%">
            <tr>
                <td width="30%" valign="top" style="font-size:7px;">
                    <img src="{{ asset(\App\Models\Config::find(1)->caminho_img_login) }}" height="30" width="30"/><br>
                    {{-- <img src="{{ public_path('img/logo_grupo_op.png') }}" height="25"><br> --}}
                    Mariscal López casi Pitiantuta / Asunción<br>
                    Tel: (021) 237 1100
                </td>
                <td width="40%" valign="top" align="center" style="font-size:20px;">
                    Solicitud de Compra
                </td>
                <td width="30%" valign="top" align="right" style="font-size: 10px;">
                    NRO :
                    {{ $wish_purchase->number }}<br>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <br>
    <div class="border_with_padding">
        <table width="100%">
            <tr>
                <td width="50%"><b>Dpto Solicitante:</b> {{ $wish_purchase->purchases_requesting_department ? $wish_purchase->purchases_requesting_department->name : '' }}</td>
                <td width="50%"><b>Solicitado por:</b> {{ $wish_purchase->requested_by }}</td>
            </tr>
            <tr>
                <td width="50%"><b>Fecha:</b> {{ $wish_purchase->date }}</td>
                <td width="50%"><b>Fecha requerimiento:</b> {{ $wish_purchase->date_requirement }}</td>
            </tr>
        </table>
    </div>
    <br>
    <br>
    <table width="100%" style="font-size:10px;">
        <tr class="with_border">
            <td width="5%" align="center"><b>Cód</b></th>
            <td width="45%" align="center"><b>Producto</b></th>
            <td width="10%" align="center"><b>Presentación</b></th>
            <td width="10%" align="center"><b>Proveedor</b></th>
            <td width="7%" align="center"><b>Cantidad</b></th>
        </tr>
        @foreach($wish_purchase->wish_purchase_details as $details)
            <tr class="with_side_border">
                <td valign="top">{{ $details->purchases_product_id }}</td>
                <td valign="top">{{ $details->description }}</td>
                <td valign="top">{{ $details->purchases_product_presentation ? $details->purchases_product_presentation->name : '' }}</td>
                <td valign="top">{{ $details->purchases_provider ? $details->purchases_provider->name : '' }}</td>
                <td valign="top" align="right">{{ number_format($details->quantity, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    <br>
    <br>
    <br>
    <br>
    @if($wish_purchase->observation)
        <table width="100%" style="font-size:10px;">
            <tr class="with_border">
                <td width="100%"><b>Observación</b></td>
            </tr>
            <tr>
                <td valign="top">{{ $wish_purchase->observation }}</td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <br>
    @endif
    <table width="100%" style="font-size:10px;">
        <tr class="with_border">
            <td width="20%" valign="top" align="center">
                <b>Hecho por</b><br>{{ $wish_purchase->user_id ? $wish_purchase->user->name : '' }}<br><br>
                @if($wish_purchase->user_id)
                    <img height="80" src="{{ public_path('storage/user-signatures/'.$wish_purchase->user->signature_image) }}"><br>
                @else
                    <br>
                @endif
            </td>
            <td width="20%" valign="top" align="center">
                <b>Verificado por</b><br>
            </td>
            <td width="20%" valign="top" align="center">
                <b>Controlado por</b><br>
            </td>
            <td width="20%" valign="top" align="center">
                <b>Compra Autorizada</b><br>{{ $wish_purchase->approve_user_id ? $wish_purchase->approve_user->preferred_fullname : '-' }}<br><br>
                @if($wish_purchase->approve_user_id && $wish_purchase->approve_user->signature_image)
                    <img height="80" src="{{ public_path('storage/user-signatures/'.$wish_purchase->approve_user->signature_image) }}"><br>
                @else
                    <br><br><br><br>
                @endif
            </td>
            <td width="20%" valign="top" align="center">
                <b>Pago Autorizado</b><br>
            </td>
        </tr>
    </table>
</body>
</html>
