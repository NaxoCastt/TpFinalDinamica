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
    <title>Mis Compras</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include_once '../../estructura/header.php' ?>

    <div class="container py-5" style="min-height: 72vh">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" 
                 style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc);">
                <h3 class="mb-0"><i class="bi bi-bag-heart-fill"></i> Mis Compras</h3>
                <a href="productos.php" class="btn btn-light btn-sm fw-bold text-primary">
                    <i class="bi bi-cart-plus"></i> Nueva Compra
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th># Orden</th>
                                <th>Fecha Compra</th>
                                <th style="width: 40%;">Productos</th>
                                <th>Estado Actual</th>
                                <th>Última Actualización</th>
                            </tr>
                        </thead>
                        <tbody id="tablaMisCompras">
                            </tbody>
                    </table>
                </div>
                <div id="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div id="noCompras" class="text-center py-5 d-none">
                    <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">Aún no has realizado ninguna compra.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHistorialCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Seguimiento del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush" id="listaHistorialCliente">
                        </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/misCompras.js"></script>

    <?php include_once '../../estructura/footer.php' ?>
</body>
</html>