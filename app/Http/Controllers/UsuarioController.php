<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    
    public function show() {
        $datos = User::where('DocID', auth()->id())->first();

        return view('Datos', compact('datos'));
    }
}
