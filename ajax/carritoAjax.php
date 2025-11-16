<?php
include_once '../configuracion.php';


ob_start();

$datos = data_submitted();
$accion = $datos['accion'] ?? '';
$objSession = new Session();
$respuesta = ['exito' => false, 'msg' => 'Error desconocido'];

// Validar Sesión
if (!$objSession->activa()) {
    // Limpiamos cualquier basura antes de responder
    ob_end_clean();
    echo json_encode(['exito' => false, 'msg' => 'Debe iniciar sesión']);
    exit;
}

$idUsuario = $objSession->getUsuario()->getIdusuario();
$controlCarrito = new ControlCarrito();

switch ($accion) {
    case 'agregar':
        if (isset($datos['idproducto']) && isset($datos['cantidad'])) {
            $respuesta = $controlCarrito->agregarProducto($idUsuario, $datos['idproducto'], $datos['cantidad']);
        } else {
            $respuesta['msg'] = "Faltan datos obligatorios.";
        }
        break;

    case 'listar':
        $objCompra = $controlCarrito->buscarCarritoActivo($idUsuario);
        $listaSalida = [];

        if ($objCompra != null) {
            $abmItem = new ABMCompraItem();
            $abmProd = new abmProducto();
            $items = $abmItem->buscar(['idcompra' => $objCompra->getIdcompra()]);

            foreach ($items as $item) {
                // Buscar datos completos del producto
                $prodArray = $abmProd->buscarProducto($item->getIdproducto());
                if (count($prodArray) > 0) {
                    $prodObj = $prodArray[0];

                    // Lógica para buscar la imagen
                    $imagen = 'default.png';
                    $carpeta = '../util/imagenesProductos/';
                    $exts = ['jpg', 'png', 'jpeg', 'webp'];
                    foreach ($exts as $e) {
                        if (file_exists($carpeta . $prodObj->getIdproducto() . '.' . $e)) {
                            $imagen = $prodObj->getIdproducto() . '.' . $e;
                            break;
                        }
                    }

                    $listaSalida[] = [
                        'idcompraitem' => $item->getIdcompraitem(),
                        'idproducto' => $prodObj->getIdproducto(),
                        'nombre' => $prodObj->getPronombre(),
                        'detalle' => $prodObj->getProdetalle(),
                        'cantidad' => $item->getCicantidad(),
                        'stock' => $prodObj->getProcantstock(),
                        'imagen' => $imagen
                    ];
                }
            }
        }
        // Si no hay carrito o está vacío, devuelve array vacío []
        $respuesta = $listaSalida;
        break;

    case 'borrar':
        if (isset($datos['idcompraitem'])) {
            if ($controlCarrito->quitarProducto($datos['idcompraitem'])) {
                $respuesta = ['exito' => true, 'msg' => 'Eliminado'];
            } else {
                $respuesta = ['exito' => false, 'msg' => 'Error al eliminar'];
            }
        } else {
            $respuesta['msg'] = "Falta el ID del item.";
        }
        break;

    case 'modificarCantidad':
        if (isset($datos['idproducto']) && isset($datos['cantidad'])) {
            $nuevaCantidad = intval($datos['cantidad']);
            if ($nuevaCantidad > 0) {
                $respuesta = $controlCarrito->modificarCantidad($idUsuario, $datos['idproducto'], $nuevaCantidad);
            } else {
                $respuesta['msg'] = "La cantidad debe ser mayor a 0.";
            }
        } else {
            $respuesta['msg'] = "Faltan datos.";
        }
        break;

    case 'finalizarCompra':
        // Validamos que haya sesión 
        $res = $controlCarrito->finalizarCompra($idUsuario);
        $respuesta = $res;
        break;

    default:
        $respuesta['msg'] = "Acción no válida.";
        break;
}
ob_end_clean();

// Enviamos SOLO el JSON puro
header('Content-Type: application/json');
echo json_encode($respuesta);
exit;
