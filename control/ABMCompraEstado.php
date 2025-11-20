<?php
class AbmCompraEstado {
    
    public function alta($param){
        $resp = false;
        $obj = new CompraEstado();
        
        $obj->setIdcompra($param['idcompra']);
        $obj->setIdcompraestadotipo($param['idcompraestadotipo']);
        
        
        $fechaIni = isset($param['cefechaini']) ? $param['cefechaini'] : date("Y-m-d H:i:s");
        $fechaFin = isset($param['cefechafin']) ? $param['cefechafin'] : null;

        $obj->setCefechaini($fechaIni);
        $obj->setCefechafin($fechaFin);
        
        if($obj->insertar()){
            $resp = true;
        }
        return $resp;
    }

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