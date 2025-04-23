<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Boleta;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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

    public function boletas(Request $request)
    {
        $query = $request->input('query');
        $usuario = Auth::user();

        $boletasQuery = Boleta::with('order');

        if (!$usuario) {
            return redirect()->route('home')->with('error', 'Debes iniciar sesión para ver el reporte.');
        }

        // Filtro según rol
        if (is_null($usuario->rol) || $usuario->rol === 'cliente') {
            $boletasQuery->where('user_id', $usuario->id);
        }

        // Búsqueda
        if ($query) {
            $boletasQuery->where(function ($q) use ($query) {
                $q->where('numero', 'LIKE', '%' . $query . '%')
                    ->orWhereHas('order', function ($sub) use ($query) {
                        $sub->where('nombre_cliente', 'LIKE', '%' . $query . '%')
                            ->orWhere('id', 'LIKE', '%' . $query . '%');
                    });
            });
        }

        // Ordenar y paginar
        $boletas = $boletasQuery
            ->orderByDesc('order_id')
            ->paginate(10);

        // Asegurar nombres de archivo correctos
        foreach ($boletas as $boleta) {
            if (!Str::endsWith($boleta->numero, '.pdf')) {
                $boleta->numero = 'boleta_' . $boleta->id . '.pdf';
                $boleta->save();
            }
        }

        return view('products.reporte', [
            'boletas' => $boletas,
            'busqueda' => $query,
        ]);
    }

    public function buscar(Request $request)
    {
        $busqueda = $request->input('query');

        $boletas = Boleta::with('order')
            ->where('numero', 'LIKE', "%{$busqueda}%")
            ->orWhereHas('order', function ($query) use ($busqueda) {
                $query->where('nombre_cliente', 'LIKE', "%{$busqueda}%")
                    ->orWhere('id', 'LIKE', "%{$busqueda}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['query' => $busqueda]);

        return view('products.reporte', [
            'boletas' => $boletas,
            'busqueda' => $busqueda
        ]);
    }


    public function graph()
    {
        $hoy = Carbon::today();

        // 🔸 Ventas del día (solo las de hoy)
        $ventasHoy = Sale::whereDate('fecha_venta', $hoy)->sum('cantidad');

        // 🔸 Productos más vendidos (acumulado por producto en cualquier fecha)
        $topProductos = DB::table('ventas')
            ->join('productos', 'ventas.producto_id', '=', 'productos.id')
            ->select('productos.nombre', DB::raw('SUM(ventas.cantidad) as total_vendido'))
            ->groupBy('productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // 🔸 Ventas por día (últimos 7 días)
        $ventasPorDia = Sale::select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(cantidad) as total'))
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->orderBy('fecha')
            ->take(7)
            ->get();

        // 🔸 Ventas de los últimos 7 días (por fecha)
        $ventasPorDia = DB::table('ventas')
            ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(cantidad) as total'))
            ->where('fecha_venta', '>=', now()->subDays(15)) // hoy y 6 días atrás
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->orderBy('fecha')
            ->get();

        // 🔸 Productos con su stock actual
        $productosStock = Product::select('id','nombre', 'stock')->orderBy('stock', 'asc')->get();
        // 🔸 Productos con stock crítico (menor a 10 unidades)
        $stockCritico = Product::where('stock', '<', 10)->get();

        //Subtotal por fecha
        $ventasPorFecha = DB::table('ventas')
            ->join('productos', 'ventas.producto_id', '=', 'productos.id')
            ->select(
                DB::raw('DATE(ventas.fecha_venta) as fecha'),
                DB::raw('SUM(productos.precio * ventas.cantidad) as subtotal')
            )
            ->groupBy(DB::raw('DATE(ventas.fecha_venta)'))
            ->orderByDesc('fecha')
            ->limit(10)
            ->get();


        return view('products.dashboard', compact('ventasHoy', 'topProductos', 'productosStock', 'ventasPorDia', 'ventasPorFecha'));

    }

    public function ventasPorProducto($id)
    {
        $ventas = Sale::where('producto_id', $id)
            ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(cantidad) as total'))
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->orderBy('fecha')
            ->get();

        $producto = Product::with('ventas')->findOrFail($id);

        return response()->json([
            'ventas' => $ventas,
            'producto' => [
                'nombre' => $producto->nombre,
                'stock' => $producto->stock,
                'total_vendido' => $producto->ventas->sum('cantidad'),
                'ultima_venta' => optional($producto->ventas->last())?->fecha_venta
                    ? \Carbon\Carbon::parse($producto->ventas->last()->fecha_venta)->format('Y-m-d')
                    : null,
            ]
        ]);
    }

    public function boletasCliente()
    {
        $usuario = Auth::user();

        if (!$usuario || $usuario->rol !== 'cliente') {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $boletas = Boleta::with('order')
            ->where('user_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 👈 Paginamos de 10 en 10


        return view('cliente.boletas', compact('boletas'));
    }

}
