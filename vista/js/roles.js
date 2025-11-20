document.addEventListener("DOMContentLoaded", () => {
  let $tabla = document.getElementById("tablaProductos");

  fetch("../../ajax/rolesAjax.php?accion=listar")
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
        <td style="vertical-align: middle">${element.idrol}</td>
        <td style="vertical-align: middle">${element.rodescripcion}</td>
        <td style="vertical-align: middle">
  <div class="d-flex justify-content-center gap-3">
    <button class="btn btn-warning btn-sm px-3 py-2 btnEditar" title="Editar" data-idrol="${element.idrol}">
      <i class="bi bi-pen"></i>
    </button> 
    <button class="btn btn-danger btn-sm px-3 py-2 btnBorrar" data-idrol="${element.idrol}" 
 title="Borrar">
      <i class="bi bi-trash"></i>
    </button>
  </div>
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
  const $nombre = document.getElementById("rodescripcion");
  const $form = document.getElementById("form");

  $botonCrear.addEventListener("click", () => {
    // Activar validación visual
    if (!$form.checkValidity()) {
      $form.classList.add("was-validated");
      return;
    }
    const formData = new FormData($form);
    formData.append("accion", "alta");
    formData.append("rodescripcion", $nombre.value);
    fetch("../../ajax/rolesAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data) {
          Swal.fire(
            "¡Rol creado!",
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
    $modal.show();
  });

  //Seccion parte borrar registro
  let $botonBorrar = null;
  let $stockBorrar = null;

  const $modalCerrar = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("modalBorrar")
  );
  document.addEventListener("click", (e) => {
    $botonBorrar = e.target.closest(".btnBorrar");

    if (!$botonBorrar) {
      return;
    }
    $idBorrar = $botonBorrar.dataset.idrol;

    $modalCerrar.show();
  });
  const $confirmacion = document.getElementById("botonBorrarConfirmacion");
  $confirmacion.addEventListener("click", () => {
    console.log($idBorrar);

    $url = "../../ajax/rolesAjax.php?accion=baja";

    fetch($url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        idrol: $idBorrar,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data === true || data === "true") {
          Swal.fire(
            "¡Rol eliminado!",
            "El rol se eliminó satisfactoriamente",
            "success"
          );
          $modalCerrar.hide();
          document.activeElement?.blur();

          actualizarTabla();
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo borrar el producto",
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
  let $formEdicion = document.getElementById("formEdicion");
  const $modalEditar = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("modalEditar")
  );
  document.addEventListener("click", (e) => {
    const $botonEditar = e.target.closest(".btnEditar");
    if (!$botonEditar) return;

    $idEdicion = $botonEditar.dataset.idrol;
    const formData = new FormData($formEdicion);
    formData.append("idrol", $idEdicion);
    formData.append("accion", "buscar");
    fetch("../../ajax/rolesAjax.php?=buscar", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
          if (Array.isArray(data) && data.length > 0) {
              let $nombreEdicion = document.getElementById("rodescripcionEdicion");
              console.log(data[0].rodescripcion)

          $nombreEdicion.value = data[0].rodescripcion;
        }
        $modalEditar.show();

        //seccion de confirmacion de la edicion

        const $botonConfirmacionEditar = document.getElementById(
          "btnEditarConfirmacion"
        );
        $botonConfirmacionEditar.addEventListener("click", () => {
          // Activar validación visual
          if (!$formEdicion.checkValidity()) {
            $formEdicion.classList.add("was-validated");
            return;
          }
          $nombreEdicion = document.getElementById("rodescripcionEdicion");

          const formData = new FormData($formEdicion);
          formData.append("idrol", $idEdicion);
          formData.append("accion", "editar");
          formData.append("rodescripcion", $nombreEdicion.value);

          fetch("../../ajax/rolesAjax.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data) {
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
              Swal.fire(
                "Error",
                "Hubo un problema de conexión al editar",
                "error"
              );
            });
        });
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión", "error");
      });
  });

  function actualizarTabla($valor) {
    fetch("../../ajax/rolesAjax.php?accion=listar")
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

  //Seccion para sin stock
});
