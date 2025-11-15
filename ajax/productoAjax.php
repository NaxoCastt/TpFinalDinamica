
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

        $extensiones = ['jpg', 'png', 'webp', 'jpeg'];
        $carpeta = '../util/imagenesProductos/';

        foreach ($respuesta as $item) {
            $imagen = 'default.png';
            $extension = 'png';

            if (is_object($item) && method_exists($item, 'getIdproducto')) {
                foreach ($extensiones as $ext) {
                    $ruta = $carpeta . $item->getIdproducto() . '.' . $ext;
                    if (file_exists($ruta)) {
                        $imagen = $item->getIdproducto() . '.' . $ext;
                        $extension = $ext;
                        break;
                    }
                }

                $salida[] = [
                    'idproducto'   => $item->getIdproducto(),
                    'pronombre'    => $item->getPronombre(),
                    'prodetalle'   => $item->getProdetalle(),
                    'procantstock' => $item->getProcantstock(),
                    'imagen'       => $imagen,
                    'extension'    => $extension
                ];
            }
        }

        $respuesta = $salida;
        break;

    case 'listarSinStock':
        $datos = data_submitted();
        $respuesta = $obj->listarProductosSinStock();

        $extensiones = ['jpg', 'png', 'webp', 'jpeg'];
        $carpeta = '../util/imagenesProductos/';

        foreach ($respuesta as $item) {
            $imagen = 'default.png';
            $extension = 'png';

            if (is_object($item) && method_exists($item, 'getIdproducto')) {
                foreach ($extensiones as $ext) {
                    $ruta = $carpeta . $item->getIdproducto() . '.' . $ext;
                    if (file_exists($ruta)) {
                        $imagen = $item->getIdproducto() . '.' . $ext;
                        $extension = $ext;
                        break;
                    }
                }

                $salida[] = [
                    'idproducto'   => $item->getIdproducto(),
                    'pronombre'    => $item->getPronombre(),
                    'prodetalle'   => $item->getProdetalle(),
                    'procantstock' => $item->getProcantstock(),
                    'imagen'       => $imagen,
                    'extension'    => $extension
                ];
            }
        }

        $respuesta = $salida;
        break;


    case 'alta':
        $datos = data_submitted();
        $respuesta = $obj->altaProducto($datos);

        if ($respuesta > 0 && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreFinal = $respuesta . '.' . strtolower($ext);
            $destino = '../util/imagenesProductos/' . $nombreFinal;

            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($_FILES['imagen']['type'], $tiposPermitidos)) {
                move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
            }
        } else if ($respuesta <= 0) {

            $respuesta = false;
        }

        break;

    case 'baja':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->bajaProducto($datos['id']);
    
        break;

    case 'bajaDefinitiva':
        $inputJSON = file_get_contents('php://input');
        $datos = json_decode($inputJSON, true);
        $respuesta = $obj->bajaProductoFisico($datos['id']);
        if ($respuesta === true && isset($datos['id'])) {
            $id = $datos['id'];
            $carpeta = '../util/imagenesProductos/';
            $extensiones = ['jpg', 'jpeg', 'png', 'webp'];
            foreach ($extensiones as $ext) {
                $archivo = $carpeta . $id . '.' . $ext;
                if (file_exists($archivo)) {
                    unlink($archivo);
                }
            }
        }

        break;

    case 'editar':
        $datos = data_submitted();
        $modificado = $obj->modificacionProducto($datos);
        $idProducto = $datos['id'];
        $imagenGuardada = false;
        $extension = null;

        if (isset($datos['id']) && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreFinal = $idProducto . '.' . strtolower($ext);
            $destino = '../util/imagenesProductos/' . $nombreFinal;

            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($_FILES['imagen']['type'], $tiposPermitidos)) {
                $carpeta = '../util/imagenesProductos/';
                $extensiones = ['jpg', 'png', 'webp', 'jpeg'];
                foreach ($extensiones as $e) {
                    $archivoViejo = $carpeta . $idProducto . '.' . $e;
                    if (file_exists($archivoViejo)) {
                        unlink($archivoViejo);
                    }
                }

                $imagenGuardada = move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
                $extension = strtolower($ext);
            }
        }

        $respuesta = $idProducto
            ? ['success' => true, 'id' => $datos['id'], 'imagen' => $imagenGuardada ? $nombreFinal : null, 'extension' => $extension]
            : ['success' => false, 'message' => 'No se pudo editar el producto'];
        break;



    case 'buscar':
        $datos = data_submitted();
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
