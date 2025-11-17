<?php 

    class ABMMenu{

        public function alta($param){
            $resp = false;
            $obj = new Menu();
            $obj->cargar(0, $param['menombre'], $param['medescripcion'], $param['idpadre'], Null);
            if($obj->insertar()){
                $resp = true;
            }   
            return $resp;
        }

        public function baja($idmenu){
            $resp = false;
            $obj = new Menu();
            if($obj->buscar($idmenu)){
                $obj->setMedeshabilitado(date('Y-m-d H:i:s'));
                if($obj->modificar()){
                    $resp = true;
                }
            }
            return $resp;
        }

        public function modificacion($param){
            $resp = false;
            $obj = new Menu();
            if($obj->buscar($param['idmenu'])){
                $obj->cargar($param['idmenu'], $param['menombre'], $param['medescripcion'], $param['idpadre'],  Null);
                if($obj->modificar()){
                    $resp = true;
                }
            }
            return $resp;
        }

        public function buscar($idmenu){
            $respObj = null;
            $obj = new Menu();
            if($obj->buscar($idmenu)){
                $respObj = $obj;
            }
            return $respObj;
        }

        public function listar($where){
            $obj = new Menu();
            $arreglo = $obj->listar($where);
            return $arreglo;
        }

        public function listarMenus($idRol,$idMenus ){

            $objMenu = new Menu();
            $objMenuRol = new Menurol();


        }

        
    }