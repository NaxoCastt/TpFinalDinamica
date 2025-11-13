<?php header('Content-Type: text/html; charset=utf-8');
header ("Cache-Control: no-cache, must-revalidate ");
session_start();
/////////////////////////////
// CONFIGURACION APP//
/////////////////////////////

$PROYECTO ='TPFINAL';

//variable que almacena el directorio del proyecto
$ROOT = dirname(__FILE__).'/';
$_SESSION['ROOT'] = $ROOT;

include_once($ROOT.'util/funciones.php');



// Variable que define la pagina de autenticacion del proyecto
$INICIO = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/vista/index.php";

// variable que define la pagina principal del proyecto (menu principal)
$PRINCIPAL = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/index.php";


$_SESSION['ROOT']=$ROOT;

?>