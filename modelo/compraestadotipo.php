<?php

class Compraestadotipo
{

    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idcompraestadotipo = "";
        $this->cetdescripcion = "";
        $this->cetdetalle = "";
        $this->mensajeOperacion = "";
    }

    public function cargar($idcompraestadotipo, $cetdescripcion, $cetdetalle)
    {
        $this->setIdcompraestadotipo($idcompraestadotipo);
        $this->setCetdescripcion($cetdescripcion);
        $this->setCetdetalle($cetdetalle);
    }

    public function buscar($idcompraestadotipo)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo=" . $idcompraestadotipo;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
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
        $consulta = "INSERT INTO compraestadotipo(cetdescripcion, cetdetalle) VALUES ('" . $this->getCetdescripcion() . "','" . $this->getCetdetalle() . "')";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdcompraestadotipo($id);
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
        $arreglo = array();
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraestadotipo";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idcompraestadotipo ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Compraestadotipo();
                    $obj->cargar($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
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

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consulta = "UPDATE compraestadotipo SET cetdescripcion='" . $this->getCetdescripcion() . "',cetdetalle='" . $this->getCetdetalle() . "' WHERE idcompraestadotipo=" . $this->getIdcompraestadotipo();
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

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consulta = "DELETE FROM compraestadotipo WHERE idcompraestadotipo=" . $this->getIdcompraestadotipo();
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
        return "ID Compra Estado Tipo: " . $this->getIdcompraestadotipo() . "\n" .
               "Descripción: " . $this->getCetdescripcion() . "\n" .
               "Detalle: " . $this->getCetdetalle() . "\n";
    }

    public function getIdcompraestadotipo() {return $this->idcompraestadotipo;}

	public function getCetdescripcion() {return $this->cetdescripcion;}

	public function getCetdetalle() {return $this->cetdetalle;}

	public static function getMensajeOperacion() {return self::$mensajeOperacion;}

    public function setIdcompraestadotipo( $idcompraestadotipo): void {$this->idcompraestadotipo = $idcompraestadotipo;}

	public function setCetdescripcion( $cetdescripcion): void {$this->cetdescripcion = $cetdescripcion;}

	public function setCetdetalle( $cetdetalle): void {$this->cetdetalle = $cetdetalle;}

	public static function setMensajeOperacion($mensajeOperacion){self::$mensajeOperacion = $mensajeOperacion;}

	
}
