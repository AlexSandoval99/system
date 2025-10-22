<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $voucher->voucher_type == 3 ? 'Recibo' : 'Factura' }} {{ $voucher->voucher_fullnumber }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 25px; color: #333; }
        .header { width: 100%; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 5px; }
        .header .left { float: left; width: 60%; }
        .header .right { float: right; width: 35%; text-align: right; }
        .header h2 { margin: 0; font-size: 18px; }
        .clear { clear: both; }
        .info, .table, .totales { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info td, .table th, .table td, .totales td { border: 1px solid #000; padding: 6px; }
        .table th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #555; }
        .logo { width: 100px; height: auto; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div class="left">
            @if(file_exists(public_path('img/cort.png')))
                <img src="{{ public_path('img/cort.png') }}" class="logo" alt="Logo">
            @endif
            <h2>All'Cort</h2>
            <p>RUC: 5504073<br>
            Dirección: Ruta D027, Capiatá<br>
            Tel: 0993 435637</p>
        </div>
        <div class="right">
            <h3><strong>{{ $voucher->voucher_type == 3 ? 'RECIBO' : config('constants.type_purchases.' . $voucher->voucher_type) }}</strong></h3>
            <p><strong>Nro:</strong> {{ $voucher->voucher_fullnumber }}</p>
            <p><strong>Fecha:</strong> {{ $voucher->date->format('d/m/Y') }}</p>
            <p><strong>Condición:</strong> {{ config('constants.invoice_condition.' . $voucher->voucher_condition) ?? '' }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Datos del cliente -->
    <table class="info">
        <tr>
            <td><strong>Cliente:</strong> {{ $voucher->razon_social }}</td>
            <td><strong>RUC:</strong> {{ $voucher->ruc }}</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong> {{ $voucher->address ?? '-' }}</td>
            <td><strong>Teléfono:</strong> {{ $voucher->phone ?? '-' }}</td>
        </tr>
    </table>

    @if($voucher->voucher_type != 3)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>Descripción</th>
                    <th style="width: 10%;">Cantidad</th>
                    <th style="width: 15%;">Precio Unitario</th>
                    <th style="width: 15%;">Exenta</th>
                    <th style="width: 15%;">IVA 5%</th>
                    <th style="width: 15%;">IVA 10%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($voucher->voucher_details as $i => $detalle)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $detalle->articulo_id ? $detalle->articulo->name : 'Sin nombre'  }}</td>
                        <td class="text-right">{{ number_format($detalle->quantity, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detalle->amount, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detalle->excenta, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detalle->iva5, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detalle->iva10, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totales">
            <tr>
                <td style="width: 70%; border: none;"></td>
                <td><strong>Total Exenta</strong></td>
                <td class="text-right">{{ number_format($voucher->total_excenta, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="width: 70%; border: none;"></td>
                <td><strong>Total IVA 5%</strong></td>
                <td class="text-right">{{ number_format($voucher->total_iva5, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="width: 70%; border: none;"></td>
                <td><strong>Total IVA 10%</strong></td>
                <td class="text-right">{{ number_format($voucher->total_iva10, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="width: 70%; border: none;"></td>
                <td><strong>Total General</strong></td>
                <td class="text-right"><strong>{{ number_format($voucher->amount, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    @else
        {{-- 💵 RECIBO --}}
        <div style="margin-top: 25px; line-height: 1.8;">
            <p>Recibí de <strong>{{ $voucher->razon_social }}</strong>,
            con RUC <strong>{{ $voucher->ruc }}</strong>,
            la suma de <strong>Gs. {{ number_format($voucher->amount, 0, ',', '.') }}</strong>
            en concepto de <strong>{{ $voucher->observation ?? 'Pago de cuotas' }}</strong>.</p>

            <p>Este pago fue recibido en fecha <strong>{{ $voucher->date->format('d/m/Y') }}</strong>
            por <strong>{{ $voucher->user->name ?? '---' }}</strong>.</p>

            <br><br><br>
            <div style="text-align:center;">
                <p>___________________________<br>
                Firma y Aclaración</p>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Documento generado por el sistema - {{ now()->format('d/m/Y H:i') }}<br>
        Usuario: {{ $voucher->user->name ?? '---' }}</p>
    </div>
</body>
</html>
