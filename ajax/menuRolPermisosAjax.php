
<?php

include_once '../configuracion.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;

$obj = new ABMMenuRol();
$respuesta = null;
switch ($accion) {

    case 'listar':
        $salida = [];
        $datos = data_submitted();
        $respuesta = $obj->listar("");

        // Instanciamos los ABM para buscar los nombres
        $abmRol = new AbmRol();
        $abmMenu = new ABMMenu();

        foreach ($respuesta as $item) {
            $nombreRol = "Desconocido";
            $nombreMenu = "Desconocido";

            // Buscar descripción del ROL
            $listaRoles = $abmRol->buscar(['idrol' => $item->getIdRol()]);
            if (count($listaRoles) > 0) {
                $nombreRol = $listaRoles[0]->getRodescripcion();
            }

            // Buscar nombre del MENU
            $objMenuEncontrado = $abmMenu->buscar($item->getIdMenu());
            if ($objMenuEncontrado != null) {
                $nombreMenu = $objMenuEncontrado->getMenombre();
            }

            $salida[] = [
                'idmenu'   => $item->getIdMenu(),
                'idrol'    => $item->getIdRol(),
                'menombre' => $nombreMenu,       // Agregamos el nombre
                'rodescripcion' => $nombreRol    // Agregamos la descripción
            ];
        }

        $respuesta = $salida;
        break;

    case 'alta':
        $datos = data_submitted();
        $resultado = $obj->alta($datos);

        if ($resultado === true) {
            $respuesta = ['success' => true, 'msg' => 'Rol creado correctamente'];
        } else {
            $respuesta = ['success' => false, 'msg' => 'El rol ya existe o no se pudo crear'];
        }
        break;

    case 'baja':
        $datos = data_submitted(); // Usamos data_submitted para recibir POST/GET estandar

        if (isset($datos['idmenu']) && isset($datos['idrol'])) {
            if ($obj->baja($datos['idmenu'], $datos['idrol'])) {
                $respuesta = ['exito' => true, 'msg' => 'Permiso eliminado correctamente'];
            } else {
                $respuesta = ['exito' => false, 'msg' => 'No se pudo eliminar el permiso. Verifique que exista.'];
            }
        } else {
            $respuesta = ['exito' => false, 'msg' => 'Faltan datos (ID Menu o ID Rol)'];
        }
        break;


    case 'editar':
        $datos = data_submitted();
        $respuesta = $obj->modificacion($datos);

        break;



    case 'buscar':
        $datos = data_submitted();
        $datos = $obj->buscar($datos['idrol'], $datos['idmenu']);

        if ($datos) {

            $respuesta['idrol'] = $datos->getIdRol();
            $respuesta['idmenu'] = $datos->getIdmenu();
        } else {
            $respuesta = ['error' => 'No se encontró la relación menú-rol'];
        }
        break;

        case 'listarPrincipales':
        $salida = [];
        // Filtramos por habilitados y que sean raíz (idpadre nulo)
        $respuesta = $obj->listar("medeshabilitado IS NULL AND idpadre IS NULL");
        
        foreach ($respuesta as $item) {
            $salida[] = [
                'idmenu'   => $item->getIdMenu(),
                'menombre' => $item->getMenombre()
            ];
        }
        $respuesta = $salida;
        break;


    default:
        $respuesta = ["error" => "Accion desconocida"];

}


header('Content-Type: application/json');
echo json_encode($respuesta);

exit;
