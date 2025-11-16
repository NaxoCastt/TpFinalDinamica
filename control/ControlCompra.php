<?php
class ControlCompra {

    /**
     * Gestiona el cambio de estado de una compra.
     * Actualiza fechas, cambia el tipo y ejecuta acciones colaterales (como devolver stock).
     */
    public function cambiarEstado($idCompra, $nuevoTipoEstado) {
        $abmEstado = new ABMCompraEstado();
        $abmCompra = new ABMCompra();
        
        // 1. Buscamos el estado actual de la compra
        $listaEstados = $abmEstado->buscar(['idcompra' => $idCompra]);

        if (count($listaEstados) == 0) {
            return ['exito' => false, 'msg' => 'No se encontró el estado de la compra.'];
        }

        $objEstadoActual = $listaEstados[0];

        // 2. Definir lógica de fechas (Fecha Fin)
        // Si pasa a "Enviada" (3) o "Cancelada" (4), cerramos la fecha.
        $fechaFin = null;
        if ($nuevoTipoEstado == 3 || $nuevoTipoEstado == 4) {
            $fechaFin = date("Y-m-d H:i:s");
        }

        // 3. Preparar actualización
        $paramUpdate = [
            'idcompraestado' => $objEstadoActual->getIdcompraestado(),
            'idcompra' => $idCompra,
            'idcompraestadotipo' => $nuevoTipoEstado,
            'cefechaini' => $objEstadoActual->getCefechaini(), // Mantenemos inicio original
            'cefechafin' => $fechaFin
        ];

        // 4. Ejecutar modificación
        if ($abmEstado->modificacion($paramUpdate)) {
            
            // ACCIÓN COLATERAL: Devolver Stock si se cancela
            if ($nuevoTipoEstado == 4) { 
                 $this->devolverStock($idCompra);
            }

            // ACCIÓN COLATERAL: Aquí podrías agregar envío de mails en el futuro
            
            return ['exito' => true, 'msg' => 'Estado actualizado correctamente.'];
        } else {
            return ['exito' => false, 'msg' => 'Error al actualizar en base de datos.'];
        }
    }

    /**
     * Restaura el stock de los productos de una compra.
     * (Método privado o público según necesidad, lo dejamos público por si se usa fuera)
     */
    public function devolverStock($idCompra) {
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
?>