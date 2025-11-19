<?php
include_once "../../configuracion.php";
include_once "../../control/Session.php";
$objSession = new Session();

// Si no está logueado, no ve nada
if (!$objSession->validar()) {
    header('Location: ../login.php?error=Debe iniciar sesion para ver el catalogo');
    exit;
};
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Modificar usuario</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include_once '../../estructura/header.php' ?>
    <div class="container py-5 w-50" style="min-height: 72vh" >
        <div class="p-4 rounded-4 shadow-lg d-flex justify-content-center flex-column align-items-center" style="background: linear-gradient(135deg, #e0c3fc, #8ec5fc);">
            <h2 class="text-center text-white mb-4">
                <i class="bi bi-person-fill-gear"></i> Modificacion de datos
            </h2>
            <div class="container py-4 w-50" id="datosUsuario" data-id-usuario="<?php echo $objSession->getIdUsuario() ?>">

                <form action="#" method="POST" id="formRegistro" class="needs-validation" onsubmit="return actualizarDatos" novalidate>

                    <div class="mb-3">
                        <label class="form-label" for="usnombre">Nombre de Usuario</label>
                        <input type="text" name="usnombre" id="usnombre" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa tu nombre.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="usmail">Email</label>
                        <input type="email" name="usmail" id="usmail" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, ingresa un email válido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="uspass">Nueva contraseña</label>
                        <input type="password" name="uspass" id="uspass" class="form-control" minlength="4" placeholder="Dejar en blanco para no cambiar" required>
                        <div class="invalid-feedback">
                            La contraseña debe tener al menos 4 caracteres.
                        </div>
                    </div>
                    <input type="hidden" name="idusuarioEscondido" id="idusuarioEscondido">
                    <div class="mb-3">
                        <label class="form-label" for="uspass_confirm">Confirmar Contraseña</label>
                        <input type="password" name="uspass_confirm" id="uspass_confirm" class="form-control" required>
                        <div class="invalid-feedback" id="passConfirmError">
                            Las contraseñas no coinciden.
                        </div>
                    </div>

                    <button type="button" class="btn btn-success w-100" id="editarUsuario">Editar</button>
                </form>
            </div>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script src="../js/registro.js"></script>
    <!-- Usamos el mismo script de verificacion que usamos en el registro pq es casi igual !-->
     <script src="../js/modificarUsuario.js"></script>
    <?php include_once '../../estructura/footer.php' ?>
</body>

</html>