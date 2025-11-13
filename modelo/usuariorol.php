<?php
class Usuariorol
{

    private $idusuario;
    private $idrol;

    private static $mensajeoperacion;

    public function __construct()
    {
        $this->idusuario = "";
        $this->idrol = "";
        self::$mensajeoperacion = "";
    }

    public function cargar($idusuario, $idrol)
    {
        $this->setIdusuario($idusuario);
        $this->setIdrol($idrol);
    }

    public function buscar($idusuario){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM usuariorol WHERE idusuario = " . $idusuario;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idusuario'], $row['idrol']);
                    $resp = true;
                }
            } else {
                self::setMensajeoperacion($base->getError());
            }
        } else {
            self::setMensajeoperacion($base->getError());
        }
        return $resp;
    } 


    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO usuariorol(idusuario, idrol) VALUES (" . $this->getIdusuario() . ", " . $this->getIdrol() . ")";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                self::setMensajeoperacion($base->getError());
            }
        } else {
            self::setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $consulta = "SELECT * FROM usuariorol";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idusuario, idrol ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Usuariorol();
                    $obj->cargar($row['idusuario'], $row['idrol']);
                    array_push($arreglo, $obj);
                }
            } else {
                self::setMensajeoperacion($base->getError());
            }
        } else {
            self::setMensajeoperacion($base->getError());
        }
        return $arreglo;
    }

    public function modificar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE usuariorol SET idrol = " . $this->getIdrol() . " WHERE idusuario = " . $this->getIdusuario();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta) !== false) {
                $resp = true;
            } else {
                self::setMensajeoperacion($base->getError());
            }
        } else {
            self::setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM usuariorol WHERE idusuario = " . $this->getIdusuario() . " AND idrol = " . $this->getIdrol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                self::setMensajeoperacion($base->getError());
            }
        } else {
            self::setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function getIdusuario()
    {
        return $this->idusuario;
    }

    public function getIdrol()
    {
        return $this->idrol;
    }

    public static function getMensajeoperacion()
    {
        return self::$mensajeoperacion;
    }

    public function setIdusuario($idusuario): void
    {
        $this->idusuario = $idusuario;
    }

    public function setIdrol($idrol): void
    {
        $this->idrol = $idrol;
    }

    public static function setMensajeoperacion($mensajeoperacion): void
    {
        self::$mensajeoperacion = $mensajeoperacion;
    }
}
