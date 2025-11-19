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
        <td style="vertical-align: middle">
      <img src="/tpfinaldinamica/util/imagenesProductos/${element.idproducto}.${element.extension}?v=${new Date().getTime()}"

           alt="Imagen del producto"
           style="max-width: 80px; max-height: 80px; object-fit: cover;"
           onerror="this.src='/tpfinaldinamica/util/imagenesProductos/default.png';">
    </td>

        <td style="vertical-align: middle">${element.pronombre}</td>
        <td style="vertical-align: middle">${element.prodetalle}</td>
        <td style="vertical-align: middle">${element.procantstock}</td>
        <td style="vertical-align: middle">
  <div class="d-flex justify-content-center gap-3">
    <button class="btn btn-warning btn-sm px-3 py-2 btnEditar" title="Editar" data-id="${element.idproducto}">
      <i class="bi bi-pen"></i>
    </button> 
    <button class="btn btn-danger btn-sm px-3 py-2 btnBorrar" data-id="${element.idproducto}" data-stock="${element.procantstock}"
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
    const formData = new FormData($form);
    formData.append("id", $idEdicion);
    formData.append("accion", "alta");
    fetch("../../ajax/productoAjax.php?", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data) {
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
    console.log($idBorrar);

    if ($stockBorrar > 0) {
      $url = "../../ajax/productoAjax.php?accion=baja";
    } else {
      $url = "../../ajax/productoAjax.php?accion=bajaDefinitiva";
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
            "¡Producto eliminado!",
            "El producto se eliminó satisfactoriamente",
            "success"
          );
          $modalCerrar.hide();
          document.activeElement?.blur();
          if ($stockBorrar > 0) {
            actualizarTabla();
          } else {
            VerTablaSinStock();
          }
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

    $idEdicion = $botonEditar.dataset.id;
    const formData = new FormData($formEdicion);
    formData.append("id", $idEdicion);
    formData.append("accion", "buscar");
    fetch("../../ajax/productoAjax.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (Array.isArray(data) && data.length > 0) {
          let $nombreEdicion = document.getElementById("nombreEdicion");
          let $detalleEdicion = document.getElementById("detalleEdicion");
          let $stockEdicion = document.getElementById("stockEdicion");
          document.getElementById("imagenEdicion").value = "";

          $nombreEdicion.value = data[0].pronombre;
          $detalleEdicion.value = data[0].prodetalle;
          $stockEdicion.value = data[0].procantstock;
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
          document.getElementById("idEdicion").value = $idEdicion;
          $nombreEdicion = document.getElementById("nombreEdicion");
          $detalleEdicion = document.getElementById("detalleEdicion");
          $stockEdicion = document.getElementById("stockEdicion");

          const formData = new FormData($formEdicion);
          formData.append("id", $idEdicion);
          formData.append("accion", "editar");
          formData.append("pronombre", $nombreEdicion.value);
          formData.append("prodetalle", $detalleEdicion.value.trim() || "");
          const $imagenEdicion = document.getElementById("imagenEdicion");
          if ($imagenEdicion.files.length > 0) {
            formData.append("imagen", $imagenEdicion.files[0]);
          }

          formData.append("procantstock", $stockEdicion.value);

          fetch("../../ajax/productoAjax.php", {
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
                if($stockEdicion.value > 0){

                  actualizarTabla();
                }
                else{
                  VerTablaSinStock()
                }
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

  //Seccion para sin stock

  let $botonVerSinStock = document.getElementById("verSinStock");
  function VerTablaSinStock() {
    let $tabla = document.getElementById("tablaProductos");
    console.log("boton pulsado");
    fetch("../../ajax/productoAjax.php?accion=listarSinStock")
      .then((response) => response.json())
      .then(($productos) => {
        if ($productos.length === 0) {
          $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay productos para mostrar</td></tr>`;
          return;
        }
        $tabla.innerHTML = dibujarTabla($productos);
      });
  }
  let $estado = "activo";
  $botonVerSinStock.addEventListener("click", () => {
    console.log($estado);
    if ($estado !== "activo") {
      $botonVerSinStock.innerHTML =
        '<i class="bi bi-plus"> Ver productos sin stock</i>';
      actualizarTabla();
      $estado = "activo";
    } else {
      $botonVerSinStock.innerHTML =
        '<i class="bi bi-plus"> Ver productos con stock</i>';
      $estado = "desactivado";
      VerTablaSinStock();
    }
  


})})
