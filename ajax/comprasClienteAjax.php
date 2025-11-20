<?php
include_once '../configuracion.php';
ob_start();
$objSession = new Session();

if (!$objSession->validar()) { echo json_encode([]); exit; }
$idUsuario = $objSession->getIdUsuario();
$datos = data_submitted();
$accion = $datos['accion'] ?? '';

if ($accion == 'listar_mias') {
    $abmCompra = new ABMCompra();
    $abmEstado = new ABMCompraEstado();
    $abmItem = new ABMCompraItem();
    $abmProd = new abmProducto();
    $listaCompras = $abmCompra->buscar(['idusuario' => $idUsuario]);
    $respuesta = [];

    foreach ($listaCompras as $compra) {
        $listaEstados = $abmEstado->buscar(['idcompra' => $compra->getIdcompra()]);
        if (count($listaEstados) > 0) {
            $estadoActual = end($listaEstados);
            $items = $abmItem->buscar(['idcompra' => $compra->getIdcompra()]);
            $detalleItems = "";
            foreach ($items as $it) {
                $prod = $abmProd->buscarProducto($it->getIdproducto());
                $nombreProd = (count($prod) > 0) ? $prod[0]->getPronombre() : "X";
                $detalleItems .= "<div>• $nombreProd (x" . $it->getCicantidad() . ")</div>";
            }
            $respuesta[] = [
                'idcompra' => $compra->getIdcompra(),
                'fecha' => $compra->getCofecha(),
                'items' => $detalleItems,
                'idestadotipo' => $estadoActual->getIdcompraestadotipo(),
                'estado_desc' => obtenerDescripcionEstado($estadoActual->getIdcompraestadotipo()),
                'fecha_estado' => $estadoActual->getCefechaini()
            ];
        }
    }
} elseif ($accion == 'verHistorial') {
    $abmEstado = new ABMCompraEstado();
    $lista = $abmEstado->buscar(['idcompra' => $datos['idcompra']]);
    $respuesta = [];
    foreach($lista as $est){
        $respuesta[] = [
            'estado' => obtenerDescripcionEstado($est->getIdcompraestadotipo()),
            'inicio' => $est->getCefechaini(),
            'fin' => $est->getCefechafin() // Directo de BD
        ];
    }
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