@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Tu Carrito</h2>

    @if(session('carrito') && count(session('carrito')) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('carrito') as $id => $producto)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $producto['imagen']) }}" alt="{{ $producto['nombre'] }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                            {{ $producto['nombre'] }}
                        </td>

                        <td>

    <input type="number"
           name="cantidad"
           value="{{ $producto['cantidad'] }}"
           min="1"
           class="form-control form-control-sm cantidad-input"
           data-id="{{ $id }}"
           data-precio="{{ $producto['precio'] }}"
           style="width: 70px;">
                            </td>
                        </td>
                        <td>S/. {{ number_format($producto['precio'], 2) }}</td>
                        <td>S/. <span class="total-producto" data-id="{{ $id }}">{{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</span></td>

                        <td>
                            <form action="{{ route('carrito.eliminar', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Quitar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Subtotal-->
        @php
            $subtotal = array_sum(array_map(function($producto) {
                return $producto['precio'] * $producto['cantidad'];
            }, session('carrito')));
        @endphp
        <div class="d-flex justify-content-end mb-3">
            <h4>Subtotal: S/. <span id="subtotal-carrito">{{ number_format($subtotal, 2) }}</span></h4>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('home') }}" class="btn btn-secondary">Seguir Comprando</a>

            <div>
                <form action="{{ route('carrito.vaciar') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger">Vaciar Carrito</button>
                </form>

                <a href="{{ route('carrito.ordenar') }}" class="btn btn-warning">Ordenar Ahora</a>
            </div>
        </div>

    @else
        <p>No tienes productos en tu carrito.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Ir a la tienda</a>
    @endif
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputsCantidad = document.querySelectorAll(".cantidad-input");

        inputsCantidad.forEach(input => {
            input.addEventListener("change", function () {
                const id = this.dataset.id;
                const precio = parseFloat(this.dataset.precio);
                const nuevaCantidad = parseInt(this.value) || 1;

                if (nuevaCantidad < 1) {
                    this.value = 1;
                    return;
                }

                // Actualiza el total por producto
                const totalProducto = document.querySelector(`.total-producto[data-id="${id}"]`);
                const nuevoTotal = (nuevaCantidad * precio).toFixed(2);
                totalProducto.textContent = nuevoTotal;

                // Recalcula el subtotal
                recalcularSubtotal();

                // También actualiza en sesión con fetch (AJAX)
                fetch(`/carrito/actualizar/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cantidad: nuevaCantidad })
                }).then(response => {
                    if (!response.ok) {
                        alert('Error actualizando carrito');
                    }
                });
            });
        });

        function recalcularSubtotal() {
            let subtotal = 0;
            document.querySelectorAll(".cantidad-input").forEach(input => {
                const precio = parseFloat(input.dataset.precio);
                const cantidad = parseInt(input.value) || 1;
                subtotal += precio * cantidad;
            });
            document.getElementById("subtotal-carrito").textContent = subtotal.toFixed(2);
        }
    });
</script>

@endsection
