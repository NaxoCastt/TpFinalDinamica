<?php
include_once "../../configuracion.php";
$datos = data_submitted();
$objSession = new Session();
$respuesta = ['exito' => false, 'msg' => ''];

if ($objSession->activa()) {
    $idUsuario = $objSession->getUsuario()->getidusuario();
    $control = new ControlCarrito();
    
    $idProducto = $datos['idproducto'];
    $cantidad = $datos['cantidad']; // Puede ser 1 o más
    
    $respuesta = $control->agregarProducto($idUsuario, $idProducto, $cantidad);
} else {
    $respuesta['msg'] = 'Debes iniciar sesión.';
}

echo json_encode($respuesta);
?>