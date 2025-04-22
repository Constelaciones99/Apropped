<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Mostrar todos los productos
    public function index(Request $request)
    {
        $query = Product::with(['imagenPrincipal', 'categoria']);

        // Búsqueda por nombre
        if ($request->filled('busqueda')) {
            $query->where('nombre', 'like', '%' . $request->busqueda . '%');
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria); //  ¡Este era el error!
        }

        // Orden
        switch ($request->orden) {
            case 'aleatorio':
                $query->inRandomOrder();
                break;
            case 'recientes':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest(); // por defecto
                break;
        }

        $productos = $query->paginate(9)->appends($request->all()); // Conserva filtros en la paginación
        $categorias = Category::all(); // Asegúrate de tener esto para el select

        return view('products.index', compact('productos', 'categorias'));
    }

    // Mostrar un solo producto products.index
    public function show($id)
    {
        $producto = Product::with(['imagenes', 'categoria'])->findOrFail($id);
        return view('products.show', compact('producto'));
    }

    // Editar un producto
    public function edit($id)
    {
        $producto = Product::with('imagenes')->findOrFail($id);
        $categorias = Category::all();
        return view('products.edit', compact('producto', 'categorias'));
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:27',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

        ]);

        // Convertir el nombre del producto a la primera letra en mayúscula
        $nombre = ucfirst(strtolower($request->nombre));
        $producto = Product::create($request->all());

        // Verificar si se subió una imagen principal
        if ($request->hasFile('imagen_principal')) {
            $path = $request->file('imagen_principal')->store('productos', 'public');
            $producto->imagenes()->create([
                'ruta' => $path,
                'es_principal' => true,
            ]);
        } elseif ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('productos', 'public');
                $producto->imagenes()->create([
                    'ruta' => $path,
                    'es_principal' => $index === 0, // solo la primera de estas
                ]);
            }
        } else {
            // Ninguna imagen subida, usar imagen por defecto
            $producto->imagenes()->create([
                'ruta' => 'productos/no-image.png',
                'es_principal' => true,
            ]);
        }

        return redirect()->route('admin.index');
    }


    // Actualizar un producto existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:27',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagenes_adicionales.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $producto = Product::findOrFail($id);
        $producto->update($request->only(['nombre', 'descripcion', 'precio', 'stock', 'categoria_id']));

        // 🔄 Reemplazar imagen principal si se subió una nueva
        if ($request->hasFile('imagen_principal')) {
            // Eliminar imagen principal anterior (solo si no es la por defecto)
            $anterior = $producto->imagenes()->where('es_principal', true)->first();
            if ($anterior && $anterior->ruta !== 'productos/no-image.png') {
                Storage::delete('public/' . $anterior->ruta);
                $anterior->delete();
            }

            // 🧼 También eliminar la imagen por defecto si es la única
            if ($producto->imagenes()->count() === 1) {
                $imgDefecto = $producto->imagenes()->where('ruta', 'productos/no-image.png')->first();
                if ($imgDefecto) {
                    $imgDefecto->delete();
                }
            }

            $path = $request->file('imagen_principal')->store('productos', 'public');
            $producto->imagenes()->create([
                'ruta' => $path,
                'es_principal' => true,
            ]);
        }

        // 📷 Agregar imágenes adicionales
        // Si se suben nuevas imágenes
        if ($request->hasFile('imagenes')) {
            // 🧼 Eliminar la imagen por defecto si existe y si es la única
            if ($producto->imagenes()->count() === 1) {
                $imgDefecto = $producto->imagenes()->where('ruta', 'productos/no-image.png')->first();
                if ($imgDefecto) {
                    $imgDefecto->delete(); // no hay que borrar físicamente porque es común para todos
                }
            }

            // 📥 Agregar nuevas imágenes
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('productos', 'public');
                $producto->imagenes()->create([
                    'ruta' => $path,
                    'es_principal' => false,
                ]);
            }
        }

        return redirect()->route('admin.index')->with('success', 'Producto actualizado correctamente');
    }

    // Eliminar un producto products.index
    public function destroy($id)
    {
        $producto = Product::findOrFail($id);
        $producto->delete();

        return redirect()->route('admin.index');
    }

    public function create()
    {
        $categorias = Category::all();
        return view('products.create', compact('categorias'));
    }

    public function deleteImage($productId, $imageId)
    {
        $producto = Product::findOrFail($productId);
        $imagen = $producto->imagenes()->findOrFail($imageId);

        // Evitar que eliminen la última imagen
        if ($producto->imagenes()->count() <= 1) {
            return back()->with('error', 'No se puede eliminar la única imagen del producto.');
        }

        // Evitar eliminar imagen principal directamente (opcional)
        if ($imagen->es_principal) {
            return back()->with('error', 'No puedes eliminar la imagen principal. Cámbiala primero.');
        }

        Storage::delete('public/' . $imagen->ruta);
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif, webp|max:2048',
        ]);

        $producto = Product::findOrFail($id);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $path = $file->store('productos', 'public');

            Image::create([
                'producto_id' => $producto->id,
                'ruta' => $path,
            ]);
        }

        return redirect()->route('products.show', $producto->id)->with('success', 'Imagen subida correctamente.');
    }

    public function setPrincipal($productId, $imageId)
    {
        $producto = Product::findOrFail($productId);
        $imagenNueva = $producto->imagenes()->findOrFail($imageId);

        // Desmarcar actual principal
        $producto->imagenes()->update(['es_principal' => false]);

        // Marcar nueva principal
        $imagenNueva->update(['es_principal' => true]);

        return back()->with('success', 'Imagen establecida como principal.');
    }


}
