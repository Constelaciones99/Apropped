<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;


class TiendaController extends Controller
{

    public function index()
    {
        return view('prediccion.tensor');
    }

    public function validarVendedor(Request $request)
    {
        $request->validate([
            'username' => 'required|string'
        ]);

        // Buscar usuario por nombre de usuario
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['status' => 'no_existe'], 404); // Usuario no encontrado
        }

        // Verificar si el usuario tiene rol "vendedor" y está activo
        if ($user->rol !== 'vendedor') {
            return response()->json(['status' => 'no_permiso'], 403); // No tiene permisos
        }

        if ($user->activo !== 1) {
            return response()->json(['status' => 'inactivo'], 403); // Usuario inactivo
        }

        // Iniciar sesión con el usuario
        Auth::login($user);

        return response()->json(['status' => 'ok']); // Todo está bien, inicio de sesión exitoso
    }

}
