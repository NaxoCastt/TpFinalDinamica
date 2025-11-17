<?php
include_once "../../configuracion.php";
$objSession = new Session();

// Verificaciones de Seguridad
if (!$objSession->validar()) {
    header('Location: ../login.php?error=Debe iniciar sesion');
    exit;
}
if (!in_array('Admin', $objSession->getRol())) {
    header('Location: ../Cliente/productos.php');
    exit;
}

$abmRol = new AbmRol();
$rolesPosibles = $abmRol->buscar(null);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include_once '../../estructura/header.php' ?>

    <div class="container py-5">
        <div class="p-4 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #e0c3fc, #8ec5fc);">
            <h2 class="text-center text-white mb-4">
                <i class="bi bi-people-fill"></i> Gestión de Usuarios
            </h2>

            <table class="table table-bordered table-hover bg-white rounded-3 overflow-hidden">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Estado</th>
                        <th class="text-center" style="min-width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaUsuarios">
                    </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Editar Usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarUsuario">
                    <div class="modal-body">
                        <input type="hidden" id="edit_idusuario" name="idusuario">

                        <div class="mb-3">
                            <label class="col-form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_usnombre" name="usnombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Email</label>
                            <input type="email" class="form-control" id="edit_usmail" name="usmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" class="form-control" id="edit_uspass" name="uspass" placeholder="Dejar vacío para no cambiar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRoles" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarRoles">
                    <div class="modal-header">
                        <h5 class="modal-title">Roles de <span id="nombreUsuarioModal" class="fw-bold"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idusuario" id="idUsuarioRolInput">
                        <p class="mb-3">Seleccione los roles para este usuario:</p>
                        
                        <div class="list-group">
                        <?php 
                        // Generamos los checkboxes disponibles.
                        // JS se encargará de MARCAR los que tenga el usuario.
                        if(count($rolesPosibles) > 0){
                            foreach ($rolesPosibles as $rol): ?>
                            <label class="list-group-item">
                                <input class="form-check-input me-1 role-checkbox" type="checkbox" 
                                       name="roles[]" 
                                       value="<?php echo $rol->getIdrol(); ?>" 
                                       id="rol_<?php echo $rol->getIdrol(); ?>">
                                <?php echo $rol->getRodescripcion(); ?>
                            </label>
                        <?php endforeach; 
                        } ?>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../js/usuarios.js"></script>
    <?php include_once '../../estructura/footer.php' ?>

</body>
</html>