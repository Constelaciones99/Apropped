@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4 animate__animated animate__fadeInDown"><i class="fa-solid fa-flask"></i> Comparar Imagen con Productos <i class="fa-solid fa-microscope"></i></h2>

    <!-- Formulario -->
    <form id="uploadForm" enctype="multipart/form-data" method="post" class="card p-4 shadow-sm rounded-4 animate__animated animate__fadeIn">
        <div class="mb-3">
            <label for="imageInput" class="form-label fw-bold">
                <i class="fas fa-camera me-2"></i>Sube tu foto
            </label>
            <input type="file" name="image" id="imageInput" class="form-control form-control-sm" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search me-2"></i>Buscar Similares
        </button>
    </form>

    <!-- Loader -->
    <div id="loader" class="text-center my-5 d-none animate__animated animate__fadeIn">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Buscando...</span>
        </div>
        <p class="mt-3 text-muted">Buscando productos similares, un momento... ⏳</p>
    </div>

    <!-- Resultados -->
    <div id="resultSection" class="d-none mt-5 animate__animated animate__fadeInUp">
        <h4 class="text-success mb-4">
            <i class="fas fa-check-circle me-2"></i>Resultado más parecido
        </h4>
        <div class="card shadow-sm p-3 mb-5 rounded-4 text-center">
            <img id="bestImage" src="" class="img-thumbnail rounded mb-3" style="max-width: 200px;">
            <p class="fw-bold">Similitud: <span id="bestScore" class="text-primary"></span>%</p>
            <!-- Aquí puedes agregar un botón de ir al producto si quieres -->
        </div>

        <h5 class="text-muted mb-3">
            <i class="fas fa-images me-2"></i>Otras coincidencias
        </h5>
        <div id="others" class="row g-3"></div>
    </div>
</div>

<script>
document.getElementById("uploadForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);

  document.getElementById("loader").classList.remove("d-none");
  document.getElementById("resultSection").classList.add("d-none");

  try {
    const res = await fetch("https://localhost:5000/upload", {
      method: "POST",
      body: formData,
    });

    const data = await res.json();

    if (!data.best_match) {
      document.getElementById("loader").classList.add("d-none");
      alert("No se encontraron coincidencias o ocurrió un error.");
      return;
    }

    document.getElementById("loader").classList.add("d-none");
    document.getElementById("resultSection").classList.remove("d-none");

    // Mostrar mejor coincidencia
    document.getElementById("bestImage").src = `/storage/productos/${data.best_match.filename}`;
    document.getElementById("bestScore").textContent = data.best_match.similarity;

    // Mostrar otras coincidencias
    const othersContainer = document.getElementById("others");
    othersContainer.innerHTML = "";
    data.others.forEach(img => {
      othersContainer.innerHTML += `
        <div class="col-md-3">
          <div class="card shadow-sm">
            <img src="/storage/productos/${img.filename}" class="card-img-top" alt="Similar">
            <div class="card-body text-center p-2">
              <p class="mb-0 fw-medium">${img.similarity}% de similitud</p>
            </div>
          </div>
        </div>`;
    });

    // 🔁 MOSTRAR PRODUCTO EN OTRA VISTA
    const nombreImagen = data.best_match.filename.replace("productos/", "");

    const formRedirect = document.createElement("form");
    formRedirect.method = "POST";
    formRedirect.action = "/tensor/producto";

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "imagen";
    input.value = nombreImagen;

    const token = document.createElement("input");
    token.type = "hidden";
    token.name = "_token";
    token.value = "{{ csrf_token() }}";

    formRedirect.appendChild(input);
    formRedirect.appendChild(token);
    document.body.appendChild(formRedirect);
    formRedirect.submit();

    // Preparar datos para redireccionar a la vista con todos los productos similares
        const otrosFiltrados = data.others.slice(0, 4).map(img => ({
        filename: img.filename,
        similarity: img.similarity
        }));

        const fullForm = document.createElement("form");
        fullForm.method = "POST";
        fullForm.action = "/tensor/producto";

        const mainInput = document.createElement("input");
        mainInput.type = "hidden";
        mainInput.name = "imagen";
        mainInput.value = nombreImagen;



        // Añadir imagenes similares como JSON
        const similaresInput = document.createElement("input");
        similaresInput.type = "hidden";
        similaresInput.name = "similares";
        similaresInput.value = JSON.stringify(otrosFiltrados);

        fullForm.appendChild(mainInput);
        fullForm.appendChild(similaresInput);
        fullForm.appendChild(token);
        document.body.appendChild(fullForm);
        fullForm.submit();

  } catch (error) {
    document.getElementById("loader").classList.add("d-none");
    alert("Error al enviar la imagen.");
    console.error(error);
  }
});
</script>


@endsection
