<?php

include_once '../../configuracion.php';

// Verificación de campos
if (!isset($datos['usnombre']) || !isset($datos['usmail']) || !isset($datos['uspass']) || !isset($datos['uspass_confirm'])) {
    header('Location: ' . $errorRedir . urlencode('Faltan datos.'));
    exit;
}

// Verificación de contraseñas (redundante con JS, pero necesario por seguridad)
if ($datos['uspass'] != $datos['uspass_confirm']) {
    header('Location: ' . $errorRedir . urlencode('Las contraseñas no coinciden.'));
    exit;
}

$obj = new AbmUsuario();

