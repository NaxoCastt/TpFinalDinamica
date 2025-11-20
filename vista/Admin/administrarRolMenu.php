<?php
include_once "../../configuracion.php";
$objSession = new Session();
require_once __DIR__ . '/../../vendor/autoload.php';

// Validaciones de seguridad
if (!$objSession->validar()) {
  header('Location: ../login.php?error=Debe iniciar sesion');
  exit;
}
if (!in_array('Admin', $objSession->getRol())) {
  header('Location: ../Cliente/productos.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Permisos de Menú y Rol</title>
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
            <th>Rol</th>
            <th>Menú</th>
            <th class="text-center w-25">Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaProductos">
           </tbody>
      </table>
      
      <div class="d-flex">
        <button type="button" id="agregarProductoBtn" class="btn btn-light d-flex justify-content-center m-auto">
            <i class="bi bi-plus"> Agregar permiso</i>
        </button>
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
                      <label for="idRol" class="col-form-label">Rol</label>
                      <select id="idRol" name="idrol" class="form-select" required>
                        <option value="" selected disabled>Seleccione un rol...</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="idMenu" class="col-form-label">Menú</label>
                      <select id="idMenu" name="idmenu" class="form-select" required>
                        <option value="" selected disabled>Seleccione un menú...</option>
                      </select>
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

      <div class="modal" tabindex="-1" id="modalBorrar">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmar eliminación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>¿Está seguro que quiere eliminar este permiso?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="button" class="btn btn-primary" id="botonBorrarConfirmacion">Si</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Editar permiso</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="needs-validation" id="formEdicion">
              <div class="mb-3">
                <label for="idRolEdicion" class="col-form-label">Rol</label>
                <select id="idRolEdicion" name="idRolEdicion" class="form-select" required>
                    </select>
              </div>
              
              <div class="mb-3">
                <label for="idMenuEdicion" class="col-form-label">Menú</label>
                <select id="idMenuEdicion" name="idMenuEdicion" class="form-select" required>
                    </select>
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