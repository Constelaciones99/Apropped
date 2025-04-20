<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Producto;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Mostrar todas las órdenes de un usuario
    public function index()
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Si está autenticado, obtener las órdenes del usuario
            $orders = Auth::user()->orders;  // La relación "orders" debe existir en el modelo User
            return view('orders.index', compact('orders'));  // Pasamos las órdenes a la vista
        }

        // Si el usuario no está autenticado, redirigirlo a la página de login
        return redirect()->route('home')->with('error', 'Debes iniciar sesión para ver tus órdenes.');
    }

    // Crear una nueva orden
    public function store(Request $request, $producto_id)
    {
        // Validación del pedido
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Crear la orden
        $order = Order::create([
            //'producto_id' => $producto_id,
            'user_id' => Auth::id(), // ✅ Paréntesis aquí también
            'nombre_cliente' => $request->nombre_cliente,
            'direccion' => $request->direccion,
            'cantidad' => $request->cantidad,
            'estado' => 'Pendiente',
        ]);

        return redirect()->view('orders.index')->with('success', '¡Tu orden se ha creado con éxito!');
    }
}
