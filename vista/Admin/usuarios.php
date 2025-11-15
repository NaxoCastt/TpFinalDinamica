<?php
include_once "../../configuracion.php";
include_once "../../control/Session.php";
include_once "../../control/ABMUsuario.php";
include_once "../../control/ABMUsuarioRol.php";

$objSession = new Session();

// Verificaciones de Seguridad
if (!$objSession->validar()) {
    header('Location: ../login.php?error=Debe iniciar sesion');
    exit;
}
if (!in_array('Administrador', $objSession->getRol())) {
    header('Location: ../Cliente/productos.php');
    exit;
}

// Obtener Usuarios
$abmUsuario = new AbmUsuario();
$listaUsuarios = $abmUsuario->buscar(null);
$abmUsuarioRol = new AbmUsuarioRol();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <a href="../accion/cerrarSesion.php" class="btn btn-danger float-end m-3">Cerrar Sesión</a>

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
                        <th class="text-center w-25">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($listaUsuarios) > 0) {
                        foreach ($listaUsuarios as $usuario) {
                            $id = $usuario->getIdusuario();
                            $nombre = htmlspecialchars($usuario->getUsnombre());
                            $mail = htmlspecialchars($usuario->getUsmail());
                            $deshabilitado = $usuario->getUsdeshabilitado();
                            $esActivo = ($deshabilitado == NULL);

                            // Obtener Roles para mostrar
                            $rolesUser = $abmUsuarioRol->buscar(['idusuario' => $id]);
                            $rolesStr = "";
                            foreach ($rolesUser as $ur) {
                                $objRol = new Rol();
                                if ($objRol->buscar($ur->getIdrol())) {
                                    $rolesStr .= $objRol->getRodescripcion() . " ";
                                }
                            }
                    ?>
                            <tr>
                                <td style="vertical-align: middle"><?php echo $id; ?></td>
                                <td style="vertical-align: middle"><?php echo $nombre; ?></td>
                                <td style="vertical-align: middle"><?php echo $mail; ?></td>
                                <td style="vertical-align: middle">
                                    <span class="badge bg-info text-dark"><?php echo $rolesStr; ?></span>
                                </td>
                                <td style="vertical-align: middle">
                                    <?php if ($esActivo): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td style="vertical-align: middle">
                                    <div class="d-flex justify-content-center gap-3">
                                        <button class="btn btn-warning btn-sm px-3 py-2 btnEditar"
                                            title="Editar"
                                            data-id="<?php echo $id; ?>"
                                            data-nombre="<?php echo $nombre; ?>"
                                            data-mail="<?php echo $mail; ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar">
                                            <i class="bi bi-pen"></i>
                                        </button>

                                        <form action="../accion/eliminarUsuario.php" method="POST" class="d-inline">
                                            <input type="hidden" name="idusuario" value="<?php echo $id; ?>">
                                            <?php if ($esActivo): ?>
                                                <input type="hidden" name="accion" value="deshabilitar">
                                                <button type="submit" class="btn btn-danger btn-sm px-3 py-2" title="Deshabilitar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="accion" value="habilitar">
                                                <button type="submit" class="btn btn-success btn-sm px-3 py-2" title="Habilitar">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">No hay usuarios registrados</td></tr>';
                    }
                    ?>
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
                <form action="../accion/actualizarUsuario.php" method="POST">
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
                            <input type="password" class="form-control" name="uspass" placeholder="Dejar vacío para no cambiar">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../js/usuarios.js"></script>
</body>
</html>