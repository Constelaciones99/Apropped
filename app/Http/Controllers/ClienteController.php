<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class ClienteController extends Controller
{
    // Página principal: muestra todos los productos
    public function index()
    {
        $productos = Product::with('imagenes')->get();
        return view('cliente.index', compact('productos'));
    }

    // Detalle de producto
    public function show($id)
    {
        $producto = Product::with('imagenes')->findOrFail($id);
        return view('cliente.show', compact('producto'));
    }

    // Formulario para ordenar producto save
    public function guardarOrden(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Debes iniciar sesión para realizar un pedido.');
        }

        $validated = $request->validate([
            'direccion' => 'required|string|max:255',
            'nombre_cliente' => 'required|string|max:255',
        ]);

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.ver')->with('error', 'Tu carrito está vacío.');
        }

        $orden = Order::create([
            'user_id'        => Auth::id(),
            'nombre_cliente' => $validated['nombre_cliente'],
            'direccion'      => $validated['direccion'],
            'estado'         => 'Pendiente',
            'productos'      => json_encode($carrito), // <-- guarda todo el carrito
            'boleta'         => null, // Luego se puede actualizar con la ruta del PDF
            'created_at'     => now(),
        ]);

        // Vaciar el carrito
        session()->forget('carrito');

        return redirect()->route('home')->with('success', '¡Orden registrada con éxito!');
    }



    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'username' => 'required|unique:usuarios',
            'celular' => 'required| digits:9',
            'direccion' => 'required',
        ]);

        $usuario = new User();
        $usuario->nombre = $request->nombre;
        $usuario->username = $request->username;
        $usuario->celular = $request->celular;
        $usuario->direccion = $request->direccion;
        $usuario->save();

        Auth::login($usuario); // Lo logueas directamente

        return response()->json(['success' => true]);
    }

    public function ordenar($id)
    {
        $producto = Product::findOrFail($id);

        return view('cliente.ordenar', compact('producto'));
    }

    // Mostrar formulario para editar usuario
    public function editar()
    {
        $usuario = Auth::user(); // Obtener los datos del usuario logueado
        //return view('cliente.editar', compact('usuario'));
    }

    // Actualizar usuario
    public function actualizar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'celular' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $usuario = Auth::user();
        $usuario->nombre = $request->nombre;
        $usuario->username = $request->username;
        $usuario->celular = $request->celular;
        $usuario->direccion = $request->direccion;
        $usuario->email = $request->email;

        $usuario->save();

        return redirect()->route('home')->with('success', 'Datos actualizados correctamente');
    }

    // Agregar al carrito
    public function agregarAlCarrito(Request $request, $id)
    {
        $producto = Product::findOrFail($id);

        // Verifica si el carrito ya tiene el producto
        $carrito = session()->get('carrito', []);

        // Si ya existe, aumentar la cantidad
        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'id' => $producto->id, // ✅ AÑADIDO
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
                'imagen' => $producto->imagenes->first()->ruta ?? 'default.jpg',
            ];
        }

        // Actualiza el carrito en la sesión
        session()->put('carrito', $carrito);

        return redirect()->route('home')->with('success', 'Producto añadido al carrito.');
    }

    // Ver el carrito
    public function verCarrito()
    {
        $carrito = session()->get('carrito', []);
        return view('cliente.carrito', compact('carrito'));
    }

    // Eliminar un producto del carrito
    public function eliminarDelCarrito($id)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$id]);
        session()->put('carrito', $carrito);

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    // Vaciar el carrito por completo
    public function vaciarCarrito()
    {
        session()->forget('carrito');
        return redirect()->back()->with('success', 'Carrito vaciado correctamente.');
    }

    // Vista de ordenar todos los productos del carrito
    public function vistaOrdenarCarrito()
    {
        $carrito = session()->get('carrito', []);
        $subtotal = array_sum(array_map(function ($producto) {
            return $producto['precio'] * $producto['cantidad'];
        }, $carrito));

        return view('cliente.ordenar', compact('carrito', 'subtotal'));
    }

    public function actualizarCantidad(Request $request, $id)
    {
        $data = $request->all();
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] = (int) $data['cantidad'];
            session()->put('carrito', $carrito);
        }

        return response()->json(['success' => true]);
    }

}


