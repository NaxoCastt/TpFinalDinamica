<?php
include_once "../../configuracion.php";

$datos = data_submitted();
$objSession = new Session();

if (isset($datos['usmail']) && isset($datos['uspass'])) {
    if ($objSession->iniciar($datos['usmail'], $datos['uspass'])) {
        header('Location: ../Cliente/productos.php');
        exit;
    } else {
        // Fallo login
        $mensaje = $objSession->getMensajeError();
        header('Location: ../login.php?error=' . urlencode($mensaje));
        exit;
    }
}
header('Location: ../login.php');
