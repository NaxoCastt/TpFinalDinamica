<?php
include_once "../../configuracion.php";
$objSession = new Session();
if (!$objSession->validar()) {
    header('Location: ../login.php?error=Debe iniciar sesion');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tu Carrito</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include_once '../../estructura/header.php' ?>


    <div class="container mt-4" style="min-height: 72vh">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-cart"></i> Tu Carrito de Compras</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Detalle</th>
                                <th>Stock Disp.</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCarritoBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="productos.php" class="btn btn-outline-primary btn-lg me-3 px-4">Seguir Comprando</a>
                <button class="btn btn-success btn-lg px-4" disabled title="Próximamente">Finalizar Compra</button>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/carrito.js"></script>

    <?php include_once '../../estructura/footer.php' ?>
</body>

</html>