<?php
include_once '../configuracion.php';


ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$datos = data_submitted();
$accion = $datos['accion'] ?? '';

$abmUsuario = new AbmUsuario();
$abmUsuarioRol = new AbmUsuarioRol();
$abmRol = new AbmRol();

$respuesta = ['exito' => false, 'mensaje' => 'Accion no valida'];

switch ($accion) {
    case 'listar':
        $listaUsuarios = $abmUsuario->buscar(null);
        $arregloSalida = [];

        foreach ($listaUsuarios as $usuario) {
            $id = $usuario->getIdusuario();
            
            // Buscar roles del usuario
            $rolesUser = $abmUsuarioRol->buscar(['idusuario' => $id]);
            $rolesStr = "";
            $rolesIds = [];

            foreach ($rolesUser as $ur) {
                $objRol = new Rol();
                if ($objRol->buscar($ur->getIdrol())) {
                    // Formato HTML para la columna de roles (Badges)
                    $rolesStr .= '<span class="badge bg-info text-dark me-1">' . $objRol->getRodescripcion() . '</span>';
                    $rolesIds[] = $ur->getIdrol();
                }
            }

            $arregloSalida[] = [
                'idusuario' => $id,
                'usnombre' => $usuario->getUsnombre(),
                'usmail' => $usuario->getUsmail(),
                'usdeshabilitado' => $usuario->getUsdeshabilitado(), // null si activo, timestamp si inactivo
                'roles_display' => $rolesStr, // Para mostrar en la tabla
                'roles_ids' => $rolesIds      // Para rellenar el modal de roles
            ];
        }
        // En listar devolvemos el array directamente
        $respuesta = $arregloSalida;
        break;

    case 'modificar':
        // Logica equivalente a actualizarUsuario.php
        if (isset($datos['idusuario']) && isset($datos['usnombre']) && isset($datos['usmail'])) {
            // ABMUsuario->modificacion espera el array con los datos
            // Nota: El ABM maneja internamente si la pass viene vacía
            if ($abmUsuario->modificacion($datos)) {
                $respuesta = ['exito' => true, 'mensaje' => 'Usuario modificado con éxito.'];
            } else {
                $respuesta = ['exito' => false, 'mensaje' => 'Error al modificar el usuario.'];
            }
        } else {
            $respuesta = ['exito' => false, 'mensaje' => 'Faltan datos requeridos.'];
        }
        break;

    case 'cambiar_estado':
        // Logica equivalente a eliminarUsuario.php (Deshabilitar/Habilitar)
        if (isset($datos['idusuario']) && isset($datos['tipo_accion'])) {
            $lista = $abmUsuario->buscar(['idusuario' => $datos['idusuario']]);
            
            if (count($lista) > 0) {
                $usuario = $lista[0];
                $params = [
                    'idusuario' => $usuario->getIdusuario(),
                    'usnombre' => $usuario->getUsnombre(),
                    'usmail' => $usuario->getUsmail(),
                    'uspass' => '', // Para mantener la contraseña anterior en ABMUsuario
                    'usdeshabilitado' => $usuario->getUsdeshabilitado()
                ];

                if ($datos['tipo_accion'] == 'deshabilitar') {
                    $params['usdeshabilitado'] = date("Y-m-d H:i:s");
                    $msg = "Usuario deshabilitado.";
                } elseif ($datos['tipo_accion'] == 'habilitar') {
                    $params['usdeshabilitado'] = null;
                    $msg = "Usuario habilitado.";
                } else {
                    $msg = "Acción desconocida.";
                }

                if ($abmUsuario->modificacion($params)) {
                    $respuesta = ['exito' => true, 'mensaje' => $msg];
                } else {
                    $respuesta = ['exito' => false, 'mensaje' => 'Error en la base de datos.'];
                }
            } else {
                $respuesta = ['exito' => false, 'mensaje' => 'Usuario no encontrado.'];
            }
        }
        break;

    case 'actualizar_roles':
        // Logica equivalente a actualizarRoles.php
        if (isset($datos['idusuario'])) {
            $idUsuario = $datos['idusuario'];
            $rolesNuevos = $datos['roles'] ?? []; // Puede venir vacío si desmarcó todo

            // Eliminar roles actuales
            $rolesActuales = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);
            foreach ($rolesActuales as $relacion) {
                $abmUsuarioRol->baja(['idusuario' => $idUsuario, 'idrol' => $relacion->getIdrol()]);
            }

            // Insertar nuevos
            $todoOk = true;
            if (!empty($rolesNuevos)) {
                // Si viene un solo rol no como array, lo convertimos (por seguridad)
                if (!is_array($rolesNuevos)) {
                    $rolesNuevos = [$rolesNuevos];
                }
                
                foreach ($rolesNuevos as $idRol) {
                    if (!$abmUsuarioRol->alta(['idusuario' => $idUsuario, 'idrol' => $idRol])) {
                        $todoOk = false;
                    }
                }
            }

            if ($todoOk) {
                $respuesta = ['exito' => true, 'mensaje' => 'Roles actualizados correctamente.'];
            } else {
                $respuesta = ['exito' => false, 'mensaje' => 'Error al asignar algunos roles.'];
            }
        } else {
            $respuesta = ['exito' => false, 'mensaje' => 'ID de usuario no proporcionado.'];
        }
        break;
    
    default:
        $respuesta = ['exito' => false, 'mensaje' => 'Acción no definida'];
        break;
}

header('Content-Type: application/json');
echo json_encode($respuesta);
exit;
?>