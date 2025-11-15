<?php
include_once "../../configuracion.php";
// Asegúrate de que Session y AbmUsuarioRol se carguen correctamente
// (Si usas el autoloader de configuracion.php no hacen falta los include, 
// pero si los dejas no pasa nada).

$objSession = new Session();
if (!$objSession->validar() || !in_array('Administrador', $objSession->getRol())) {
    header('Location: ../login.php');
    exit;
}

$datos = data_submitted();
$abmUsuarioRol = new AbmUsuarioRol();

if (isset($datos['idusuario'])) {
    $idUsuario = $datos['idusuario'];
    $rolesNuevos = $datos['roles'] ?? []; 

    // Obtener y ELIMINAR roles actuales
    $rolesActuales = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);
    
    foreach ($rolesActuales as $relacion) {
        $idRolActual = $relacion->getIdrol();
        
        $paramBaja = [
            'idusuario' => $idUsuario,
            'idrol' => $idRolActual
        ];
        $abmUsuarioRol->baja($paramBaja);
    }

    // INSERTAR nuevos roles
    $exito = true;
    foreach ($rolesNuevos as $idRol) {
        $paramAlta = [
            'idusuario' => $idUsuario,
            'idrol' => $idRol
        ];
        if (!$abmUsuarioRol->alta($paramAlta)) {
            $exito = false;
        }
    }
    
    if($exito){
        header('Location: ../Admin/usuarios.php?mensaje=Roles actualizados correctamente');
    } else {
        header('Location: ../Admin/usuarios.php?error=Hubo un error al asignar algunos roles');
    }
    
} else {
    header('Location: ../Admin/usuarios.php?error=Faltan datos');
}
?>