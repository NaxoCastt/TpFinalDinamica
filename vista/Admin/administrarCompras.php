<?php
include_once "../../configuracion.php";
$objSession = new Session();

// Seguridad: Solo Admin
if (!$objSession->validar() || !in_array('Admin', $objSession->getRol())) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include_once '../../estructura/header.php' ?>

    <div class="container py-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-bag-check-fill"></i> Gestión de Compras</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#ID</th>
                                <th>ID Usuario</th>
                                <th>Items</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado Actual</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaComprasBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/adminCompras.js"></script>

    <?php include_once '../../estructura/footer.php' ?>
</body>

</html>