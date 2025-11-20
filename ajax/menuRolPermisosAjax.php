
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
        foreach ($respuesta as $item) {

            $salida[] = [
                'idmenu'   => $item->getIdMenu(),
                'idrol'    => $item->getIdRol(),
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
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->baja($datos['idmenu'], $datos['idrol']);

        break;

  
    case 'editar':
        $datos = data_submitted();
        $respuesta = $obj->modificacion($datos);
        
        break;



    case 'buscar':
        $datos = data_submitted();
        $datos = $obj->buscar($datos['idrol'], $datos['idmenu']);

       if($datos){

        $respuesta['idrol'] = $datos->getIdRol();
        $respuesta['idmenu'] = $datos->getIdmenu();
       }else {
        $respuesta = ['error' => 'No se encontró la relación menú-rol'];
    }
    break;


    default:
        $respuesta = ["error" => "Accion desconocida"];
}


header('Content-Type: application/json');
echo json_encode($respuesta);

exit;
