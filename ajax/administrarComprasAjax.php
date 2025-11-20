<?php
include_once '../configuracion.php';
ob_start();
$objSession = new Session();

if (!$objSession->activa() || !in_array('Admin', $objSession->getRol())) {
    ob_end_clean(); echo json_encode(['exito' => false]); exit;
}

$datos = data_submitted();
$accion = $datos['accion'] ?? '';
$respuesta = [];

switch ($accion) {
    case 'listarCompras':
        $abmCompra = new ABMCompra();
        $abmEstado = new ABMCompraEstado();
        $abmItem = new ABMCompraItem();
        $abmProducto = new abmProducto();
        $abmUsuario = new AbmUsuario();
        
        $listaCompras = $abmCompra->buscar(null);
        $salida = [];

        foreach ($listaCompras as $compra) {
            $listaEstados = $abmEstado->buscar(['idcompra' => $compra->getIdcompra()]);
            if (count($listaEstados) > 0) {
                $objEstado = end($listaEstados); // Último estado
                $objUser = $abmUsuario->buscar(['idusuario' => $compra->getIdusuario()]);
                $nombreUsuario = (count($objUser) > 0) ? $objUser[0]->getUsnombre() : "ID: " . $compra->getIdusuario();

                $items = $abmItem->buscar(['idcompra' => $compra->getIdcompra()]);
                $detalleItems = "";
                foreach ($items as $it) {
                    $prod = $abmProducto->buscarProducto($it->getIdproducto());
                    $nombreProd = count($prod) > 0 ? $prod[0]->getPronombre() : "X";
                    $detalleItems .= "• $nombreProd (x" . $it->getCicantidad() . ")<br>";
                }

                $salida[] = [
                    'idcompra' => $compra->getIdcompra(),
                    'usnombre' => $nombreUsuario,
                    'fechainicio' => $objEstado->getCefechaini(),
                    'fechafin' => $objEstado->getCefechafin(), // Mostramos directo de BD
                    'idestadotipo' => $objEstado->getIdcompraestadotipo(),
                    'estadodescripcion' => obtenerDescripcionEstado($objEstado->getIdcompraestadotipo()),
                    'items' => $detalleItems
                ];
            }
        }
        $respuesta = $salida;
        break;

    case 'cambiarEstado':
        $control = new ControlCompra();
        $respuesta = $control->cambiarEstado($datos['idcompra'], intval($datos['idestadotipo']));
        break;

    case 'verHistorial':
        $abmEstado = new ABMCompraEstado();
        $lista = $abmEstado->buscar(['idcompra' => $datos['idcompra']]);
        $historial = [];
        foreach($lista as $est){
            $historial[] = [
                'estado' => obtenerDescripcionEstado($est->getIdcompraestadotipo()),
                'inicio' => $est->getCefechaini(),
                'fin' => $est->getCefechafin() // Directo de BD
            ];
        }
        $respuesta = $historial;
        break;
}

ob_end_clean();
header('Content-Type: application/json');
echo json_encode($respuesta);

function obtenerDescripcionEstado($tipo) {
    switch ($tipo) {
        case 1: return 'Iniciada'; case 2: return 'Aceptada'; case 3: return 'Enviada'; case 4: return 'Cancelada'; default: return 'Desconocido';
    }
}
?>