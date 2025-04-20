<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;


class TiendaController extends Controller
{
    // Mostrar todos los productos en la tienda
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtro por categoría (si se selecciona)
        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Ordenar productos
        switch ($request->orden) {
            case 'aleatorio':
                $query->inRandomOrder();
                break;
            case 'recientes':
                $query->orderBy('created_at', 'desc');
                break;
            case 'barato':
                $query->orderBy('precio', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $productos = $query->paginate(9);
        return view('tienda.index', compact('productos'));
    }

    // Mostrar detalles del producto
    public function show($id)
    {
        $producto = Product::findOrFail($id);
        return view('tienda.show', compact('producto'));
    }

    // Procesar una orden del cliente
    public function ordenar(Request $request, $id)
    {
        $producto = Product::findOrFail($id);

        // Aquí puedes agregar más campos a la orden
        $orden = new Order();
        $orden->producto_id = $producto->id;
        $orden->nombre_cliente = $request->nombre_cliente;
        $orden->direccion = $request->direccion;
        $orden->cantidad = $request->cantidad;
        $orden->estado = 'Pendiente'; // Estado inicial
        $orden->save();

        return redirect()->route('tienda.index')->with('success', '¡Gracias por tu compra! Orden registrada.');
    }
}
