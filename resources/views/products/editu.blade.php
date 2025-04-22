@extends('layouts.app')
{{-- photo --}}
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Editar Vendedor</h1>

    <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ $user->nombre }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cel:</label>
            <input type="number" minlength="9" maxlength="9"  name="celular" value="{{ $user->celular }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Direccion:</label>
            <input type="text" name="direccion" value="{{ $user->direccion }}" class="form-control" required>
        </div>

        <button class="btn btn-primary" type="submit">
            <i class="fas fa-save"></i> Actualizar
        </button>
    </form>
</div>
@endsection
