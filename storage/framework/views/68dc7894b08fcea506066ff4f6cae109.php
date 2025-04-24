<?php $__env->startSection('content'); ?>
<div class="container py-4">
  <h2 class="text-center mb-4">🔍 Comparar Imagen con Productos</h2>

  <!-- Formulario -->
  <form id="uploadForm" enctype="multipart/form-data" method="post">
    <div class="mb-3">
      <input type="file" name="image" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">Buscar Similares</button>
  </form>

  <!-- Loader -->
  <div id="loader" class="text-center my-4 d-none">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Buscando...</span>
    </div>
    <p class="mt-2 text-muted">Comparando imagen, por favor espera...</p>
  </div>

  <!-- Resultados -->
  <div id="resultSection" class="d-none">
    <h4 class="mt-4 text-success">✅ Resultado más parecido</h4>
    <div class="mb-4">
      <img id="bestImage" src="" class="img-fluid rounded shadow mb-2" />
      <p class="fw-bold">Similitud: <span id="bestScore" class="text-primary"></span>%</p>
      

    </div>

    <h5>🔸 Otras coincidencias</h5>
    <div id="others" class="row g-4"></div>
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
    const res = await fetch("http://127.0.0.1:5000/upload", {
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

  } catch (error) {
    document.getElementById("loader").classList.add("d-none");
    alert("Error al enviar la imagen.");
    console.error(error);
  }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/prediccion/tensor.blade.php ENDPATH**/ ?>