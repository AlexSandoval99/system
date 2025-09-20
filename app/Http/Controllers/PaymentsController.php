<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        return view('pages.payments.index');
    }

    public function create(Request $request)
    {
        return redirect()->route('payments')->with('success', 'Pago registrado correctamente');
    }
}
