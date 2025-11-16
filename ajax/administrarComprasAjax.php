<?php
include_once '../configuracion.php';

ob_start();

$objSession = new Session();
// Validación de seguridad
if (!$objSession->activa() || !in_array('Admin', $objSession->getRol())) {
    ob_end_clean();
    echo json_encode(['exito' => false, 'msg' => 'Acceso denegado']);
    exit;
}

$datos = data_submitted();
$accion = $datos['accion'] ?? '';
$respuesta = ['exito' => false, 'msg' => 'Error desconocido'];

switch ($accion) {
    case 'listarCompras':
        $abmCompra = new ABMCompra();
        $abmEstado = new ABMCompraEstado();
        $abmItem = new ABMCompraItem();
        $abmProducto = new abmProducto();
        
        $listaCompras = $abmCompra->buscar(null);
        $salida = [];

        foreach ($listaCompras as $compra) {
            $listaEstados = $abmEstado->buscar(['idcompra' => $compra->getIdcompra()]);
            
            if (count($listaEstados) > 0) {
                $objEstado = $listaEstados[0];
                
                $items = $abmItem->buscar(['idcompra' => $compra->getIdcompra()]);
                $detalleItems = "";
                foreach ($items as $it) {
                    $prod = $abmProducto->buscarProducto($it->getIdproducto());
                    $nombreProd = count($prod) > 0 ? $prod[0]->getPronombre() : "Producto eliminado";
                    $detalleItems .= "• $nombreProd (x" . $it->getCicantidad() . ")<br>";
                }

                $salida[] = [
                    'idcompra' => $compra->getIdcompra(),
                    'idusuario' => $compra->getIdusuario(),
                    'fechainicio' => $objEstado->getCefechaini(),
                    'fechafin' => $objEstado->getCefechafin(),
                    'idestadotipo' => $objEstado->getIdcompraestadotipo(),
                    'estadodescripcion' => obtenerDescripcionEstado($objEstado->getIdcompraestadotipo()),
                    'items' => $detalleItems
                ];
            }
        }
        $respuesta = $salida;
        break;

    case 'cambiarEstado':
        if (isset($datos['idcompra']) && isset($datos['idestadotipo'])) {
            
            $controlCompra = new ControlCompra();
            $respuesta = $controlCompra->cambiarEstado($datos['idcompra'], intval($datos['idestadotipo']));
            
        } else {
            $respuesta = ['exito' => false, 'msg' => 'Faltan datos'];
        }
        break;
}

ob_end_clean();
header('Content-Type: application/json');
echo json_encode($respuesta);

function obtenerDescripcionEstado($tipo) {
    switch ($tipo) {
        case 1: return 'Iniciada';
        case 2: return 'Aceptada';
        case 3: return 'Enviada';
        case 4: return 'Cancelada';
        default: return 'Desconocido';
    }
}
?>