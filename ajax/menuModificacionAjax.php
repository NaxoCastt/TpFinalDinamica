
<?php

include_once '../configuracion.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;

$obj = new abmMenu();
$respuesta = null;
switch ($accion) {

    case 'listar':
        $salida = [];
        $datos = data_submitted();
        $objPadre = new ABMMenu();
        $respuesta = $obj->listar("medeshabilitado IS NULL");
        foreach ($respuesta as $item) {
            $idPadre = $item->getIdpadre();
            if ($idPadre == NULL) {
                $idPadre = "Es principal";
            } else if ($idPadre != NULL) {
                $idPadre = $objPadre->buscar($idPadre)->getMenombre();
            }
            $salida[] = [
                'idmenu'   => $item->getIdMenu(),
                'menombre'    => $item->getMenombre(),
                'medescripcion'    => $item->getMedescripcion(),
                'idpadre'   => $idPadre,
                'medeshabilitado' => $item->getMedeshabilitado()
            ];
        }

        $respuesta = $salida;
        break;

    case 'listardeshabilitados':
        $salida = [];
        $datos = data_submitted();
        $objPadre = new ABMMenu();
        $respuesta = $obj->listar("medeshabilitado IS NOT NULL");
        foreach ($respuesta as $item) {
            $idPadre = $item->getIdpadre();
            if ($idPadre == NULL) {
                $idPadre = "Es principal";
            } else if ($idPadre != NULL) {
                $idPadre = $objPadre->buscar($idPadre)->getMenombre();
            }
            $salida[] = [
                'idmenu'   => $item->getIdMenu(),
                'menombre'    => $item->getMenombre(),
                'medescripcion'    => $item->getMedescripcion(),
                'idpadre'   => $idPadre,
                'medeshabilitado' => $item->getMedeshabilitado()
            ];
        }
        $respuesta = $salida;
        break;

    case 'alta':
        $datos = data_submitted();
        $respuesta = $obj->alta($datos);

        break;

    case 'baja':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->baja($datos['id']);

        break;

    case 'buscar':
        $datos = data_submitted();
        $respuesta = null;
        $respuesta = $obj->buscar($datos['id']);
        if ($respuesta !== null && is_object($respuesta)) {
            $salida = [
                'idmenu'   => $respuesta->getIdmenu(),
                'menombre'    => $respuesta->getMenombre(),
                'medescripcion'   => $respuesta->getMedescripcion(),
                'idpadre' => $respuesta->getIdpadre(),
                'medeshabilitado' => $respuesta->getMedeshabilitado()
            ];
            $respuesta = [$salida];  // Envuelve en array para que menus.js espere array
        } else {
            $respuesta = null;
        }
        break;

    case 'bajaDefinitiva':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->bajaFisica($datos['id']);


        break;


    case 'editar':
        $datos = data_submitted();
        $respuesta= $obj->modificacion($datos);


        
        break;
    default:
        $respuesta = ["error" => "Accion desconocida"];
        break;
}



header('Content-Type: application/json');
echo json_encode($respuesta);

exit;
