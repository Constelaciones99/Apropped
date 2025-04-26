<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AproppedController extends Controller
{
    public function ruta(){
        if (Auth::check()) {
            if (Auth::user()->rol === 'vendedor') {
                return redirect()->route('vendedor.index');
            } elseif (Auth::user()->rol === 'admin') {
                return redirect()->route('admin.index');
            } else {
                return redirect()->route('home');
            }
        }

        return view('inicio'); // Si no hay sesión, muestra la vista normal
    }
}
