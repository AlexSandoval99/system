<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function compras()
    {
        $subModulos = [
            'pedido' => 'Pedido de Compras',
            'orden' => 'Orden de Compras',
            'factura' => 'Factura Compras',
            'inventario' => 'Inventario',
            'existencia' => 'Existencia',
            'libro' => 'Libro Compra',
        ];

        return view('reportes.compras', compact('subModulos'));
    }

    public function produccion()
    {
        $subModulos = [
            'presupuesto' => 'Presupuesto',
            'orden' => 'Orden de Producción',
            'control' => 'Control de Producción',
            'calidad' => 'Control de Calidad',
            'mermas' => 'Mermas',
            'costo' => 'Costo Producción',
        ];

        return view('reportes.produccion', compact('subModulos'));
    }

    public function ventas()
    {
        $subModulos = [
            'pedidos' => 'Pedidos',
            'factura' => 'Factura Ventas',
            'cobros' => 'Cobros',
            'remision' => 'Nota de Remisión',
        ];

        return view('reportes.ventas', compact('subModulos'));
    }

    public function generar(Request $request, $modulo, $submodulo)
    {
        // Acá procesás el filtro y buscás datos
        return view('reportes.resultado', [
            'modulo' => $modulo,
            'submodulo' => $submodulo,
            'filtros' => $request->all()
        ]);
    }
}
