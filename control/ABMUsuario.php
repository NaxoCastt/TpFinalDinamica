<?php
class AbmUsuario
{

    /**
     * Carga un objeto Usuario con todos los datos
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (
            array_key_exists('usnombre', $param) &&
            array_key_exists('usmail', $param)
        ) {
            $obj = new Usuario();
            $id = $param['idusuario'] ?? 0;

            // Se toma la contraseña de $param (que vendrá en texto plano)
            $pass = $param['uspass'] ?? null;

            $deshabilitado = $param['usdeshabilitado'] ?? null;

            $obj->cargar(
                $id,
                $param['usnombre'],
                $pass, // Se setea el texto plano
                $param['usmail'],
                $deshabilitado
            );
        }
        return $obj;
    }

    /**
     * Carga un objeto Usuario solo con la clave (idusuario)
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idusuario'])) {
            $obj = new Usuario();
            $obj->cargar($param['idusuario'], null, null, null, null);
        }
        return $obj;
    }

    /**
     * Verifica que esté seteada la clave primaria
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario'])) {
            $resp = true;
        }
        return $resp;
    }

    // --- CRUD ---

    /**
     * (Alta) Inserta un usuario en la BD
     */
    public function alta($param)
    {
        $resp = false; 

        // Verificar si ya existe el email
        $existe = $this->buscar(['usmail' => $param['usmail']]);
        if (count($existe) <= 0) {

            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null && $objUsuario->insertar()) {
                //En lugar de true, devolvemos el ID
                $resp = $objUsuario->getIdusuario(); 
            }
        }
        // Si el email ya existe, $resp sigue siendo 'false'
        return $resp;
    }

    /**
     * (Baja) Elimina un usuario de la BD
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtUsuario = $this->cargarObjetoConClave($param);
            if ($elObjtUsuario != null && $elObjtUsuario->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Modificación) Modifica un usuario de la BD
     */
public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {

            // Buscamos el objeto actual en la BD para recuperar datos que no vengan en el formulario
            $objViejoArray = $this->buscar(['idusuario' => $param['idusuario']]);

            if (count($objViejoArray) > 0) {
                $objViejo = $objViejoArray[0];

            
                if (!isset($param['uspass']) || $param['uspass'] == '' || $param['uspass'] == 'null') {
                    // Si viene vacía o es 'null' (por data_submitted), mantenemos la vieja
                    $param['uspass'] = $objViejo->getUspass();
                }

                // Lógica de Estado (Deshabilitado)
                if (!array_key_exists('usdeshabilitado', $param)) {
                    $param['usdeshabilitado'] = $objViejo->getUsdeshabilitado();
                }
            }

            $elObjtUsuario = $this->cargarObjeto($param);

            // Verificamos que el objeto se haya creado bien y ejecutamos modificar
            if ($elObjtUsuario != null && $elObjtUsuario->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * (Búsqueda) Busca usuarios en la BD
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " and usnombre ='" . $param['usnombre'] . "'";
            if (isset($param['usmail']))
                $where .= " and usmail ='" . $param['usmail'] . "'";
            if (isset($param['uspass']))
                $where .= " and uspass ='" . $param['uspass'] . "'";
        }
        $arreglo = Usuario::listar($where);
        return $arreglo;
    }

    public function buscarPorId($id){

        $usuario = NULL;
        $obj = new Usuario();
        if($obj->buscar($id)){
            $usuario = $obj;
        }

        return $usuario;
    }
}