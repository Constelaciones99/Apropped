@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-file-invoice"></i> Mis Boletas</h2>

    @if($boletas->isEmpty())
        <div class="alert alert-info">
            <i class="fa-solid fa-circle-info"></i> No tienes boletas registradas aún.
        </div>
    @else
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Boleta</th>
                <th>Fecha</th>
                <th>Dirección</th>
                <th>Estado Pedido</th>
                <th>Ver Boleta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boletas as $boleta)
                <tr>
                    <td>Boleta #{{ $boleta->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($boleta->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $boleta->order->direccion ?? 'No registrada' }}</td>
                    <td>{{ $boleta->order->estado ?? 'Sin estado' }}</td>
                    <td>
                        <a href="{{ asset($boleta->numero) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-file-pdf"></i> Ver PDF
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Links de paginación -->
    <div class="d-flex justify-content-center">
        {{ $boletas->links('vendor.pagination.bootstrap-5') }}
    </div>
    @endif
</div>
<button class="btn btn-dark"><a href="{{ route('home') }}" class="text-white fw-bold"><i class="fa-solid fa-left-long"></i> Volver</a></button>
@endsection
