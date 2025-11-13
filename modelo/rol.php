<?php
class Rol
{


    private $idrol;
    private $rodescripcion;

    private static $mensajeoperacion;

    public function __construct()
    {
        $this->idrol = "";
        $this->rodescripcion = "";
        self::$mensajeoperacion = "";
    }
    public function cargar($idrol, $rodescripcion)
    {
        $this->setIdrol($idrol);
        $this->setRodescripcion($rodescripcion);
    }

    public function buscar($idrol)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM rol WHERE idrol = " . $idrol;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idrol'], $row['roldescripcion']);
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

    public static function listar($condicion = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $consulta = "SELECT * FROM rol";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idrol ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Rol();
                    $obj->cargar($row['idrol'], $row['roldescripcion']);
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

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO rol(roldescripcion) VALUES ('" . $this->getRodescripcion() . "')";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdrol($id);
                $resp = true;
            } else {
                self::setMensajeoperacion($base->getError());
            }
        } else {
            self::setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consulta = "UPDATE rol SET roldescripcion='" . $this->getRodescripcion() . "' WHERE idrol=" . $this->getIdrol();
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

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consulta = "DELETE FROM rol WHERE idrol=" . $this->getIdrol();
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
    public function __toString()
    {
        return "Rol ID: " . $this->getIdrol() . " - Descripción: " . $this->getRodescripcion();
    }
    public function getIdrol()
    {
        return $this->idrol;
    }

    public function getRodescripcion()
    {
        return $this->rodescripcion;
    }

    public static function getMensajeoperacion()
    {
        return self::$mensajeoperacion;
    }

    public function setIdrol($idrol): void
    {
        $this->idrol = $idrol;
    }

    public function setRodescripcion($rodescripcion): void
    {
        $this->rodescripcion = $rodescripcion;
    }

    public static function setMensajeoperacion($mensajeoperacion): void
    {
        self::$mensajeoperacion = $mensajeoperacion;
    }
}
