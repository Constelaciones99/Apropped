@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Mis Órdenes</h1>

    @if($orders->isEmpty())
        <p>No tienes órdenes aún.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->producto->nombre }}</td>
                        <td>{{ $order->cantidad }}</td>
                        <td>{{ $order->estado }}</td>
                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                        <td>
                            <!-- Aquí puedes agregar botones para editar o eliminar la orden -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
