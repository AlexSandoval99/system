<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StampedController extends Controller
{
    public function index()
    {
        return view('pages.stampeds.index');
    }

    public function create(Request $request)
    {
        return redirect()->route('stampeds')->with('success', 'Timbrado registrado correctamente');
    }
}
