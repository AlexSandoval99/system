<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RemissionNotesController extends Controller
{
    public function index()
    {
        return view('pages.remission-note.index');
    }

    public function create(Request $request)
    {
        return redirect()->route('remission-note')->with('success', 'Nota de remisión registrada correctamente');
    }
}
