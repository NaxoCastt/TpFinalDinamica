<?php
include_once "../../configuracion.php";

$datos = data_submitted();
$errorRedir = '../registro.php?error=';
$exitoRedir = '../login.php?success=';

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

$abmUsuario = new AbmUsuario();

// Intentar dar de alta al usuario
// La función 'alta' devuelve el ID del nuevo usuario o 'false' si falla (ej: mail duplicado)
$idNuevoUsuario = $abmUsuario->alta($datos);

if ($idNuevoUsuario > 0) {
    // Si el alta fue exitosa, asignar el rol "Cliente" (ID 2)
    $abmUsuarioRol = new AbmUsuarioRol();
    $datosRol = [
        'idusuario' => $idNuevoUsuario,
        'idrol' => 2 // ID 2 = Cliente (según bdcarritocompras.sql)
    ];

    if ($abmUsuarioRol->alta($datosRol)) {
        // ¡Éxito! Redirigir al login
        header('Location: ' . $exitoRedir . urlencode('¡Registro exitoso! Ya podés iniciar sesión.'));
        exit;
    } else {
        // Falló la asignación de rol (raro, pero posible)
        // Opcional: Borrar el usuario creado para mantener consistencia
        $abmUsuario->baja(['idusuario' => $idNuevoUsuario]);
        header('Location: ' . $errorRedir . urlencode('Error al asignar rol. Intente de nuevo.'));
        exit;
    }

} else {
    // Si 'alta' devolvió false, probablemente el email ya existe
    header('Location: ' . $errorRedir . urlencode('El email ingresado ya está registrado.'));
    exit;
}

?>