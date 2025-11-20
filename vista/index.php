<?php
include_once "../configuracion.php";
$objSession = new Session();
$objSession->asignarSerVisitante();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>IUPI material didactico</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include_once '../estructura/header.php' ?>
    <div class="container py-5" style="min-height: 72vh;">
        <div class="p-5 rounded-4 shadow-lg d-flex flex-column gap-5 position-relative" style="background: linear-gradient(135deg, #d9a7c7, #fffcdc); border: 3px solid #fff; box-shadow: 0 0 25px rgba(0,0,0,0.2);">

            <h1 class="text-center text-dark display-4 fw-bold animate__animated animate__fadeInDown">
                <i class="bi bi-stars text-warning"></i> Bienvenido <i class="bi bi-stars text-warning"></i>
            </h1>

            <div class="text-center">
                <h2 class="text-dark fw-semibold mb-3">🧸 Últimos productos agregados</h2>
                <div class="mx-auto mb-3" style="width: 80px; height: 4px; background: linear-gradient(to right, #ff9a9e, #fad0c4); border-radius: 2px;"></div>
            </div>
            <div class="container px-3" id="catalogoProductos">
                </div>

            <div class="text-center mt-4">
                <h2 class="text-dark fw-semibold mb-3">🔥 Más vendidos</h2>
                <div class="mx-auto mb-3" style="width: 80px; height: 4px; background: linear-gradient(to right, #fbc2eb, #a6c1ee); border-radius: 2px;"></div>
            </div>
            <div class="container px-3" id="catalogoProductosMasVendidos">
                </div>

        </div>
    </div>
    <?php include_once '../estructura/footer.php' ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="js/index.js"></script>
</body>
</html>