<?php
include_once "../../configuracion.php";
include_once "../../control/Session.php";
$objSession = new Session();
$objSession->cerrar();
header('Location: ../login.php');
?>