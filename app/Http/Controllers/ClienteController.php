<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Boleta;
use App\Models\Category;
use App\Models\Detail;


class ClienteController extends Controller
{
    // Página principal: muestra todos los productos

    public function index(Request $request)
    {
        $query = Product::with('imagenPrincipal', 'categoria');

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('orden_precio') && ($request->nombre || $request->categoria_id)) {
            $query->orderBy('precio', $request->orden_precio === 'asc' ? 'asc' : 'desc');
        }

        $productos = $query->paginate(9);
        $categorias = Category::all(); // Para el select

        $favoritos = [];

        if (auth::check()) {
            $detalleVenta = Detail::where('id_usuario', Auth::user()->id)->first();
            if ($detalleVenta) {
                $favoritos = json_decode($detalleVenta->productos, true) ?? [];
            }
        }

        return view('cliente.index', compact('productos', 'categorias', 'favoritos'));
    }

    public function loginCliente(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = User::where('username', $request->username)->first();

        if (!$usuario) {
            return back()->with('error', 'usuarios incorrectas')->withInput();
        }



        if (!Hash::check($request->password, $usuario->password)) {

            return back()->with('error', 'Credenciales incorrectas')->withInput();
        }

        Auth::login($usuario);

        // Redirigir según el rol
        if ($usuario->rol === 'cliente') {
            return redirect()->route('home');
        } elseif ($usuario->rol === 'vendedor') {
            return redirect()->route('vendedor.index');
        }

        return redirect()->route('home'); // Por defecto
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Validación básica de admin
        if ($request->username === 'admin' && $request->password === 'admin') {
            // Buscar al usuario admin (por si ya está en la DB)
            $admin = User::where('username', 'admin')->first();

            // Si no existe, lo creamos
            if (!$admin) {
                $admin = User::create([
                    'username' => 'admin',
                    'password' => bcrypt('admin'),
                    'nombre' => 'Administrador',
                    'rol' => 'admin',
                    'celular' => '000000000',
                    'direccion' => 'Oficina Central',
                ]);
            }

            Auth::login($admin);
            return redirect()->route('admin.index'); // o a donde tú desees redirigir
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'username' => 'required|unique:usuarios',
            'password'=>'required',
            'celular' => 'required| digits:9',
            'direccion' => 'required',
        ]);

        $user = User::create([
            'nombre' => $request['nombre'],
            'password' =>$request->password,
            'username' => $request['username'],
            'celular' => $request['celular'],
            'direccion' => $request['direccion'],
            'rol' => 'cliente',
        ]);

        Auth::login($user); // Lo logueas directamente

        return redirect()->route('home');
    }

    public function filtrarAjax(Request $request)
    {
        $query = Product::with('imagenPrincipal', 'categoria');

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if (in_array($request->orden_precio, ['asc', 'desc'])) {
            $query->orderBy('precio', $request->orden_precio);
        }

        $productos = $query->paginate(9);

        return response()->json([
            'html' => view('partials._productos', compact('productos'))->render()
        ]);
    }

    // Detalle de producto
    public function show($id)
    {

        $producto = Product::with('imagenes')->findOrFail($id);
        return view('cliente.show', compact('producto'));
    }

    // Formulario para ordenar producto save hash
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

        // 1. Crear orden
        $orden = Order::create([
            'user_id'        => Auth::id(),
            'nombre_cliente' => $validated['nombre_cliente'],
            'direccion'      => $validated['direccion'],
            'estado'         => 'Pendiente',
            'productos'      => json_encode($carrito),
            'boleta'         => null,
            'created_at'     => now(),
        ]);

        $productos = $carrito;
        $fechaHoy = Carbon::today();
        $fechaTexto = $fechaHoy->toDateString();

        // 2. Registrar ventas acumulativas por fecha
        foreach ($productos as $producto) {
            $ventaExistente = Sale::where('producto_id', $producto['id'])
                ->whereDate('fecha_venta', $fechaHoy)
                ->first();

            if ($ventaExistente) {
                $ventaExistente->cantidad += $producto['cantidad'];
                $ventaExistente->save();
            } else {
                Sale::create([
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'fecha_venta' => $fechaHoy,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Crear boleta
        $boleta = Boleta::create([
            'user_id' => Auth::id(),
            'order_id' => $orden->id,
            'numero' => 'storage/public/boletas/boleta_' . $orden->id . '.pdf',
            'tipo' => 'boleta',
            'fecha' => $fechaTexto,
        ]);

        // 4. Generar PDF y guardarlo
        $vendedor = User::find(Auth::id());
        $pdf = Pdf::loadView('boletas.pdf', [
            'boleta' => $boleta,
            'cliente' => $validated['nombre_cliente'],
            'vendedor' => 'Tienda APROPPED',
            'productos' => $productos,
            'fecha' => $fechaTexto,
        ]);
        // 5. Limpiar carrito
        session()->forget('carrito');

        // Guardamos el PDF para luego descargarlo
        Storage::put('public/boletas/boleta_' . $orden->id . '.pdf', $pdf->output());

        // 6. Redirigir a Home con mensaje y link a boleta
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'boleta_' . $orden->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
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

    public function toggleFavorito(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Debes iniciar sesión.');
        }

        $productoId = $request->input('producto_id');
        $usuarioId = Auth::id();

        $detalleVenta = Detail::firstOrCreate(
            ['id_usuario' => $usuarioId],
            ['productos' => json_encode([]), 'fecha' => now()]
        );

        $productos = json_decode($detalleVenta->productos, true) ?? [];

        if (in_array($productoId, $productos)) {
            $productos = array_filter($productos, function ($id) use ($productoId) {
                return $id != $productoId;
            });
            $mensaje = 'Producto eliminado de favoritos.';
        } else {
            $productos[] = $productoId;
            $mensaje = 'Producto guardado en favoritos.';
        }

        $detalleVenta->productos = json_encode(array_values($productos));
        $detalleVenta->save();

        return redirect()->back()->with('success', $mensaje);
    }

    public function toggle(Request $request, $producto)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Debes iniciar sesión.');
        }

        $usuario = Auth::user();

        // Cargar detalle o crear uno nuevo
        $detalleVenta = Detail::firstOrCreate(
            ['id_usuario' => $usuario->id],
            ['productos' => json_encode([]), 'fecha' => now()]
        );

        $productosFavoritos = json_decode($detalleVenta->productos, true) ?? [];

        // Verificamos si ya existe
        if (in_array($producto, $productosFavoritos)) {
            $productosFavoritos = array_filter($productosFavoritos, fn($id) => $id != $producto);
            $mensaje = 'Producto eliminado de favoritos.';
        } else {
            $productosFavoritos[] = $producto;
            $mensaje = 'Producto guardado en favoritos.';
        }

        // Guardar cambios
        $detalleVenta->productos = json_encode(array_values($productosFavoritos));
        $detalleVenta->save();

        return redirect()->back()->with('success', $mensaje);
    }

    public function favoritos()
    {
        if (!Auth::check()) {
            return redirect()->route('home');  // Redirige a login si no hay sesión activa
        }

        $usuarioId = Auth::id();  // Obtener el ID del usuario autenticado

        // Buscar el detalle de venta que contiene los productos favoritos
        $detalleVenta = Detail::where('id_usuario', $usuarioId)->first();

        // Si el detalle de venta no existe, redirige o muestra mensaje de error
        if (!$detalleVenta) {
            return redirect()->route('home')->with('error', 'No tienes productos favoritos.');
        }

        // Decodificar los productos favoritos
        $productosFavoritos = json_decode($detalleVenta->productos, true) ?? [];

        // Obtener los productos que están guardados en favoritos
        $productos = Product::whereIn('id', $productosFavoritos)->get();

        return view('cliente.favoritos', compact('productos'));
    }

    // En el controlador
    public function deleteFav($productoId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false]);
        }

        $usuarioId = Auth::id();
        $detalleVenta = Detail::where('id_usuario', $usuarioId)->first();

        if (!$detalleVenta) {
            return redirect()->route('favoritos')->with('error', 'No tienes productos favoritos.');
        }

        $productos = json_decode($detalleVenta->productos, true) ?? [];

        // Filtrar el producto a eliminar
        $productos = array_filter($productos, function ($id) use ($productoId) {
            return $id != $productoId;
        });

        $detalleVenta->productos = json_encode(array_values($productos)); // Actualizamos la lista de productos
        $detalleVenta->save();

        return redirect()->route('favoritos')->with('success', 'Producto eliminado de tus favoritos.');
    }
}


