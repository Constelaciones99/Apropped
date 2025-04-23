@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- <h3><button class="btn btn-dark"><a href="{{ route('admin.index') }}" class="text-white fw-bold"><i class="fa-solid fa-hand-point-left"></i> Back</a></button></h3> --}}
    <h2 class="mb-4"><i class="fa-solid fa-clipboard-list"></i> Reporte de Boletas</h2>

    <!-- Buscador con íconos FontAwesome 😍 resultados-->
    <form action="{{ route('boletas.buscar') }}" method="GET" class="d-flex mb-4 gap-2 align-items-center">
        <input type="text" name="query" class="form-control" placeholder="🔎 Buscar por boleta, cliente u orden..." value="{{ request('query') }}">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </form>

    @if(isset($busqueda))
        <div class="mb-3">
            <span class="text-muted">
                <i class="fa-solid fa-filter-circle-xmark"></i> Resultados para: <strong>"{{ $busqueda }}"</strong>
            </span>
        </div>
    @endif

    <table class="table table-bordered table-hover shadow-sm rounded-3 overflow-hidden">
        <thead class="table-dark">
            <tr>
                <th><i class="fa-solid fa-user"></i> Nombre Cliente</th>
                <th><i class="fa-solid fa-file-invoice"></i> Boleta</th>
                <th><i class="fa-solid fa-calendar-days"></i> Fecha</th>
                <th><i class="fa-solid fa-eye"></i> Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($boletas as $boleta)
                @php
                    $cliente = $boleta->nombre_cliente ?? ($boleta->order->nombre_cliente ?? 'Sin nombre');
                    $archivoPDF = Str::endsWith($boleta->id, '.pdf')
                        ? $boleta->numero
                        : 'Boleta_' . $boleta->id;
                    $rutaPDF = asset($boleta->numero);
                @endphp
                <tr>
                    <td>{{ $cliente }}</td>
                    <td>{{ $archivoPDF }}</td>
                    <td>{{ \Carbon\Carbon::parse($boleta->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalBoleta{{ $boleta->id }}">
                            <i class="fa-solid fa-file-pdf"></i> PDF
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
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="fa-solid fa-circle-info"></i> No se encontraron boletas para esta búsqueda.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
    {{ $boletas->appends(['query' => request('query')])->links('vendor.pagination.bootstrap-5') }}
</div>
</div>
@endsection
