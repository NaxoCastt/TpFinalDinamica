<?php
include_once '../configuracion.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);


$inputJSON = file_get_contents('php://input');
$datos = json_decode($inputJSON, true);
$accion = $datos['accion'] ?? '';

$objSession = new Session();
$rol = $objSession->getIdRol()[0];
$objMenu = new ABMMenu();
$objMenuRol = new ABMMenuRol();
$respuesta = null;

switch ($accion) {

    case ("listarMenus"):
        $respuesta = null;


        $ObjsMenusRoles = $objMenuRol->listar("idrol = " . $rol);
        $ObjsMenus = [];
        foreach ($ObjsMenusRoles as $item) {

            array_push($ObjsMenus, $objMenu->buscar($item->getIdmenu()));
        }
        $menus = [];

        foreach ($ObjsMenus as $itemMenus) {
            if ($itemMenus->getMedeshabilitado() == NULL && $itemMenus->getIdpadre() == NULL)
                $menus[] = [

                    "idmenu" => $itemMenus->getIdmenu(),
                    "menombre" => $itemMenus->getMenombre(),
                    "medescripcion" => $itemMenus->getMedescripcion()
                ];
        }

        $respuesta = $menus;

        break;

    case "listarSubMenus":


        $respuesta = null;
        $subMenus = $objMenu->listar("idpadre IS NOT NULL AND medeshabilitado IS NULL");
        foreach ($subMenus as $itemMenus) {
            if ($itemMenus->getMedeshabilitado() == NULL)
                $menus[] = [

                    "idmenu" => $itemMenus->getIdmenu(),
                    "idpadre" => $itemMenus->getIdpadre(),
                    "menombre" => $itemMenus->getMenombre(),
                    "medescripcion" => $itemMenus->getMedescripcion()
                ];
        }

        $respuesta = $menus;

        break;

    case 'listar':
        $datos = data_submitted();
        $respuesta = $obj->listar("medeshabilitado IS NULL");

        break;

    case 'listardeshabilitados':
        $datos = data_submitted();
        $respuesta = $obj->listar("medeshabilitado IS NOT NULL");


        $respuesta = $salida;
        break;

    case 'alta':
        $datos = data_submitted();
        $respuesta = $objMenu->alta($datos);

        break;

    case 'baja':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $objMenu->baja($datos['idmenu']);

        break;

    case 'bajaDefinitiva':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $objMenu->bajaFisica($datos['idmenu']);


        break;

    case "switchVerComo":
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $_SESSION['idRoles'][0] = $datos['idRol'];
        break;

    default:
        $respuesta = ["error" => "Accion desconocida"];
        break;
}


header('Content-Type: application/json');
echo json_encode($respuesta);

exit;
