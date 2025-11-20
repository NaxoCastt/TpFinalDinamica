
<?php

include_once '../configuracion.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;

$obj = new AbmRol();
$respuesta = null;
switch ($accion) {

    case 'listar':
        $salida = [];
        $datos = data_submitted();
        $respuesta = $obj->listar("");
        foreach ($respuesta as $item) {

            $salida[] = [
                'idrol'   => $item->getIdrol(),
                'rodescripcion'    => $item->getRodescripcion(),
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
        $respuesta = $obj->baja($datos);

        break;

  
    case 'editar':
        $datos = data_submitted();
        $respuesta = $obj->modificacion($datos);
     


     
        break;



    case 'buscar':
        $datos = data_submitted();
        $respuesta = $obj->buscar(['idrol' => $datos['idrol']]);

        if (is_array($respuesta) && count($respuesta) > 0) {
            $salida = [];
            foreach ($respuesta as $item) {
                if (is_object($item) && method_exists($item, 'getIdRol')) {
                    $salida[] = [
                        'idrol'   => $item->getIdRol(),
                        'rodescripcion'    => $item->getRodescripcion(),
                    ];
                } else {
                    $salida[] = $item;
                }
            }
            $respuesta = $salida;
        }
        break;

    default:
        $respuesta = ["error" => "Accion desconocida"];
}


header('Content-Type: application/json');
echo json_encode($respuesta);

exit;
