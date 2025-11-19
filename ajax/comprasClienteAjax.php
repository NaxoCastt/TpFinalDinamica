<?php
include_once '../configuracion.php';

ob_start();

// Verificar sesión
$objSession = new Session();
if (!$objSession->validar()) {
    echo json_encode(['exito' => false, 'msg' => 'Sesión expirada']);
    exit;
}

$idUsuario = $objSession->getIdUsuario();
$datos = data_submitted();
$accion = $datos['accion'] ?? '';

$respuesta = [];

if ($accion == 'listar_mias') {
    $abmCompra = new ABMCompra();
    $abmEstado = new ABMCompraEstado();
    $abmItem = new ABMCompraItem();
    $abmProd = new abmProducto();

    // Buscar todas las compras de ESTE usuario
    $listaCompras = $abmCompra->buscar(['idusuario' => $idUsuario]);
    
    foreach ($listaCompras as $compra) {
        // Buscar estados de esta compra
        $listaEstados = $abmEstado->buscar(['idcompra' => $compra->getIdcompra()]);
        
        // Si tiene estados, es una compra realizada (no es el carrito activo)
        if (count($listaEstados) > 0) {
            // Obtenemos el último estado (el actual)
            // Asumimos que el último del array es el actual.
            $estadoActual = end($listaEstados);
            
            // Buscar items (productos)
            $items = $abmItem->buscar(['idcompra' => $compra->getIdcompra()]);
            $detalleItems = "";
            foreach ($items as $it) {
                $prod = $abmProd->buscarProducto($it->getIdproducto());
                $nombreProd = (count($prod) > 0) ? $prod[0]->getPronombre() : "Producto eliminado";
                // Formato visual simple para la celda
                $detalleItems .= "<div>• <strong>" . $nombreProd . "</strong> <span class='text-muted'>(x" . $it->getCicantidad() . ")</span></div>";
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
}

ob_end_clean();

// Enviamos SOLO el JSON puro
header('Content-Type: application/json');
echo json_encode($respuesta);
exit;

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