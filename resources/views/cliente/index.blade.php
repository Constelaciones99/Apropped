@extends('layouts.app')

@section('content')
<h1 class="mt-2">APROPPED</h1>
<hr>
<div class="container mt-5 pb-2">

    <!-- Información h2 del  usuario en la parte superior derecha -->
    <div class="d-flex justify-content-between">
        @if(auth()->check())
            <div>
                <p><strong>Nombre:</strong> {{ auth()->user()->nombre }}</p>
                <p><strong>Celular:</strong> {{ auth()->user()->celular }}</p>
                <p><strong>Estado:</strong>
    <span class="align-middle">
         Sesión Activa  <i class="fas fa-circle text-success me-1" style="font-size: 0.7rem;"></i>
    </span>
</p>
            </div>
            <div>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                    <i class="fas fa-user-edit me-1"></i> Editar
                </button>
                <div class="mt-3 text-end">
                    <a href="{{ route('carrito.ver') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-1"></i> Carrito
                    </a>
                </div>
                <div class="mt-3 text-end">
                <a href="{{ route('boletas.cliente') }}" class="btn btn-dark">
                    <i class="fa-solid fa-file-lines"></i> Boletas
                </a>
                </div>
            </div>
        @else
        <div>
            <p>Registrate para realizar compras.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistro">Registrarse</button>
        </div>
        @endif

        {{-- MODAL PARA REGISTRAR CLIENTE --}}

<!-- Modal de Registro -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('cliente.registrar') }}" id="formRegistro">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistroLabel">Registro de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    {{-- Alerta (se mostrará desde JavaScript si hay éxito) --}}
                    <div id="alertRegistro" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                        Registro exitoso. ¡Bienvenido!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>

                    <div class="mb-3">
                        <label for="nombre">Nombres Completos</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="celular">Celular (9 dígitos)</label>
                        <input type="text" name="celular" class="form-control" pattern="[0-9]{9}" maxlength="9" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrarse</button>
                </div>
            </div>
        </form>
    </div>
</div>

    </div>

    <hr>

</div>

<!-- Modal de edición de usuario editar img-->
@if(auth()->check())
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{-- EDITAR PERFIL --}}
        <form method="POST" action="{{ route('usuario.actualizar') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ auth()->user()->nombre }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="{{ auth()->user()->username }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Celular</label>
                        <input type="text" name="celular" class="form-control" value="{{ auth()->user()->celular }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ auth()->user()->direccion }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Password (si deseas cambiarlo)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Editar Usuario</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif





{{-- TIENDA DE PRODUCTOS CON IMAGENES Y PRECIO nombreInput--}}
<div class="container py-0">
    <h2 class="mb-4"><i class="fas fa-store"></i> Productos disponibles</h2>
    <form id="form-filtros" method="GET" action="{{ route('home') }}" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label for="nombre" class="form-label">Buscar por nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" class="form-control" placeholder="Ej. Zapatos">
        </div>
        <div class="col-md-4">
            <label for="categoria_id" class="form-label">Filtrar por categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-select">
                <option value="">-- Todas --</option>

            </select>
        </div>
        <div class="col-md-4">
            <label for="orden_precio" class="form-label">Ordenar por precio</label>
            <select name="orden_precio" id="orden_precio" class="form-select" {{ request('nombre') || request('categoria_id') ? '' : 'disabled' }}>
                <option value="">-- No ordenar --</option>
                <option value="asc" {{ request('orden_precio') == 'asc' ? 'selected' : '' }}>De menor a mayor</option>
                <option value="desc" {{ request('orden_precio') == 'desc' ? 'selected' : '' }}>De mayor a menor</option>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-success mt-2">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('home') }}" class="btn btn-secondary mt-2">Limpiar</a>
        </div>
    </div>
    <div class="d-flex justify-content-center">
                {{ $productos->links() }}
            </div>
</form>
    <div id="contenedor-productos">
                    @include('partials._productos', ['productos' => $productos])
                <!-- Links de paginación -->

    </div>
    <hr>

</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 🟩 FILTROS DE PRODUCTOS
    const filtroForm = document.getElementById('form-filtros');
    const contenedor = document.getElementById('contenedor-productos');
    const nombreInput = document.getElementById('nombre');
    const categoriaSelect = document.getElementById('categoria_id');
    const ordenSelect = document.getElementById('orden_precio');

    function toggleOrdenPrecio() {
        if (nombreInput.value.trim() || categoriaSelect.value) {
            ordenSelect.disabled = false;
        } else {
            ordenSelect.disabled = true;
            ordenSelect.value = '';
        }
    }

    toggleOrdenPrecio();

    nombreInput.addEventListener('input', () => {
        toggleOrdenPrecio();
        filtrarProductos();
    });

    categoriaSelect.addEventListener('change', () => {
        toggleOrdenPrecio();
        filtrarProductos();
    });

    ordenSelect.addEventListener('change', filtrarProductos);

    function filtrarProductos() {
        const params = new URLSearchParams(new FormData(filtroForm)).toString();

        fetch(`{{ route('productos.filtrar.ajax') }}?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(res => res.json())
        .then(data => {
            contenedor.innerHTML = data.html;
        })
        .catch(err => console.error('Error al filtrar:', err));
    }

    // 🟨 REGISTRO DE USUARIO
    const registroForm = document.getElementById('formRegistro');

    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const data = new FormData(registroForm);

            fetch(registroForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: data
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(response => {
                if (response.success) {
                    const alert = document.getElementById('alertRegistro');
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        alert.classList.add('d-none');
                    }, 500);

                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistro'));
                    setTimeout(() => modal.hide(), 500);

                    registroForm.reset();
                    setTimeout(() => window.location.reload(), 600);
                }
            })
            .catch(async err => {
                let errorMsg = 'Error al registrar. Revisa los campos.';
                if (err.json) {
                    const jsonErr = await err.json();
                    if (jsonErr.message) errorMsg = jsonErr.message;
                }
                alert(errorMsg);
                console.error(err);
            });
        });
    }
});
</script>

