<?php
include_once "../../configuracion.php";
$objSession = new Session();

// Si no está logueado, al login
if (!$objSession->validar()) {
  header('Location: ../login.php?error=Debe iniciar sesion');
  exit;
}

// Si no es Admin, al catálogo de cliente (o a donde prefieras)
if (!in_array('Administrador', $objSession->getRol())) {
  header('Location: ../Cliente/productos.php');
  exit;
}
?>

<!DOCTYPE html>
<a href="../accion/cerrarSesion.php" class="btn btn-danger float-end m-3">Cerrar Sesión</a>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Catálogo de Juguetes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="p-4 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #e0c3fc, #8ec5fc);">
      <h2 class="text-center text-white mb-4">
        <i class="bi bi-stars"></i> Catálogo de Juguetes
      </h2>

      <table class="table table-bordered table-hover bg-white rounded-3 overflow-hidden">
        <thead class="table-secondary">
          <tr>
            <th>Id</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Detalles</th>
            <th>Stock</th>
            <th class="text-center w-25">Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaProductos">

        </tbody>
      </table>
      <!-- Modal para agregar producots -->
      <div class="d-flex">
        <button type="button" id="agregarProductoBtn" class="btn btn-light d-flex justify-content-center m-auto" data-bs-whatever="@mdo"><i class="bi bi-plus"> Agregar producto</i></button>
        <button type="button" id="verSinStock" class="btn btn-light d-flex justify-content-center m-auto" data-bs-whatever="@mdo"><i class="bi bi-plus"> Ver productos sin stock...En construccion</i></button>
      </div>
      <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar producto</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="needs-validation" id="form" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Nombre del producto</label>
                  <input type="text" id="nombre" name="pronombre" class="form-control" required maxlength="100"
                    pattern="^[\p{L}\d\s.,\-!&]{1,100}$">
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Detalle</label>
                  <textarea class="form-control" id="detalle" name="prodetalle"></textarea>
                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Stock</label>
                  <input type="number" class="form-control" id="stock" name="procantstock" required max="9999"
                    step="1" min="1"></input>
                </div>
                <div class="mb-3">
                  <label for="imagen" class="col-form-label">Imagen del producto</label>
                  <input type="file" id="imagen" name="imagen" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                  <div class="invalid-feedback">Solo se permiten imágenes JPG, PNG o WEBP.</div>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary" id="agregar">Agregar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- modal para borrar producto -->

      <div class="modal" tabindex="-1" id="modalBorrar">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Esta seguro que quiere eliminar este producto?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="button" class="btn btn-primary" id="botonBorrarConfirmacion">Si</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para editar registros -->

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar producto</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" id="formEdicion" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="recipient-name" class="col-form-label">Nombre del producto</label>
                <input type="text" id="nombreEdicion" class="form-control" name="pronombre" required maxlength="100"
                  pattern="^[\p{L}\d\s.,\-!&]{1,100}$">
              </div>
              <div class="mb-3">
                <label for="message-text" class="col-form-label">Detalle</label>
                <textarea class="form-control" id="detalleEdicion" name="prodetalle"></textarea>
              </div>
              <div class="mb-3">
                <label for="message-text" class="col-form-label">Stock</label>
                <input type="number" class="form-control" id="stockEdicion" name="procantstock" required max="9999"
                  step="1" min="1"></input>
              </div>
              <div class="mb-3">
                <label for="imagen" class="col-form-label">Imagen del producto</label>
                <input type="file" id="imagenEdicion" name="imagenEdicion" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                <div class="invalid-feedback">Solo se permiten imágenes JPG, PNG o WEBP.</div>
                <input type="hidden" id="idEdicion" name="idproducto">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btnEditarConfirmacion">Editar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/productos.js"></script>
</body>

</html>