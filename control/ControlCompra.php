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

        // Buscamos el estado actual
        $listaEstados = $abmEstado->buscar(['idcompra' => $idCompra]);
        $objEstadoActual = $listaEstados[0];

        // Definir lógica de fechas
        $fechaFin = null;
        if ($nuevoTipoEstado == 3 || $nuevoTipoEstado == 4) {
            $fechaFin = date("Y-m-d H:i:s");
        }
        $paramUpdate = [
            'idcompraestado' => $objEstadoActual->getIdcompraestado(),
            'idcompra' => $objEstadoActual->getIdcompra(),
            'idcompraestadotipo' => $nuevoTipoEstado, // El nuevo estado
            'cefechaini' => $objEstadoActual->getCefechaini(), // La fecha de inicio original
            'cefechafin' => $fechaFin // La nueva fecha de fin (o null)
        ];

        // Ejecutar modificación
        if ($abmEstado->modificacion($paramUpdate)) {

            // ACCIÓN COLATERAL: Devolver Stock si se cancela
            if ($nuevoTipoEstado == 4) {
                $this->devolverStock($idCompra);
            }

            // INICIO ENVÍO DE CORREO (ESTADOS 2, 3, 4) 
            try {
                // Buscamos los datos del usuario para enviarle el mail
                $compra = $abmCompra->buscar(['idcompra' => $idCompra])[0];
                $abmUsuario = new AbmUsuario();
                $usuario = $abmUsuario->buscar(['idusuario' => $compra->getIdusuario()])[0];

                $asunto = "";
                $cuerpoHTML = "";
                $fechaActual = Carbon::now()->isoFormat('dddd D [de] MMMM [de] YYYY');
                switch ($nuevoTipoEstado) {
                    case 2: // Aceptada
                        $asunto = "¡Tu pedido fue aceptado!";
                        $cuerpoHTML = "<h1>Hola " . $usuario->getUsnombre() . "</h1>" .
                            "<p>Tu pedido (ID: " . $idCompra . ") fue aceptada el ".  $fechaActual ." y está siendo preparado.</p>";
                        break;

                    case 3: // Enviada
                        $asunto = "¡Tu pedido está en camino!";
                        $cuerpoHTML = "<h1>Hola " . $usuario->getUsnombre() . "</h1>" .
                            "<p>Tu pedido (ID: " . $idCompra . ") ha sido enviado el ".  $fechaActual  .".</p>" .
                            "<p>¡Gracias por tu compra!</p>";
                        break;

                    case 4: // Cancelada
                        $asunto = "Tu pedido fue cancelado";
                        $cuerpoHTML = "<h1>Hola " . $usuario->getUsnombre() . "</h1>" .
                            "<p>Lamentamos informarte que tu pedido (ID: " . $idCompra . ") ha sido cancelado.</p>" .
                            "<p>Si crees que es un error, contacta a soporte.</p>";
                        break;
                }

                // Si se definió un asunto, se envía el correo
                if ($asunto != "") {
                    ControlCorreo::enviarCorreo($usuario->getUsmail(), $usuario->getUsnombre(), $asunto, $cuerpoHTML);
                }
            } catch (Exception $e) {
                // El estado se cambió bien, pero el mail falló
                error_log("Falló el envío de correo al cambiar estado: " . $e->getMessage());
            }
            // ----------- FIN ENVÍO DE CORREO -----------

            return ['exito' => true, 'msg' => 'Estado actualizado correctamente.'];
        } else {
            return ['exito' => false, 'msg' => 'Error al actualizar en base de datos.'];
        }
    }

    /**
     * Restaura el stock de los productos de una compra.
     * (Método privado o público según necesidad, lo dejamos público por si se usa fuera)
     */
    public function devolverStock($idCompra)
    {
        $abmItem = new ABMCompraItem();
        $abmProd = new abmProducto();

        $items = $abmItem->buscar(['idcompra' => $idCompra]);

        foreach ($items as $item) {
            $listaProd = $abmProd->buscarProducto($item->getIdproducto());

            if (count($listaProd) > 0) {
                $objProducto = $listaProd[0];

                // Cálculo de stock restaurado
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
