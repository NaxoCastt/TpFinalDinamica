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

        public function baja($idmenu, $idrol){
            $resp = false;
            $obj = new MenuRol();
            if($obj->buscar($idrol, $idmenu)){
                if($obj->eliminar()){
                    $resp = true;
                }
            }
            return $resp;
        }

        public function modificacion($param){
            $resp = false;
            $obj = new MenuRol();

            if($obj->buscar($param['idrolOriginal'], $param['idmenuOriginal'])){
                $obj->setOriginal($param['idmenuOriginal'], $param['idrolOriginal']);
                $obj->cargar($param['idmenuNuevo'], $param['idrolNuevo']);

                if($obj->modificar()){
                    $resp = true;
                }
            }
            return $resp;
        }

        public function buscar($idrol, $idmenu){
            $respObj = null;
            $obj = new MenuRol();
            if($obj->buscar($idrol, $idmenu)){
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