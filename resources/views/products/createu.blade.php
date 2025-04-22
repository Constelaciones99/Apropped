@extends('layouts.app')

@section('content')

{{-- photo --}}
<div class="container mt-5">
    <h1 class="mb-4">Crear Vendedor</h1>
        @if ($errors->any())
    <div class="alert alert-danger">
        <h5><strong>Ups! Hubo algunos errores:</strong></h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('admin.usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username"  class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirmar Contraseña:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cel:</label>
            <input type="number" minlength="9" maxlength="9"  name="celular"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Direccion:</label>
            <input type="text" name="direccion"  class="form-control" required>
        </div>

        <!-- Input oculto para rol -->
        <input type="hidden" name="rol" value="vendedor">

        <button class="btn btn-secondary text-white">
            <a href="{{ route('admin.usuarios.index') }}" class="text-white"/><i class="fa-solid fa-arrow-left"></i> Cancelar
        </button>

        <button class="btn btn-success">
            <i class="fas fa-check"></i> Guardar
        </button>

    </form>
</div>
@endsection
