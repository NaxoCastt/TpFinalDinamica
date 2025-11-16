<?php
class AbmCompraEstado {
    
    public function alta($param){
        $resp = false;
        $obj = new CompraEstado();
        
        $obj->setIdcompra($param['idcompra']);
        $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
        $obj->setCefechaini(date("Y-m-d H:i:s"));
        $obj->setCefechafin(null);
        
        if($obj->insertar()){
            $resp = true;
        }
        return $resp;
    }

    /**
     * Modifica el estado existente.
     * Espera un array con: idcompraestado, idcompra, idcompraestadotipo, cefechaini, cefechafin
     */
    public function modificacion($param){
        $resp = false;
        if (isset($param['idcompraestado'])){
            $obj = new CompraEstado();
            $obj->cargar(
                $param['idcompraestado'],
                $param['idcompra'],
                $param['idcompraestadotipo'],
                $param['cefechaini'],
                $param['cefechafin']
            );
            if($obj->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if (isset($param['idcompra'])) $where.=" and idcompra = ".$param['idcompra'];
            if (isset($param['idcompraestado'])) $where.=" and idcompraestado = ".$param['idcompraestado'];
        }
        $obj = new CompraEstado();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}
?>