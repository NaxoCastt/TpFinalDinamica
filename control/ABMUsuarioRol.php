<?php
class AbmUsuarioRol {

    /**
     * Carga el objeto Usuariorol con IDs
     */
    private function cargarObjeto($param){
        $obj = null;
        if( isset($param['idusuario']) && isset($param['idrol']) ){
            $obj = new Usuariorol();
            // En este proyecto, cargar recibe IDs directos
            $obj->cargar($param['idusuario'], $param['idrol']);
        }
        return $obj;
    }
    
    private function cargarObjetoConClave($param){
        return $this->cargarObjeto($param);
    }
    
    private function seteadosCamposClaves($param){
        return (isset($param['idusuario']) && isset($param['idrol']));
    }
    
    public function alta($param){
        $resp = false;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()){
            $resp = true;
        }
        return $resp;
    }
    
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null && $obj->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    public function modificacion($param){
        return false; 
    }
    
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario =".$param['idusuario'];
            if  (isset($param['idrol']))
                 $where.=" and idrol =".$param['idrol'];
        }
        $arreglo = Usuariorol::listar($where);
        return $arreglo;
    }
}
?>