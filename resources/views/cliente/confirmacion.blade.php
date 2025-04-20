@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <h2 class="text-success mb-4"><i class="fas fa-check-circle"></i> ¡Pedido realizado con éxito!</h2>
    <p>Gracias por tu compra, <strong>{{ $nombre }}</strong>. Pronto estaremos en contacto contigo para coordinar el envío.</p>

    <a href="{{ route('home') }}" class="btn btn-primary mt-4">
        <i class="fas fa-home me-1"></i> Volver al inicio
    </a>
</div>
@endsection
