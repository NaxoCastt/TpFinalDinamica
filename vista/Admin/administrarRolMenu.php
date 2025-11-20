<?php
include_once "../../configuracion.php";
$objSession = new Session();
require_once __DIR__ . '/../../vendor/autoload.php';


// Si no está logueado, al login
if (!$objSession->validar()) {
  header('Location: ../login.php?error=Debe iniciar sesion');
  exit;
}

// Si no es Admin, al catálogo de cliente (o a donde prefieras)
if (!in_array('Admin', $objSession->getRol())) {
  header('Location: ../Cliente/productos.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Catálogo de Juguetes</title>
  <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <?php include_once '../../estructura/header.php' ?>
  <div class="container py-5" style="min-height: 72vh">
    <div class="p-4 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #e0c3fc, #8ec5fc);">
      <h2 class="text-center text-white mb-4">
        <i class="bi bi-stars"></i> Permisos de menu y rol
      </h2>

      <table class="table table-bordered table-hover bg-white rounded-3 overflow-hidden">
        <thead class="table-secondary">
          <tr>
              <th>Id del rol</th>
            <th>Id del menu</th>
            <th class="text-center w-25">Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaProductos">

        </tbody>
      </table>
      <!-- Modal para agregar producots -->
      <div class="d-flex">
        <button type="button" id="agregarProductoBtn" class="btn btn-light d-flex justify-content-center m-auto" data-bs-whatever="@mdo"><i class="bi bi-plus"> Agregar puente</i></button>

      </div>
      <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar permiso</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="needs-validation" id="form" enctype="multipart/form-data">
                  <div class="mb-3">
                      <label for="recipient-name" class="col-form-label">Id del rol</label>
                      <input type="text" id="idRol" name="idRol" class="form-control" required maxlength="100"
                      pattern="^[\p{L}\d\s.,\-!&]{1,100}$">
                    </div>
                    <div class="mb-3">
                      <label for="recipient-name" class="col-form-label">Id del menu</label>
                      <input type="text" id="idMenu" name="idMenu" class="form-control" required maxlength="100"
                        pattern="^[\p{L}\d\s.,\-!&]{1,100}$">
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
              <p>Esta seguro que quiere eliminar este rol?</p>
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
            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar rol</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" id="formEdicion">
              <div class="mb-3">
                <label for="rodescripcion" class="col-form-label">Id del rol</label>
                <input type="text" id="idRolEdicion" class="form-control" name="idRolEdicion" required maxlength="100"
                  pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,\-!&]{1,100}$">
              </div>
              
              <div class="mb-3">
                <label for="rodescripcion" class="col-form-label">Id del menu</label>
                <input type="text" id="idMenuEdicion" class="form-control" name="idMenuEdicion" required maxlength="100"
                  pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,\-!&]{1,100}$">
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
  <script src="../js/rolMenuPermisos.js"></script>
  <?php include_once '../../estructura/footer.php' ?>
</body>

</html>