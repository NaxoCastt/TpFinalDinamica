<?php

class AbmRol
{

    /**
     * Carga un objeto Rol con los datos del parámetro
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('rodescripcion', $param)) {
            $obj = new Rol();
            $id = $param['idrol'] ?? 0; 
            $obj->cargar($id, $param['rodescripcion']);
        }
        return $obj;
    }

    /**
     * Carga un objeto Rol solo con la clave (idrol)
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idrol'])) {
            $obj = new Rol();
            $obj->cargar($param['idrol'], null);
        }
        return $obj;
    }

    

    /**
     * Verifica que esté seteada la clave primaria
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idrol'])) {
            $resp = true;
        }
        return $resp;
    }

    // --- CRUD ---


    public function listar($x){

        $objProducto = new Rol();
        $colProductos = $objProducto->listar($x);
        return $colProductos;
    }
    /**
     * Inserta un rol en la BD
     */


    public function alta($param)
    {
        $resp = false;


        $existe = $this->buscar(['rodescripcion' => $param['rodescripcion']]);
        if (count($existe) <= 0) {

            $objRol = $this->cargarObjeto($param);
            if ($objRol != null && $objRol->insertar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Baja) Elimina un rol de la BD
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtRol = $this->cargarObjetoConClave($param);
            if ($elObjtRol != null && $elObjtRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Modificación) Modifica un rol de la BD
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtRol = $this->cargarObjeto($param);
            if ($elObjtRol != null && $elObjtRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Búsqueda) Busca roles en la BD
     */
    public function buscar($param)
    {
        $where = " true "; 
        if ($param != NULL) {
            if (isset($param['idrol'])) {
                $where .= " AND idrol =" . $param['idrol'];
            }
            if (isset($param['rodescripcion'])) {
                $where .= " AND rodescripcion ='" . $param['rodescripcion'] . "'";
            }
        }
        $arreglo = Rol::listar($where);
        return $arreglo;
    }
}
?>