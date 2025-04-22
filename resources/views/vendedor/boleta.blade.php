@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">Generar Boleta</h2>

    <form id="formBoleta" action="{{ route('boleta.generar') }}" method="POST" target="_blank">
        @csrf

        <div class="mb-3">
            <label for="cliente" class="form-label">Nombre del Cliente</label>
            <input type="text" name="nombre_cliente" id="cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="vendedor" class="form-label">Seleccionar Vendedor</label>
            <select name="user_id" id="vendedor" class="form-select" required>
                <option value="">-- Seleccione --</option>
                @foreach($usuarios as $usuario)
                    @if($usuario->rol == 'vendedor')
                        <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <h5 class="mt-4">Productos en el carrito</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach($productos as $producto)
                    @php $total = $producto['precio'] * $producto['cantidad']; $subtotal += $total; @endphp
                    <tr>
                        <td>{{ $producto['id'] }}</td>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>S/. {{ number_format($producto['precio'], 2) }}</td>
                        <td>{{ $producto['cantidad'] }}</td>
                        <td>S/. {{ number_format($total, 2) }}</td>
                    </tr>
                    <input type="hidden" name="productos[]" value='@json($producto)'>
                @endforeach
            </tbody>
        </table>

        <div class="text-end">
            <h5>Subtotal: S/. {{ number_format($subtotal, 2) }}</h5>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('vendedor.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Aceptar y Generar Boleta</button>
        </div>
    </form>
</div>

    <script>
        document.getElementById('formBoleta').addEventListener('submit', function () {
            setTimeout(() => {
                window.location.href = '/vender';
            }, 1000); // redirige después de abrir el PDF
        });

    </script>
@endsection
