<?php
class AbmCompraEstado {
    public function alta($param){
        $resp = false;
        $obj = new CompraEstado();
        
        // CORRECCIÓN: Seteamos IDs
        $obj->setIdcompra($param['idcompra']);
        $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
        
        $obj->setCefechaini(date("Y-m-d H:i:s"));
        $obj->setCefechafin(null);
        
        if($obj->insertar()){
            $resp = true;
        }
        return $resp;
    }

    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if (isset($param['idcompra'])) $where.=" and idcompra = ".$param['idcompra'];
            if (isset($param['cefechafin'])) {
                if($param['cefechafin'] == 'null') $where.=" and cefechafin IS NULL";
            }
            // Agregamos filtro por tipo si hace falta
            if (isset($param['idcompraestadotipo'])) $where.=" and idcompraestadotipo = ".$param['idcompraestadotipo'];
        }
        $obj = new CompraEstado();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}
?>