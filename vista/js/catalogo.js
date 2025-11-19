document.addEventListener("DOMContentLoaded", () => {
  let $containerCards = document.getElementById("catalogoProductos");

  // Cargar productos al iniciar
  fetch("../../ajax/productoAjax.php?accion=listar")
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

  function dibujarCatalogo($datos) {
    let $dibujado = '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';

    // Obtener el rol actual desde el header (1: Admin, 2: Cliente)
    // Usamos el mismo atributo data-rol que ya existe en el header
    const menuElement = document.getElementById("ulMenu");
    const rolActivo = menuElement ? parseInt(menuElement.dataset.rol) : 0;

    $datos.forEach((element) => {

      // LÓGICA: Definir si mostramos el botón
      let botonAgregarHTML = "";

      // Solo si es Cliente (2) generamos el HTML del botón
      if (rolActivo === 2) {
        botonAgregarHTML = `
            <div class="mt-auto d-flex justify-content-end">
              <button class="btn btn-success btn-sm btnCarritoAdd" 
                      data-id="${element.idproducto}" 
                      title="Agregar al Carrito">
                <i class="bi bi-cart-plus"></i> Agregar
              </button>
            </div>`;
      }

      $dibujado += `
      <div class="col">
        <div class="card h-100 shadow-sm">
           <img src="/tpfinaldinamica/util/imagenesProductos/${element.idproducto}.${element.extension}?v=${Date.now()}"
               class="card-img-top"
               alt="Imagen del producto"
               style="height: 200px; object-fit: cover;"
               onerror="this.src='/tpfinaldinamica/util/imagenesProductos/default.png';">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${element.pronombre}</h5>
            <p class="card-text">${element.prodetalle}</p>
            <p class="card-text text-muted"><small>Stock: ${element.procantstock}</small></p>
            
            ${botonAgregarHTML}
            
          </div>
        </div>
      </div>
    `;
    });
    $dibujado += "</div>";
    return $dibujado;
  }

  // Evento Delegado para detectar clic en los botones "Agregar"
  document.addEventListener("click", (e) => {
    const btnAgregar = e.target.closest(".btnCarritoAdd");

    if (btnAgregar) {
      const idProducto = btnAgregar.dataset.id;
      agregarAlCarrito(idProducto);
    }
  });

  // Función AJAX para agregar al carrito
  function agregarAlCarrito(idProducto) {
    const formData = new FormData();
    formData.append("accion", "agregar");
    formData.append("idproducto", idProducto);
    formData.append("cantidad", 1);

    fetch("../../ajax/carritoAjax.php", {
      method: "POST",
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.exito) {
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: '¡Producto agregado!',
            showConfirmButton: false,
            timer: 1500,
            toast: true
          });
          // Disparamos el evento para que el header se actualice
          document.dispatchEvent(new CustomEvent('cartUpdated'));
        } else {

          Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: data.msg || 'No se pudo agregar el producto'
          });

          if (data.msg === 'Debe iniciar sesión') {
            setTimeout(() => {
              window.location.href = '../login.php';
            }, 1500);
          }
        }
      })
      .catch(error => {
        console.error("Error:", error);
        Swal.fire("Error", "No se pudo comunicar con el servidor", "error");
      });
  }

  // Función auxiliar para recargar (si se usara externamente)
  function actualizarTabla() {
    // Reutiliza la lógica del fetch inicial si fuera necesario refrescar sin F5
    // Por ahora el código original la tenía pero no la llamaba.
  }
});