document.addEventListener("DOMContentLoaded", () => {
  let $tabla = document.getElementById("tablaProductos");

  fetch("../../ajax/menuRolPermisosAjax.php?accion=listar")
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
        <td style="vertical-align: middle">${element.idmenu}</td>
        <td style="vertical-align: middle">
  <div class="d-flex justify-content-center gap-3">
    <button class="btn btn-warning btn-sm px-3 py-2 btnEditar" title="Editar" data-idrol="${element.idrol}" data-idmenu="${element.idmenu}">
      <i class="bi bi-pen"></i>
    </button> 
    <button class="btn btn-danger btn-sm px-3 py-2 btnBorrar" data-idrol="${element.idrol}" data-idmenu="${element.idmenu}" 
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
  const $idMenu = document.getElementById("idMenu");
  const $idRol = document.getElementById("idRol");
  const $form = document.getElementById("form");

  $botonCrear.addEventListener("click", () => {
    // Activar validación visual
    if (!$form.checkValidity()) {
      $form.classList.add("was-validated");
      return;
    }
    const formData = new FormData($form);
    formData.append("accion", "alta");
    formData.append("idmenu", $idMenu.value);
    formData.append("idrol", $idRol.value);
    fetch("../../ajax/menuRolPermisosAjax.php", {
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

    $idMenu.value = "";
    $idRol.value = "";
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
    $idBorrar2 = $botonBorrar.dataset.idmenu;
    console.log($idBorrar);
    console.log($idBorrar2);
    $modalCerrar.show();
  });
  const $confirmacion = document.getElementById("botonBorrarConfirmacion");
  $confirmacion.addEventListener("click", () => {
    $url = "../../ajax/menuRolPermisosAjax.php?accion=baja";

    fetch($url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        idrol: $idBorrar,
        idmenu: $idBorrar2,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data === true || data === "true") {
          Swal.fire(
            "¡Puente eliminado!",
            "El puente se eliminó satisfactoriamente",
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
  //Seccion para editar registro

  let $idRolEdicion = null;
  let $idMenuEdicion = null;
  let $idRolEdicionInput = document.getElementById("idRolEdicion");
  let $idMenuEdicionInput = document.getElementById("idMenuEdicion");
  let $formEdicion = document.getElementById("formEdicion");
  const $modalEditar = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("modalEditar")
  );

  // EVENT LISTENER PARA HACER CLIC EN BOTÓN EDITAR
  document.addEventListener("click", (e) => {
    const $botonEditar = e.target.closest(".btnEditar");
    if (!$botonEditar) return;

    $idRolEdicion = $botonEditar.dataset.idrol;
    $idMenuEdicion = $botonEditar.dataset.idmenu;

    const formData = new FormData();
    formData.append("idrol", $idRolEdicion);
    formData.append("idmenu", $idMenuEdicion);
    formData.append("accion", "buscar");

    fetch("../../ajax/menuRolPermisosAjax.php?accion=buscar", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data && data.idrol !== undefined) {
          $idRolEdicionInput.value = data.idrol;
          $idMenuEdicionInput.value = data.idmenu;
          $modalEditar.show();
        } else {
          Swal.fire("Error", "No se encontró el registro", "error");
        }
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión", "error");
      });
  });

  // EVENT LISTENER PARA CONFIRMAR EDICIÓN (FUERA, NO DENTRO)
  const $botonConfirmacionEditar = document.getElementById(
    "btnEditarConfirmacion"
  );

  $botonConfirmacionEditar.addEventListener("click", () => {
    if (!$formEdicion.checkValidity()) {
      $formEdicion.classList.add("was-validated");
      return;
    }

    const formData = new FormData();
    formData.append(
      "idrolNuevo",
      document.getElementById("idRolEdicion").value
    );
    formData.append(
      "idmenuNuevo",
      document.getElementById("idMenuEdicion").value
    );
    formData.append("idrolOriginal", $idRolEdicion);
    formData.append("idmenuOriginal", $idMenuEdicion);
    formData.append("accion", "editar");

    fetch("../../ajax/menuRolPermisosAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data) {
          Swal.fire(
            "¡Puente Editado!",
            "Se modificó correctamente el puente",
            "success"
          );
          $modalEditar.hide();
          document.activeElement?.blur();
          $formEdicion.classList.remove("was-validated");
          actualizarTabla();
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo editar el puente",
            "error"
          );
        }
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión al editar", "error");
      });
  });

  function actualizarTabla($valor) {
    fetch("../../ajax/menuRolPermisosAjax.php?accion=listar")
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
