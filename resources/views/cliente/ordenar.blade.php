@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Resumen del Pedido</h2>

    @if(count($carrito) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carrito as $producto)
                    <tr>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>{{ $producto['cantidad'] }}</td>
                        <td>S/. {{ number_format($producto['precio'], 2) }}</td>
                        <td>S/. {{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            <h4>Subtotal: S/. {{ number_format($subtotal, 2) }}</h4>
        </div>

        <div class="d-flex justify-content-between mt-4">

        <form action="{{ route('producto.ordenar.guardar', ['id' => 0]) }}" method="POST" id="formBoleta">
                @csrf
            <div class="mb-3">
                <label for="nombre_cliente" class="form-label">Nombre</label>
                <input type="text" name="nombre_cliente" class="form-control"
                    value="{{ auth()->user()->nombre }}" required>
                    <input type="hidden" name="vendedor" value="Tienda APROPPED">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control"
                    value="{{ auth()->user()->direccion }}" required>
            </div>
            <button type="submit" class="btn btn-success">Comprar</button>
            <a href="{{ route('carrito.ver') }}" class="btn btn-secondary">Cancelar</a>
        </form>

        </div>
    @else
        <p>No hay productos en el carrito.</p>
    @endif
</div>

    <script>
        document.getElementById('formBoleta').addEventListener('submit', function () {
            setTimeout(() => {
                window.location.href = '/cliente';
            }, 1000); // redirige después de abrir el PDF
        });
        </script>

@endsection
