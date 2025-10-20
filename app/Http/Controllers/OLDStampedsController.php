<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StampedController extends Controller
{
    public function index()
    {
        return view('pages.stampe.index');
    }

    public function create(Request $request)
    {
        return redirect()->route('stampe')->with('success', 'Timbrado registrado correctamente');
    }
}
