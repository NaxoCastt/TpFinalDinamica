document.addEventListener("DOMContentLoaded", () => {
  let $containerCards = document.getElementById("catalogoProductos");

  fetch("../../ajax/productoAjax.php?accion=listar")
    .then((response) => response.json())
    .then(($productos) => {
      if ($productos.length === 0) {
        $containerCards.innerHTML = `<tr><td colspan="5" class="text-center">No hay productos para mostrar</td></tr>`;
        return;
      }
      $containerCards.innerHTML = dibujarCatalogo($productos);
    });

  function dibujarCatalogo($datos) {
    let $dibujado =
      '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
    $datos.forEach((element) => {
      $dibujado += `
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="/tpfinaldinamica/util/imagenesProductos/${
            element.idproducto
          }.${element.extension}?v=${Date.now()}"
               class="card-img-top"
               alt="Imagen del producto"
               style="height: 200px; object-fit: cover;"
               onerror="this.src='/tpfinaldinamica/util/imagenesProductos/default.png';">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${element.pronombre}</h5>
            <p class="card-text">${element.prodetalle}</p>
            <p class="card-text text-muted"><small>Disponibles: ${
              element.procantstock
            }</small></p>
            <div class="mt-auto d-flex justify-content-right">
              
              <button class="btn btn-danger btn-sm btnCarritoAdd" data-id="${
                element.idproducto
              }" title="Borrar">
                <i class="bi bi-cart-fill"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    `;
    });
    $dibujado += "</div>";
    return $dibujado;
  }

  function actualizarTabla() {
    fetch("../../ajax/productoAjax.php?accion=listar")
      .then((response) => response.json())
      .then(($productos) => {
        if ($productos.length === 0) {
          $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay productos para mostrar</td></tr>`;
          return;
        }
        $tabla.innerHTML = dibujarTabla($productos);
      })
      .catch((error) => {
        console.error("Error al cargar productos:", error);
        $tabla.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar productos</td></tr>`;
      });
  }
});
