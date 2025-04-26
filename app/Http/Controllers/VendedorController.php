<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Models\Order;
use App\Models\Boleta;
use App\Models\Sale;

class VendedorController extends Controller
{

    public function vender(){

        $productos = Product::paginate(6);
        $categorias = Category::all(); // Asegúrate de importar el modelo Categoria
        $imagenes=Image::all();

        return view('vendedor.index',compact('productos', 'categorias','imagenes'));
    }

    public function validarVendedor(Request $request)
    {
        $username = $request->input('username');

        $user = User::where('username', $username)
            ->where('rol', 'vendedor')
            ->first();

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        if ($user->activo != 1) {
            return back()->with('error', 'Usuario inactivo. No tiene permisos.');
        }

        // Autenticación
        Auth::login($user);

        return redirect()->route('vendedor.index');
    }


    public function validar(Request $request)
    {
        $usuario = User::where('username', $request->username)
            ->where('rol', 'vendedor')
            ->first();

        if (!$usuario) {
            return response()->json(['status' => 'no_encontrado']);
        }

        if ($usuario->activo == 0) {
            return response()->json(['status' => 'inactivo']);
        }

        return response()->json(['status' => 'ok']);
    }

    public function mostrarProductos(Request $request)
    {

        $query = Product::query();

        // Filtro por nombre
        if ($request->has('nombre') && $request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por categoría
        if ($request->has('categoria_id') && $request->categoria_id) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por precio
        if ($request->has('precio') && $request->precio) {
            if ($request->precio == 'menor') {
                $query->orderBy('precio', 'asc');
            } else {
                $query->orderBy('precio', 'desc');
            }
        }

        // Paginación con 6 productos por página
        $productos = $query->paginate(6);

        // Obtener las categorías para el filtro
        $categorias = Category::all();

        if ($request->ajax()) {
            $imagenes = Image::all();
            return view('vendedor.lista', compact('productos', 'imagenes'))->render();
        }

        // ⚠️ Esto solo debe ejecutarse si NO es AJAX (como al cargar la página por primera vez)
        $imagenes = Image::all();
        return view('vendedor.index', compact('productos', 'categorias', 'imagenes'));
    }

    public function apiDetalles($id)
    {
        $producto = Product::with('categoria')->find($id);

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto);
    }

    public function verBoleta(Request $request)
    {
        $usuarios = User::all();

        $productos = [];

        if ($request->has('productos')) {
            $productos = json_decode($request->productos, true);
        }

        return view('vendedor.boleta', compact('usuarios', 'productos'));
    }

    public function generarBoleta(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'user_id' => 'required|exists:usuarios,id',
            'productos' => 'required|array|min:1',
        ]);


            $orden = Order::create([
                'user_id' => $request->user_id,
                'nombre_cliente' => $request->nombre_cliente,
                'direccion' => 'tienda',
                'estado' => 'entregado',
                'productos' => json_encode($request['productos']),
            ]);

        $productosDecodificados = [];
        foreach ($request->productos as $productoJson) {
            $producto = json_decode($productoJson, true);
            if (isset($producto['nombre'], $producto['precio'], $producto['cantidad'])) {
                $productosDecodificados[] = $producto;
            }
        }

        $fecha = Carbon::now()->toDateString();
        $boleta = Boleta::create([
            'user_id' => $request->user_id,
            'order_id' => $orden->id,
            'numero' => 'storage/public/boletas/boleta_' . $orden->id .'.pdf',
            'tipo' => 'boleta',
            'fecha' => $fecha,
        ]);

        // Crear ventas (aquí es donde agregamos lo que me pediste 💋)
        foreach ($productosDecodificados as $producto) {
            $ventaExistente = Sale::where('producto_id', $producto['id'])
                ->whereDate('fecha_venta', Carbon::today())
                ->first();

            if ($ventaExistente) {
                // Ya hay venta de este producto hoy, sumamos la cantidad
                $ventaExistente->cantidad += $producto['cantidad'];
                $ventaExistente->save();
            } else {
                // No hay venta hoy de este producto, creamos nuevo registro
                Sale::create([
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'fecha_venta' => Carbon::today(), // 💥 aquí la magia
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $vendedor = User::find($request->user_id);

        $pdf = Pdf::loadView('boletas.pdf', [
            'boleta' => $boleta,
            'cliente' => $request->nombre_cliente,
            'vendedor' => $vendedor,
            'productos' => $productosDecodificados,
            'fecha' => $fecha,
        ]);

        Storage::put('public/boletas/boleta_' . $orden->id . '.pdf', $pdf->output());

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'boleta_' . $orden->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
