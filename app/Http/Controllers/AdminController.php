<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Boleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminController extends Controller
{


    public function index()
    {
        $vendedores = User::where('rol', 'vendedor')->paginate(10);
        return view('products.admin', compact('vendedores'));
    }

    public function create()
    {
        return view('products.createu');
    }

    public function store(Request $request)
    {


        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:usuarios,username',
            'email' => 'required|email|unique:usuarios,email',
            'celular' => 'required|string|regex:/^[0-9]{9}$/',
            'direccion' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'username' => $request->username,
            'email' => $request->email,
            'celular' => $request->celular,
            'direccion' => $request->direccion,
            'rol' => $request->rol,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('admin.usuarios.index')->with('success', 'Vendedor creado correctamente.');
    }

    public function edit(User $user)
    {
        if ($user->rol !== 'vendedor') {
            abort(403);
        }

        return view('products.editu', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->rol !== 'vendedor') {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => "required|email|unique:usuarios,email,{$user->id}",
            'celular' => 'required|string|size:9',
            'direccion' => 'required|string|max:255',
        ]);

        $user->update($request->only('nombre', 'username', 'email', 'celular', 'direccion'));

        return redirect()->route('admin.usuarios.index')->with('success', 'Vendedor actualizado.');
    }

    public function destroy(User $user)
    {
        if ($user->rol !== 'vendedor') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Vendedor eliminado.');
    }

    public function boletas(){
        $boletas = Boleta::with('order')->get(); // trae boletas y la relación con orders

        foreach ($boletas as $boleta) {
            if (!Str::endsWith($boleta->numero, '.pdf')) {
                $boleta->numero = 'boleta_' . $boleta->id . '.pdf';
                $boleta->save();
            }
        }

        return view('products.reporte', compact('boletas'));
    }

    public function graph()
    {
        return view('products.dashboard');
    }


}
