<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;


class TiendaController extends Controller
{
    // Mostrar todos los productos en la tienda
    public function index()
    {
        return view('prediccion.tensor');
    }
}
