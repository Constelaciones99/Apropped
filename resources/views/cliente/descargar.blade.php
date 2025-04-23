@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <h2>¡Gracias por tu compra!</h2>
    <p>Estamos descargando tu boleta... Serás redirigido automáticamente.</p>

    <p class="text-muted">Si la descarga no comienza, haz clic aquí:
        <a href="{{ asset('storage/boletas/boleta_' . $ordenId . '.pdf') }}" download>Descargar manualmente</a>
    </p>
</div>

<script>
    // Descargar PDF automáticamente
    window.onload = function () {
        const link = document.createElement('a');
        link.href = "{{ asset('storage/boletas/boleta_' . $ordenId . '.pdf') }}";
        link.download = "boleta_{{ $ordenId }}.pdf";
        link.click();

        // Redirigir a home después de unos segundos
        setTimeout(() => {
            window.location.href = "{{ route('home') }}";
        }, 4000); // 4 segundos
    };
</script>
@endsection
