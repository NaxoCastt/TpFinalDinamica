<?php 
    
    class ABMMenuRol{

        public function alta($param){
            $resp = false;
            $obj = new MenuRol();
            $obj->cargar( $param['idrol'], $param['idmenu']);
            if($obj->insertar()){
                $resp = true;
            }   
            return $resp;
        }

        public function baja($idmenurole){
            $resp = false;
            $obj = new MenuRol();
            if($obj->buscar($idmenurole)){
                if($obj->eliminar()){
                    $resp = true;
                }
            }
            return $resp;
        }

        public function modificacion($param){
            $resp = false;
            $obj = new MenuRol();
            if($obj->buscar($param['idmenurole'])){
                $obj->cargar($param['idmenu'], $param['idrol']);
                if($obj->modificar()){
                    $resp = true;
                }
            }
            return $resp;
        }

        public function buscar($idrol){
            $respObj = null;
            $obj = new MenuRol();
            if($obj->buscar($idrol)){
                $respObj = $obj;
            }
            return $respObj;
        }

        public function listar($where = ""){
            $obj = new MenuRol();
            $arreglo = $obj->listar($where);
            return $arreglo;
        }

        
    }