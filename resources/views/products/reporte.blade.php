@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Reporte de Boletas</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre Cliente</th>
                <th>Boleta</th>
                <th>Fecha</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($boletas as $boleta)
                @php
                    $cliente = $boleta->order->nombre_cliente ?? 'Sin nombre';
                    // Normalizar nombre del archivo
                    $archivoPDF = Str::endsWith($boleta->numero, '.pdf')
                        ? $boleta->numero
                        : 'boleta_' . $boleta->id . '.pdf';

                    $rutaPDF = asset( $archivoPDF);
                @endphp
                <tr>
                    <td>{{ $cliente }}</td>
                    <td>{{ $archivoPDF }}</td>
                    <td>{{ $boleta->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBoleta{{ $boleta->id }}">
                            Ver
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="modalBoleta{{ $boleta->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $boleta->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel{{ $boleta->id }}">Boleta N° {{ $boleta->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <iframe src="{{ $rutaPDF }}" width="100%" height="600px" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
