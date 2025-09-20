<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamWorksController extends Controller
{
    public function index()
    {
        return view('team_works.index');
    }

    public function create(Request $request)
    {
        return redirect()->route('team_works')->with('success', 'Equipo de trabajo creado correctamente');
    }
}
