<?php

class Compra
{

    private $idcompra;
    private $cofecha;
    private $idusuario;
    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idcompra = "";
        $this->cofecha = "";
        $this->idusuario = "";
        self::$mensajeOperacion = "";
    }

    public function cargar($idcompra, $cofecha, $idusuario)
    {
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setIdusuario($idusuario);
    }

    public function buscar($idcompra)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compra WHERE idcompra=" . $idcompra;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idcompra'], $row['cofecha'], $row['idusuario']);
                    $resp = true;
                }
            } else {
                self::$mensajeOperacion = $base->getError();
            }
        } else {
            self::$mensajeOperacion = $base->getError();
        }
        return $resp;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO compra(cofecha, idusuario) VALUES ('" . $this->getCofecha() . "', " . $this->getIdusuario() . ")";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdcompra($id);
                $resp = true;
            } else {
                self::$mensajeOperacion = $base->getError();
            }
        } else {
            self::$mensajeOperacion = $base->getError();
        }
        return $resp;
    }

    public function modificar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "UPDATE compra SET cofecha='" . $this->getCofecha() . "', idusuario=" . $this->getIdusuario() . " WHERE idcompra=" . $this->getIdcompra();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                self::$mensajeOperacion = $base->getError();
            }
        } else {
            self::$mensajeOperacion = $base->getError();
        }
        return $resp;
    }

    public static function listar($condicion = "")
    {
        $arreglo = [];
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compra";
        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }
        $consulta .= " ORDER BY idcompra ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Compra();
                    $obj->cargar($row['idcompra'], $row['cofecha'], $row['idusuario']);
                    array_push($arreglo, $obj);
                }
            } else {
                self::$mensajeOperacion = $base->getError();
            }
        } else {
            self::$mensajeOperacion = $base->getError();
        }
        return $arreglo;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM compra WHERE idcompra=" . $this->getIdcompra();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $resp = true;
            } else {
                self::$mensajeOperacion = $base->getError();
            }
        } else {
            self::$mensajeOperacion = $base->getError();
        }
        return $resp;
    }
    public function __toString()
    {
        return "Compra ID: " . $this->getIdcompra() . "\nFecha: " . $this->getCofecha() . "\nUsuario ID: " . $this->getIdusuario() . "\n";
    }

    public function getIdcompra() {return $this->idcompra;}

	public function getCofecha() {return $this->cofecha;}

	public function getIdusuario() {return $this->idusuario;}

	public static function getMensajeOperacion(){return self::$mensajeOperacion;}

    public function setIdcompra( $idcompra): void {$this->idcompra = $idcompra;}

	public function setCofecha( $cofecha): void {$this->cofecha = $cofecha;}

	public function setIdusuario( $idusuario): void {$this->idusuario = $idusuario;}

	public static function setMensajeOperacion($mensajeOperacion){self::$mensajeOperacion = $mensajeOperacion;}

	
}
