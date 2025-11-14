document.addEventListener("DOMContentLoaded", () => {
  let $tabla = document.getElementById("tablaProductos");

  fetch("../../ajax/productoAjax.php?accion=listar")
    .then((response) => response.json())
    .then(($productos) => {
      if ($productos.length === 0) {
        $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay productos para mostrar</td></tr>`;
        return;
      }
      $tabla.innerHTML = dibujarTabla($productos);
    });

  function dibujarTabla($datos) {
    let $dibujado = "";
    $datos.forEach((element) => {
      $dibujado += `
        <tr>
        <td style="vertical-align: middle">${element.idproducto}</td>
        <td style="vertical-align: middle">${element.pronombre}</td>
        <td style="vertical-align: middle">${element.prodetalle}</td>
        <td style="vertical-align: middle">${element.procantstock}</td>
        <td class="d-flex justify-content-center gap-5">
            <button class="btn btn-warning btn-sm px-3 py-2 btnEditar" title="Editar" data-id="${element.idproducto}">
                <i class="bi bi-pen"></i>
            </button> 
            <button class="btn btn-danger btn-sm px-3 py-2 btnBorrar" data-id="${element.idproducto}" title="Borrar">
                <i class="bi bi-trash"></i>
            </button>
        </td>
        </tr>
        `;
    });

    return $dibujado;
  }

  //seccion de la parte crear

  const $modal = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("Modal")
  );
  const $crear = document.getElementById("agregarProductoBtn");

  const $botonCrear = document.getElementById("agregar");
  const $nombre = document.getElementById("nombre");
  const $detalle = document.getElementById("detalle");
  const $stock = document.getElementById("stock");
  const $form = document.getElementById("form");

  $botonCrear.addEventListener("click", () => {
    // Activar validación visual
    if (!$form.checkValidity()) {
      $form.classList.add("was-validated");
      return;
    }

    fetch("../../ajax/productoAjax.php?accion=alta", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        pronombre: $nombre.value.trim(),
        prodetalle: $detalle.value.trim(),
        procantstock: $stock.value.trim(),
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data === true || data === "true") {
          Swal.fire(
            "¡Producto creado!",
            "Se agregó correctamente al catálogo",
            "success"
          );
          $modal.hide();
          document.activeElement?.blur();

          $form.reset();
          $form.classList.remove("was-validated");
          actualizarTabla();
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo crear el producto",
            "error"
          );
        }
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión", "error");
      });
  });

  $crear.addEventListener("click", (e) => {
    e.currentTarget?.blur();

    $nombre.value = "";
    $detalle.value = "";
    $stock.value = "";
    $modal.show();
  });

  //Seccion parte borrar registro
  let $botonBorrar = null;
  const $modalCerrar = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("modalBorrar")
  );
  document.addEventListener("click", (e) => {
    const $botonBorrar = e.target.closest(".btnBorrar");
    if (!$botonBorrar) return;
    $idBorrar = $botonBorrar.dataset.id;

    $modalCerrar.show();
  });
  const $confirmacion = document.getElementById("botonBorrarConfirmacion");
  $confirmacion.addEventListener("click", () => {
    fetch("../../ajax/productoAjax.php?accion=baja", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id: $idBorrar,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data === true || $data === "true") {
          Swal.fire(
            "¡Producto eliminado!",
            "El producto se eliminó satisfactoriamente",
            "success"
          );
          $modalCerrar.hide();
          document.activeElement?.blur();

          actualizarTabla();
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo crear el producto",
            "error"
          );
        }
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión", "error");
      });
  });

  //Seccion para editar registro

  let $botonEditar = null;
  let $idEdicion = null;

  const $modalEditar = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("modalEditar")
  );
  document.addEventListener("click", (e) => {
    const $botonEditar = e.target.closest(".btnEditar");
    if (!$botonEditar) return;

    $idEdicion = $botonEditar.dataset.id;
    fetch("../../ajax/productoAjax.php?accion=buscar", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id: $idEdicion,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (Array.isArray(data) && data.length > 0) {
          let $nombreEdicion = document.getElementById("nombreEdicion");
          let $detalleEdicion = document.getElementById("detalleEdicion");
          let $stockEdicion = document.getElementById("stockEdicion");

          $nombreEdicion.value = data[0].pronombre;
          $detalleEdicion.value = data[0].prodetalle;
          $stockEdicion.value = data[0].procantstock;
        }
        $modalEditar.show();





//seccion de confirmacion de la edicion


        let $formEdicion = document.getElementById("formEdicion");
        const $botonConfirmacionEditar = document.getElementById("btnEditarConfirmacion");
        $botonConfirmacionEditar.addEventListener("click", () => {
          // Activar validación visual
          if (!$formEdicion.checkValidity()) {
            $formEdicion.classList.add("was-validated");
            return;
          }

          $nombreEdicion = document.getElementById("nombreEdicion");
          $detalleEdicion = document.getElementById("detalleEdicion");
          $stockEdicion = document.getElementById("stockEdicion");

          fetch("../../ajax/productoAjax.php?accion=editar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              idproducto: $idEdicion,
              pronombre: $nombreEdicion.value.trim(),
              prodetalle: $detalleEdicion.value.trim(),
              procantstock: $stockEdicion.value.trim(),
            }),
          })
            .then((response) => response.json())
            .then((data) => {
              if (data === true || data === "true") {
                Swal.fire(
                  "¡Producto Editado!",
                  "Se modificó correctamente al catálogo",
                  "success"
                );
                $modalEditar.hide();
                document.activeElement?.blur();

                actualizarTabla();
              } else {
                Swal.fire(
                  "Error",
                  data.message || "No se pudo editar el producto",
                  "error"
                );
              }
            })
            .catch((error) => {
              console.error("Error AJAX:", error);
              Swal.fire("Error", "Hubo un problema de conexión", "error");
            });
        });
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión", "error");
      });
  });

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
