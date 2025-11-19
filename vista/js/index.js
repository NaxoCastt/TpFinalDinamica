document.addEventListener("DOMContentLoaded", () => {
  let $containerCards = document.getElementById("catalogoProductos");
  let $containerCards2 = document.getElementById("catalogoProductosMasVendidos");

  // Cargar productos al iniciar
  fetch("../ajax/productoAjax.php?accion=listarUltimos")
    .then((response) => response.json())
    .then(($productos) => {
      if ($productos.length === 0) {
        $containerCards.innerHTML = `<div class="col-12"><p class="text-center">No hay productos para mostrar</p></div>`;
        return;
      }
      $containerCards.innerHTML = dibujarCatalogo($productos);
    })
    .catch((error) => {
        console.error("Error al cargar catálogo:", error);
        $containerCards.innerHTML = `<div class="col-12"><p class="text-center text-danger">Error de conexión</p></div>`;
    });

    //cargar los mas vendidos(menos stock)

    fetch("../ajax/productoAjax.php?accion=listarMasVendidos")
    .then((response) => response.json())
    .then(($productos) => {
      if ($productos.length === 0) {
        $containerCards2.innerHTML = `<div class="col-12"><p class="text-center">No hay productos para mostrar</p></div>`;
        return;
      }
      $containerCards2.innerHTML = dibujarCatalogo($productos);
    })
    .catch((error) => {
        console.error("Error al cargar catálogo:", error);
        $containerCards2.innerHTML = `<div class="col-12"><p class="text-center text-danger">Error de conexión</p></div>`;
    });

  //Función para dibujar las tarjetas
  function dibujarCatalogo($datos) {
    let $dibujado =
      '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
    
    $datos.forEach((element) => {
      $dibujado += `
      <div class="col-12 col-md-6 col-lg-4 mb-4">
  <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #f0f0f0, #dcdcdc);">
    <img
      src="/tpfinaldinamica/util/imagenesProductos/${element.idproducto}.${element.extension}?v=${Date.now()}"
      class="card-img-top"
      alt="Imagen del producto"
      style="height: 260px; object-fit: cover; border-bottom: 4px solid #8ec5fc;"
      onerror="this.src='/tpfinaldinamica/util/imagenesProductos/default.png';"
    >
    <div class="card-body d-flex flex-column justify-content-between p-4">
      <div>
        <h4 class="card-title fw-bold text-primary-emphasis">${element.pronombre}</h4>
        <p class="card-text text-dark-emphasis">${element.prodetalle}</p>
      </div>
      <div class="mt-3">
        <p class="card-text text-muted mb-1"><i class="bi bi-box-seam"></i> <small>Stock: ${element.procantstock}</small></p>
        <a href="../vista/Cliente/productos.php"><button class="btn btn-outline-primary w-100 mt-2">Ver más</button></a>
      </div>
    </div>
  </div>
</div>
    `;
    });
    $dibujado += "</div>";
    return $dibujado;
  }})