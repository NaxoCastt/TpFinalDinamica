<?php
class AbmCompra
{
    // Método para cargar un objeto Compra desde un arreglo
private function cargarObjeto($param){
        $obj = null;
        
        // Verificamos si existen las claves necesarias o al menos el ID/Usuario
        if( array_key_exists('idcompra', $param) || array_key_exists('idusuario', $param) ){
            $obj = new Compra();
            
            $idcompra = $param['idcompra'] ?? null; // Si no viene, es null (para altas)
            $cofecha = $param['cofecha'] ?? date("Y-m-d H:i:s"); // Si no viene, fecha actual
            $idusuario = $param['idusuario'] ?? null; 
            
            // Llamamos a cargar con los 3 parámetros requeridos
            $obj->cargar($idcompra, $cofecha, $idusuario);
        }
        return $obj;
    }

    public function alta($param)
    {
        $resp = false;
        $elObjtCompra = new Compra();

        // CORRECCIÓN: Seteamos directamente el ID, no un objeto Usuario
        $elObjtCompra->setIdusuario($param['idusuario']);
        $elObjtCompra->setCofecha(date("Y-m-d H:i:s"));

        if ($elObjtCompra->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idcompra']))
                $where .= " and idcompra = " . $param['idcompra'];
            if (isset($param['idusuario']))
                $where .= " and idusuario = " . $param['idusuario'];
        }
        $arreglo = Compra::listar($where);
        return $arreglo;
    }
}
