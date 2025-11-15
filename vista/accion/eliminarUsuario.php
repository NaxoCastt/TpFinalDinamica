<?php
include_once "../../configuracion.php";


$datos = data_submitted();
$abmUsuario = new AbmUsuario();

$exito = false;
$mensaje = "Acción no válida.";

if (isset($datos['idusuario']) && isset($datos['accion'])) {
    
    // 1. Buscamos el usuario actual
    $lista = $abmUsuario->buscar(['idusuario' => $datos['idusuario']]);
    
    if (count($lista) > 0) {
        $usuario = $lista[0];
        
        // 2. Preparamos los datos (necesitamos todos los datos viejos para usar el método modificación)
        $params = [
            'idusuario' => $usuario->getIdusuario(),
            'usnombre' => $usuario->getUsnombre(),
            'usmail' => $usuario->getUsmail(),
            'uspass' => '', // Al pasar vacío, ABMUsuario mantiene la vieja
            'usdeshabilitado' => $usuario->getUsdeshabilitado()
        ];

        // 3. Aplicamos la lógica de Habilitar/Deshabilitar
        if ($datos['accion'] == 'deshabilitar') {
            $params['usdeshabilitado'] = date("Y-m-d H:i:s");
            $mensaje = "Usuario deshabilitado.";
        } elseif ($datos['accion'] == 'habilitar') {
            $params['usdeshabilitado'] = null; // Enviamos null explícitamente
            $mensaje = "Usuario habilitado.";
        }

        // 4. Ejecutamos
        if ($abmUsuario->modificacion($params)) {
            $exito = true;
        } else {
            $mensaje = "Error en la base de datos al cambiar estado.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}

header('Location: ../Admin/usuarios.php?exito=' . $exito . '&mensaje=' . urlencode($mensaje));
exit;
?>