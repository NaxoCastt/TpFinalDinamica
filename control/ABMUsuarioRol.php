<?php

class AbmUsuarioRol
{

    /**
     * Carga un objeto UsuarioRol con los IDs de Usuario y Rol
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (
            array_key_exists('idusuario', $param) &&
            array_key_exists('idrol', $param)
        ) {
            // 1. Crear el objeto Usuario y setearle su ID
            $objUsuario = new Usuario();
            $objUsuario->setIdusuario($param['idusuario']);

            // 2. Crear el objeto Rol y setearle su ID
            $objRol = new Rol();
            $objRol->setIdrol($param['idrol']);

            // 3. Crear el objeto UsuarioRol y setearle los dos objetos
            $obj = new UsuarioRol();
            $obj->cargar($objUsuario, $objRol);
        }
        return $obj;
    }

    /**
     * Carga un objeto UsuarioRol solo con las claves
     */
    private function cargarObjetoConClave($param)
    {
        // Para esta clase, CargarObjeto y CargarObjetoConClave son idénticos
        return $this->cargarObjeto($param);
    }

    /**
     * Verifica que esté seteada la clave primaria (que es compuesta)
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $resp = true;
        }
        return $resp;
    }

    // --- CRUD ---

    /**
     * (Alta) Asigna un rol a un usuario
     */
    public function alta($param)
    {
        $resp = false;

        // Verificar si la asignación ya existe
        $existe = $this->buscar($param); // $param debe tener idusuario e idrol
        if (count($existe) <= 0) {
            // No existe, entonces la inserto
            $objUsuarioRol = $this->cargarObjeto($param);
            if ($objUsuarioRol != null && $objUsuarioRol->insertar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Baja) Quita un rol a un usuario
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtUsuarioRol = $this->cargarObjetoConClave($param);
            if ($elObjtUsuarioRol != null && $elObjtUsuarioRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Modificación) No se aplica
     * No tiene sentido "modificar" una asignación.
     * O se elimina (baja) o se crea una nueva (alta).
     */
    public function modificacion($param)
    {
        return false;
    }

    /**
     * (Búsqueda) Busca asignaciones
     * Puede buscar todos los roles de un usuario (pasando 'idusuario')
     * o todos los usuarios con un rol (pasando 'idrol')
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
        }
        $arreglo = Usuariorol::listar($where);
        return $arreglo;
    }
}
