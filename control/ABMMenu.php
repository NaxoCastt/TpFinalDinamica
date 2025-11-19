<?php

class ABMMenu
{

    public function alta($param)
    {
        $resp = false;
        $obj = new Menu();
        $idpadre = $param['idpadre'];
        $idpadreSQL = ($idpadre === null || $idpadre === '') ? "NULL" : intval($idpadre);

        $obj->cargar(0, $param['menombre'], $param['medescripcion'], $idpadreSQL, NULL);
        if ($obj->insertar()) {
            $resp = true;
        }
        error_log("Resultado de alta: " . var_export($resp, true));

        return $resp;
    }

    public function baja($idmenu)
    {
        $resp = false;
        $obj = new Menu();
        if ($obj->buscar($idmenu)) {
            $obj->setMedeshabilitado(date('Y-m-d H:i:s'));
            if ($obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function bajaFisica($idmenu)
    {

        $resp = false;
        $obj = new Menu();
        if ($obj->buscar($idmenu)) {
            if ($obj->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        $obj = new Menu();
        $idpadre = $param['idpadre'];
        if ($idpadre === "null" || $idpadre === "") {
            $idpadre = null;
        }
        
        if ($obj->buscar($param['idmenu'])) {
            $obj->cargar($param['idmenu'], $param['menombre'], $param['medescripcion'], $idpadre,  $param['medeshabilitado']);
            $resp =$obj->modificar();

            
        }
        return $resp;
    }

    public function buscar($idmenu)
    {
        $respObj = null;
        $obj = new Menu();
        if ($obj->buscar($idmenu)) {
            $respObj = $obj;
        }
        return $respObj;
    }

    public function listar($where)
    {
        $obj = new Menu();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}
