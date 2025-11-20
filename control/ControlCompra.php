<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;

Carbon::setLocale('es');
class ControlCompra
{
    public function cambiarEstado($idCompra, $nuevoTipoEstado)
    {
        $abmEstado = new ABMCompraEstado();
        $abmCompra = new ABMCompra();

        // 1. Buscamos el historial completo para obtener el ÚLTIMO estado
        $listaEstados = $abmEstado->buscar(['idcompra' => $idCompra]);
        
        $fechaInicioNuevo = date("Y-m-d H:i:s"); // Por defecto si no hay anterior (raro)
        
        if (count($listaEstados) > 0) {
            // Obtenemos el último estado registrado
            $estadoAnterior = end($listaEstados);
            
            // REGLA DE NEGOCIO: Fecha Inicio del nuevo = Fecha Fin del anterior
            $fechaFinAnterior = $estadoAnterior->getCefechafin();
            
            // Si por alguna razón el anterior fuera null (casos viejos), usamos 'ahora'
            if ($fechaFinAnterior != null) {
                $fechaInicioNuevo = $fechaFinAnterior;
            }
        }

        // REGLA DE NEGOCIO: Fecha Fin del nuevo = Fecha Actual Estática
        $fechaFinNuevo = date("Y-m-d H:i:s");

        $paramNew = [
            'idcompra' => $idCompra,
            'idcompraestadotipo' => $nuevoTipoEstado,
            'cefechaini' => $fechaInicioNuevo,
            'cefechafin' => $fechaFinNuevo
        ];

        if ($abmEstado->alta($paramNew)) {

            if ($nuevoTipoEstado == 4) { // Cancelada
                $this->devolverStock($idCompra);
            }

            $this->enviarNotificacionCorreo($idCompra, $nuevoTipoEstado, $abmCompra);

            return ['exito' => true, 'msg' => 'Estado actualizado correctamente.'];
        } else {
            return ['exito' => false, 'msg' => 'Error al crear el nuevo estado.'];
        }
    }

    private function enviarNotificacionCorreo($idCompra, $nuevoTipoEstado, $abmCompra){
        // (Misma lógica de correo que tenías antes...)
        try {
            $compra = $abmCompra->buscar(['idcompra' => $idCompra])[0];
            $abmUsuario = new AbmUsuario();
            $usuario = $abmUsuario->buscar(['idusuario' => $compra->getIdusuario()])[0];

            $asunto = ""; $cuerpoHTML = "";
            // ... Configurar asuntos según estado ...
             switch ($nuevoTipoEstado) {
                case 2: $asunto = "Pedido Aceptado"; $cuerpoHTML = "Tu pedido #$idCompra fue aceptado."; break;
                case 3: $asunto = "Pedido Enviado"; $cuerpoHTML = "Tu pedido #$idCompra fue enviado."; break;
                case 4: $asunto = "Pedido Cancelado"; $cuerpoHTML = "Tu pedido #$idCompra fue cancelado."; break;
            }
            if ($asunto != "") {
                ControlCorreo::enviarCorreo($usuario->getUsmail(), $usuario->getUsnombre(), $asunto, $cuerpoHTML);
            }
        } catch (Exception $e) {}
    }

    public function devolverStock($idCompra)
    {
        $abmItem = new ABMCompraItem();
        $abmProd = new abmProducto();
        $items = $abmItem->buscar(['idcompra' => $idCompra]);

        foreach ($items as $item) {
            $listaProd = $abmProd->buscarProducto($item->getIdproducto());
            if (count($listaProd) > 0) {
                $objProducto = $listaProd[0];
                $nuevoStock = $objProducto->getProcantstock() + $item->getCicantidad();
                $datosProd = [
                    'idproducto' => $objProducto->getIdproducto(),
                    'pronombre' => $objProducto->getPronombre(),
                    'prodetalle' => $objProducto->getProdetalle(),
                    'procantstock' => $nuevoStock
                ];
                $abmProd->modificacionProducto($datosProd);
            }
        }
    }
}
?>