<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestMaterialsController extends Controller
{
    public function index()
    {
        return view('pages.request-materials.index');
    }

    public function create(Request $request)
    {

        return redirect()->route('request-materials')->with('success', 'Solicitud de material registrada correctamente');
    }
}
