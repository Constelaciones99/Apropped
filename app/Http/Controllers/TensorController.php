<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class TensorController extends Controller
{
    public function redireccionarProducto(Request $request)
    {
        $nombreImagen = $request->input('imagen');
        $similaresJSON = $request->input('similares');
        $similares = json_decode($similaresJSON, true);

        $imagen = Image::where('ruta', 'like', "%$nombreImagen")->first();
        if (!$imagen) return back()->with('error', 'Imagen no encontrada.');

        $producto = Product::find($imagen->producto_id);
        if (!$producto) return back()->with('error', 'Producto no encontrado.');

        $imagenPrincipal = Image::where('producto_id', $producto->id)->where('es_principal', 1)->first();

        // Mapear los 4 productos similares
        $productosSimilares = collect($similares)->map(function ($sim) {
            $imagen = Image::where('ruta', 'like', "%{$sim['filename']}")->first();
            if (!$imagen) return null;

            $producto = Product::find($imagen->producto_id);
            if (!$producto) return null;

            $imagenPrincipal = Image::where('producto_id', $producto->id)->where('es_principal', 1)->first();

            return [
                'producto' => $producto,
                'imagen' => $imagenPrincipal,
                'similitud' => $sim['similarity'],
            ];
        })->filter(); // quitar nulos

        return view('prediccion.producto', compact('producto', 'imagenPrincipal', 'productosSimilares'));
    }

    public function verDetalleProducto(Request $request)
    {
        $producto = Product::findOrFail($request->producto_id);

        $usuario = Auth::User();

        if ($usuario && $usuario->rol === 'cliente') {
            // Redirigir a la vista cliente con query param
            return redirect('/cliente?nombre=' . urlencode($producto->nombre));
        }

        if ($usuario && $usuario->rol === 'vendedor') {
            // Agregar el producto a la sesión (sin duplicados)
            $productos = session()->get('productos_vendedor', []);

            // Evita duplicados
            if (!in_array($producto->nombre, $productos)) {
                $productos[] = $producto->nombre;
            }

            session()->put('productos_vendedor', $productos);
            return redirect('/vender-prod?nombre='.urlencode($producto->nombre));
        }else{
            dd("hubo un error");
        }

        return redirect()->back()->with('error', 'No autorizado');
    }

}
