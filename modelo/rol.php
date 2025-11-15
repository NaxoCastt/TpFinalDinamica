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
                    // CORREGIDO: roldescripcion -> rodescripcion
                    $this->cargar($row['idrol'], $row['rodescripcion']);
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
                    // CORREGIDO: roldescripcion -> rodescripcion
                    $obj->cargar($row['idrol'], $row['rodescripcion']);
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
        // CORREGIDO: roldescripcion -> rodescripcion
        $consulta = "INSERT INTO rol(rodescripcion) VALUES ('" . $this->getRodescripcion() . "')";
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
        // CORREGIDO: roldescripcion -> rodescripcion
        $consulta = "UPDATE rol SET rodescripcion='" . $this->getRodescripcion() . "' WHERE idrol=" . $this->getIdrol();
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
?>