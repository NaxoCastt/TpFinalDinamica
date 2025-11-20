document.addEventListener("DOMContentLoaded", () => {
  let $tabla = document.getElementById("tablaProductos");

  // 1. Cargar Listados para la tabla
  cargarTabla();
  
  // 2. Cargar los Selects (Roles y Menús) para los modales
  cargarSelects();

  function cargarTabla() {
    fetch("../../ajax/menuRolPermisosAjax.php?accion=listar")
      .then((response) => response.json())
      .then(($productos) => {
        if ($productos.length === 0) {
          $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay permisos asignados</td></tr>`;
          return;
        }
        $tabla.innerHTML = dibujarTabla($productos);
      });
  }

  function dibujarTabla($datos) {
    let $dibujado = "";
    $datos.forEach((element) => {
      $dibujado += `
        <tr>
            <td style="vertical-align: middle">${element.rodescripcion}</td> 
            <td style="vertical-align: middle">${element.menombre}</td>
            <td style="vertical-align: middle">
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-warning btn-sm px-3 py-2 btnEditar" title="Editar" data-idrol="${element.idrol}" data-idmenu="${element.idmenu}">
                    <i class="bi bi-pen"></i>
                    </button> 
                    <button class="btn btn-danger btn-sm px-3 py-2 btnBorrar" data-idrol="${element.idrol}" data-idmenu="${element.idmenu}" title="Borrar">
                    <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    });
    return $dibujado;
  }

  function cargarSelects() {
    // Cargar Roles
    fetch("../../ajax/rolesAjax.php?accion=listar")
      .then(r => r.json())
      .then(roles => {
        let opciones = '<option value="" selected disabled>Seleccione un rol...</option>';
        roles.forEach(rol => {
          opciones += `<option value="${rol.idrol}">${rol.rodescripcion}</option>`;
        });
        document.getElementById("idRol").innerHTML = opciones;
        document.getElementById("idRolEdicion").innerHTML = opciones;
      });

    // Cargar Menús
    fetch("../../ajax/menuModificacionAjax.php?accion=listarPrincipales")
      .then(r => r.json())
      .then(menus => {
        let opciones = '<option value="" selected disabled>Seleccione un menú...</option>';
        menus.forEach(menu => {
          opciones += `<option value="${menu.idmenu}">${menu.menombre}</option>`;
        });
        document.getElementById("idMenu").innerHTML = opciones;
        document.getElementById("idMenuEdicion").innerHTML = opciones;
      });
  }

  // --- SECCION AGREGAR (ALTA) ---
  const $modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("Modal"));
  const $botonCrear = document.getElementById("agregar");
  const $form = document.getElementById("form");

  // >>>>>>>>>> ESTO ES LO QUE FALTABA <<<<<<<<<<
  const $btnAbrirModal = document.getElementById("agregarProductoBtn");
  $btnAbrirModal.addEventListener("click", () => {
      $form.reset(); // Limpiamos el formulario
      $form.classList.remove("was-validated"); // Quitamos estilos de validación viejos
      $modal.show(); // Mostramos el modal
  });

  $botonCrear.addEventListener("click", () => {
    if (!$form.checkValidity()) {
      $form.classList.add("was-validated");
      return;
    }
    const formData = new FormData($form);
    formData.append("accion", "alta");

    fetch("../../ajax/menuRolPermisosAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          Swal.fire("¡Permiso creado!", "Se asignó correctamente", "success");
          $modal.hide();
          $form.reset();
          $form.classList.remove("was-validated");
          cargarTabla();
        } else {
          Swal.fire("Error", data.msg || "No se pudo crear", "error");
        }
      })
      .catch(() => Swal.fire("Error", "Hubo un problema de conexión", "error"));
  });

  // --- SECCION BORRAR ---
  let $idRolBorrar = null;
  let $idMenuBorrar = null;
  const $modalBorrar = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalBorrar"));
  
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".btnBorrar");
    if (!btn) return;
    $idRolBorrar = btn.dataset.idrol;
    $idMenuBorrar = btn.dataset.idmenu;
    $modalBorrar.show();
  });

  document.getElementById("botonBorrarConfirmacion").addEventListener("click", () => {
    const formData = new FormData();
    formData.append("accion", "baja");
    formData.append("idrol", $idRolBorrar);
    formData.append("idmenu", $idMenuBorrar);

    fetch("../../ajax/menuRolPermisosAjax.php", {
      method: "POST",
      body: formData 
    })
      .then(r => r.json())
      .then(data => {
        if (data === true || data.exito === true) {
          Swal.fire("¡Eliminado!", "El permiso se eliminó correctamente", "success");
          $modalBorrar.hide();
          cargarTabla();
        } else {
          Swal.fire("Error", data.msg || "No se pudo borrar el permiso", "error");
        }
      })
      .catch((error) => {
        console.error(error);
        Swal.fire("Error", "Hubo un error de conexión", "error");
      });
  });

  // --- SECCION EDITAR ---
  let $idRolOriginal = null;
  let $idMenuOriginal = null;
  const $modalEditar = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalEditar"));
  const $formEdicion = document.getElementById("formEdicion");

  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".btnEditar");
    if (!btn) return;

    $idRolOriginal = btn.dataset.idrol;
    $idMenuOriginal = btn.dataset.idmenu;

    // Pre-seleccionamos los valores
    document.getElementById("idRolEdicion").value = $idRolOriginal;
    document.getElementById("idMenuEdicion").value = $idMenuOriginal;

    $modalEditar.show();
  });

  document.getElementById("btnEditarConfirmacion").addEventListener("click", () => {
    if (!$formEdicion.checkValidity()) {
        $formEdicion.classList.add("was-validated");
        return;
    }

    const formData = new FormData();
    formData.append("idrolNuevo", document.getElementById("idRolEdicion").value);
    formData.append("idmenuNuevo", document.getElementById("idMenuEdicion").value);
    formData.append("idrolOriginal", $idRolOriginal);
    formData.append("idmenuOriginal", $idMenuOriginal);
    formData.append("accion", "editar");

    fetch("../../ajax/menuRolPermisosAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data) {
          Swal.fire("¡Editado!", "Se modificó correctamente", "success");
          $modalEditar.hide();
          $formEdicion.classList.remove("was-validated");
          cargarTabla();
        } else {
          Swal.fire("Error", "No se pudo editar (¿Ya existe esa combinación?)", "error");
        }
      })
      .catch(() => Swal.fire("Error", "Error de conexión", "error"));
  });
});