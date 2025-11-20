<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Juguetería</title>
      <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 450px;">
        <h3 class="text-center mb-4">Crear Cuenta</h3>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="accion/accionRegistro.php" method="POST" id="formRegistro" class="needs-validation" novalidate>
            
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
                <label class="form-label" for="uspass">Contraseña</label>
                <input type="password" name="uspass" id="uspass" class="form-control" required minlength="4">
                <div class="invalid-feedback">
                    La contraseña debe tener al menos 4 caracteres.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="uspass_confirm">Confirmar Contraseña</label>
                <input type="password" name="uspass_confirm" id="uspass_confirm" class="form-control" required>
                <div class="invalid-feedback" id="passConfirmError">
                    Las contraseñas no coinciden.
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">Registrarse</button>
        </form>

        <div class="text-center mt-3">
            <small>¿Ya tenés cuenta? <a href="login.php">Ingresá acá</a></small>
        </div>
    </div>

    <script src="js/registro.js"></script>
</body>
</html>