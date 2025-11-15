<?php
include_once "../../configuracion.php";

$datos = data_submitted();
$abmUsuario = new AbmUsuario();

$exito = false;
$mensaje = "Error al actualizar el usuario.";

// Lógica de modificación (usa la lógica del modelo que ya tienes)
if (!empty($datos) && $abmUsuario->modificacion($datos)) {
    $exito = true;
    $mensaje = "Usuario modificado con éxito.";
}

header('Location: ../Admin/usuarios.php?exito=' . $exito . '&mensaje=' . urlencode($mensaje));
exit;
?>