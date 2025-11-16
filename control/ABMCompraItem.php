<?php
class AbmCompraItem {
    
    public function alta($param){
        $resp = false;
        $obj = new CompraItem();
        $obj->setIdcompra($param['idcompra']);
        $obj->setIdproducto($param['idproducto']);
        $obj->setCicantidad($param['cicantidad']);
        
        if($obj->insertar()){
            $resp = true;
        }
        return $resp;
    }

    public function modificacion($param){
        $resp = false;
        // Buscamos el item por ID para no perder referencias (aunque en este caso solo actualizamos cantidad)
        $arreglo = $this->buscar(['idcompraitem'=>$param['idcompraitem']]);
        if(count($arreglo) > 0){
            $obj = $arreglo[0];
            $obj->setCicantidad($param['cicantidad']);
            // Es importante que los otros IDs sigan estando, el cargar del objeto ya los tiene
            if($obj->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }

    public function baja($param){
        $resp = false;
        if(isset($param['idcompraitem'])){
            $obj = new CompraItem();
            $obj->setIdcompraitem($param['idcompraitem']);
            if($obj->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if (isset($param['idcompraitem'])) $where.=" and idcompraitem = ".$param['idcompraitem'];
            if (isset($param['idcompra'])) $where.=" and idcompra = ".$param['idcompra'];
            if (isset($param['idproducto'])) $where.=" and idproducto = ".$param['idproducto'];
        }
        $obj = new CompraItem();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}
?>