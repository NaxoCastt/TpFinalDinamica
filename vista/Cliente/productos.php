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
    <title>Catálogo de Juguetes</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include_once '../../estructura/header.php' ?>
    <div class="container py-5 " style="min-height: 72vh">
        <div class="p-4 rounded-4 shadow-lg d-flex justify-content-center flex-column align-items-center" style="background: linear-gradient(135deg, #e0c3fc, #8ec5fc);">
            <h2 class="text-center text-white mb-4">
                <i class="bi bi-stars"></i> Catálogo de Juguetes
            </h2>

            <div class="container py-4 " id="catalogoProductos"></div>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/catalogo.js"></script>

    <?php include_once '../../estructura/footer.php' ?>
</body>

</html>