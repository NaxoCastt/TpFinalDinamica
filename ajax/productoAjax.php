
<?php

include_once '../configuracion.php';

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;

$obj = new abmProducto();
$respuesta = null;
switch ($accion) {

    case 'listar':
        $datos = data_submitted();
        $respuesta = $obj->listarProductos();
        if (is_array($respuesta) && count($respuesta) > 0) {
            $salida = [];
            foreach ($respuesta as $item) {
                if (is_object($item) && method_exists($item, 'getIdproducto')) {
                    $salida[] = [
                        'idproducto'   => $item->getIdproducto(),
                        'pronombre'    => $item->getPronombre(),
                        'prodetalle'   => $item->getProdetalle(),
                        'procantstock' => $item->getProcantstock(),
                    ];
                } else {
                    $salida[] = $item; // ya es array u otro formato
                }
            }
            $respuesta = $salida;
        }
        break;

    case 'alta':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->altaProducto($datos);
        break;

    case 'baja':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->bajaProducto($datos['id']);
        break;

    case 'editar':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->modificacionProducto($datos);
        break;


    case 'buscar':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->buscarProducto($datos['id']);
        if (is_array($respuesta) && count($respuesta) > 0) {
            $salida = [];
            foreach ($respuesta as $item) {
                if (is_object($item) && method_exists($item, 'getIdproducto')) {
                    $salida[] = [
                        'idproducto'   => $item->getIdproducto(),
                        'pronombre'    => $item->getPronombre(),
                        'prodetalle'   => $item->getProdetalle(),
                        'procantstock' => $item->getProcantstock(),
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
