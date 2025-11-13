<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../configuracion.php';

$datos = data_submitted();
$accion = $datos['accion'];
$obj = new abmProducto();
$respuesta = null;
switch ($accion) {

    case 'listar':
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
        $respuesta = $obj->altaProducto($datos);
        break;

    case 'baja':
        $respuesta = $obj->bajaProducto($datos['idproducto']);
        break;

    case 'buscar':
        $respuesta = $obj->buscarProducto($datos['idproducto']);
        break;

    default:
        $respuesta = ["error" => "Accion desconocida"];
}


header('Content-Type: application/json');
echo json_encode($respuesta);
