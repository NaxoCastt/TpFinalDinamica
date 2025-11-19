document.addEventListener("DOMContentLoaded", () => {
  let $tabla = document.getElementById("tablaProductos");

  fetch("../../ajax/menuModificacionAjax.php?accion=listar")
    .then((response) => response.json())
    .then(($productos) => {
      if ($productos.length === 0) {
        $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay menus para mostrar</td></tr>`;
        return;
      }
      $tabla.innerHTML = dibujarTabla($productos);
    });

  function dibujarTabla($datos) {
    let $dibujado = "";
    $datos.forEach((element) => {
      $dibujado += `
        <tr>
        <td style="vertical-align: middle">${element.idmenu}</td>
        <td style="vertical-align: middle">${element.menombre}</td>
        <td style="vertical-align: middle">${element.medescripcion}</td>
        <td style="vertical-align: middle">${element.idpadre}</td>
        <td style="vertical-align: middle">
  <div class="d-flex justify-content-center gap-3">
    <button class="btn btn-warning btn-sm px-3 py-2 btnEditar" title="Editar" data-id="${element.idmenu}">
      <i class="bi bi-pen"></i>
    </button> 
    <button class="btn btn-danger btn-sm px-3 py-2 btnBorrar" id="botonBorrar"data-id="${element.idmenu}" data-stock="${element.medeshabilitado}"
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
  const $nombre = document.getElementById("menombre");
  const $detalle = document.getElementById("medescripcion");
  const $form = document.getElementById("form");
  let $stock = document.getElementById("idpadre");

  $botonCrear.addEventListener("click", () => {
    let $idpadre = document.getElementById("idpadre").value;
    $idpadre = ($idpadre ?? "").trim().toLowerCase();

    $idpadre =
      $idpadre === "" || $idpadre === "null" ? "-1" : parseInt($idpadre); 
      
      // Activar validación visual
      if (!$form.checkValidity()) {
        $form.classList.add("was-validated");
        return;
      }
      console.log($idpadre);
    const formData = new FormData();
    formData.append("medeshabilitado", null);
     formData.append("menombre", $nombre.value.trim());
     formData.append("medescripcion", $detalle.value.trim());
    formData.append("idpadre", $idpadre);
    formData.append("accion", "alta");
    fetch("../../ajax/menuModificacionAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data) {
          Swal.fire(
            "¡Menu creado!",
            "Se agregó correctamente el menu al sistema",
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
            data.message || "No se pudo crear el menu",
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
  let $stockBorrar = null;

  const $modalCerrar = bootstrap.Modal.getOrCreateInstance(
    document.getElementById("modalBorrar")
  );
  document.addEventListener("click", (e) => {
    $botonBorrar = e.target.closest(".btnBorrar");

    if (!$botonBorrar) {
      return;
    }
    $stockBorrar = $botonBorrar.dataset.stock;
    $idBorrar = $botonBorrar.dataset.id;

    $modalCerrar.show();
  });
  const $confirmacion = document.getElementById("botonBorrarConfirmacion");
  $confirmacion.addEventListener("click", () => {
    if (
      $stockBorrar === "null" ||
      $stockBorrar === undefined ||
      $stockBorrar === ""
    ) {
      $url = "../../ajax/menuModificacionAjax.php?accion=baja";
    } else {
      $url = "../../ajax/menuModificacionAjax.php?accion=bajaDefinitiva";
    }
    fetch($url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id: $idBorrar,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data === true || data === "true") {
          Swal.fire(
            "¡Menu eliminado!",
            "El menu se eliminó satisfactoriamente",
            "success"
          );
          $modalCerrar.hide();
          document.activeElement?.blur();
          if ($stockBorrar === null) {
            actualizarTabla();
          } else {
            VerTablaSinStock();
          }
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo borrar el menu",
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

    $idEdicion = $botonEditar.dataset.id;
    const formData = new FormData($formEdicion);
    formData.append("id", $idEdicion);
    formData.append("accion", "buscar");
    fetch("../../ajax/menuModificacionAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (Array.isArray(data) && data.length > 0) {
          const $estadoWrapper = document.getElementById("estadoMenuWrapper");
          if (data[0].medeshabilitado !== null) {
            $estadoWrapper.classList.remove("d-none");
          } else {
            $estadoWrapper.classList.add("d-none");
          }

          let $nombreEdicion = document.getElementById("menombreEdicion");
          let $detalleEdicion = document.getElementById("medescripcionEdicion");
          let $stockEdicion = document.getElementById("idpadreEdicion");

          $nombreEdicion.value = data[0].menombre;
          $detalleEdicion.value = data[0].medescripcion;
          $stockEdicion.value = data[0].idpadre;
        }
        $modalEditar.show();
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
        Swal.fire("Error", "Hubo un problema de conexión", "error");
      });
  });
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
    const $checkboxEstado = document.getElementById("estadoMenuCheck");
    const medeshabilitado =
      $checkboxEstado && $checkboxEstado.checked
        ? "-1" //Esto es si llega a estar en null
        : "0"; //Esto es si llega a no tener ningun valor

    document.getElementById("idEdicion").value = $idEdicion;
    $nombreEdicion = document.getElementById("menombreEdicion");
    $detalleEdicion = document.getElementById("medescripcionEdicion");
    $stockEdicion = document.getElementById("idpadreEdicion");

    const formData = new FormData($formEdicion);
    formData.append("medeshabilitado", medeshabilitado);
    formData.append("idmenu", $idEdicion);
    formData.append("accion", "editar");
    formData.append("menombre", $nombreEdicion.value);
    formData.append("idpadre", $stockEdicion.value);
    formData.append("medescripcion", $detalleEdicion.value.trim() || "");

    fetch("../../ajax/menuModificacionAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data) {
          Swal.fire(
            "¡Menu Editado!",
            "Se modificó correctamente al menu",
            "success"
          );
          $modalEditar.hide();
          document.activeElement?.blur();
          $chequeadorDeDeshabilitado =
            document.getElementById("botonBorrar").dataset.stock;

          if ($chequeadorDeDeshabilitado === "null") {
            actualizarTabla();
          } else {
            VerTablaSinStock();
          }
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo editar el menu",
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
    fetch("../../ajax/menuModificacionAjax.php?accion=listar")
      .then((response) => response.json())
      .then(($productos) => {
        if ($productos.length === 0) {
          $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay pmenus para mostrar</td></tr>`;
          return;
        }

        $tabla.innerHTML = dibujarTabla($productos);
      })
      .catch((error) => {
        console.error("Error al cargar productos:", error);
        $tabla.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar el menu</td></tr>`;
      });
  }

  //Seccion para sin stock

  function VerTablaSinStock() {
    let $tabla = document.getElementById("tablaProductos");
    fetch("../../ajax/menuModificacionAjax.php?accion=listardeshabilitados")
    .then((response) => response.json())
    .then(($productos) => {
      if ($productos.length === 0) {
        $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay productos para mostrar</td></tr>`;
        return;
      }
      $tabla.innerHTML = dibujarTabla($productos);
    });
  }
  
  let $botonVerSinStock = document.getElementById("verSinStock");
  let $estado = "activo";
  $botonVerSinStock.addEventListener("click", () => {
    console.log($estado);
    if ($estado !== "activo") {
      $botonVerSinStock.innerHTML =
        '<i class="bi bi-plus"> Ver menus desactivados</i>';
      actualizarTabla();
      $estado = "activo";
    } else {
      $botonVerSinStock.innerHTML =
        '<i class="bi bi-plus"> Ver menus activados</i>';
      $estado = "desactivado";
      VerTablaSinStock();
    }
  });
});
