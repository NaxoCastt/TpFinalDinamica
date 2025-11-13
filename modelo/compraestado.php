<?php

class Compraestado
{

    private $idcompraestado;
    private $idcompra;
    private $idcompraestadotipo;
    private $cefechaini;
    private $cefechafin;
    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idcompraestado = "";
        $this->idcompra = "";
        $this->idcompraestadotipo ="";
        $this->cefechaini = "";
        $this->cefechafin = "";
        self::$mensajeOperacion = "";
    }

    public function cargar($idcompraestado, $idcompra, $idcompraestadotipo, $cefechaini, $cefechafin)
    {
        $this->setIdcompraestado($idcompraestado);
        $this->setIdcompra($idcompra);
        $this->setIdcompraestadotipo($idcompraestadotipo);
        $this->setCefechaini($cefechaini);
        $this->setCefechafin($cefechafin);
    }
    public function buscar($idcompraestado)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraestado WHERE idcompraestado=" . $idcompraestado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idcompraestado'], $row['idcompra'], $row['idcompraestadotipo'], $row['cefechaini'], $row['cefechafin']);
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
        $consulta = "INSERT INTO compraestado(idcompra, idcompraestadotipo, cefechaini, cefechafin) VALUES (" . $this->getIdcompra() . "," . $this->getIdcompraestadotipo() . ",'" . $this->getCefechaini() . "','" . $this->getCefechafin() . "')";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdcompraestado($id);
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
        $consulta = "SELECT * FROM compraestado";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idcompraestado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Compraestado();
                    $obj->cargar($row['idcompraestado'], $row['idcompra'], $row['idcompraestadotipo'], $row['cefechaini'], $row['cefechafin']);
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
        $consulta = "UPDATE compraestado SET idcompra=" . $this->getIdcompra() . ",idcompraestadotipo=" . $this->getIdcompraestadotipo() . ",cefechaini='" . $this->getCefechaini() . "',cefechafin='" . $this->getCefechafin() . "' WHERE idcompraestado=" . $this->getIdcompraestado();
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
        $consulta = "DELETE FROM compraestado WHERE idcompraestado=" . $this->getIdcompraestado();
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
        return "Compraestado [idcompraestado=" . $this->getIdcompraestado() . ", idcompra=" . $this->getIdcompra() . ", idcompraestadotipo=" . $this->getIdcompraestadotipo() . ", cefechaini=" . $this->getCefechaini() . ", cefechafin=" . $this->getCefechafin() . "]";
    }

    public function getIdcompraestado() {return $this->idcompraestado;}

	public function getIdcompra() {return $this->idcompra;}

	public function getIdcompraestadotipo() {return $this->idcompraestadotipo;}

	public function getCefechaini() {return $this->cefechaini;}

	public function getCefechafin() {return $this->cefechafin;}

	public static function getMensajeOperacion() {return self::$mensajeOperacion;}

    public function setIdcompraestado( $idcompraestado): void {$this->idcompraestado = $idcompraestado;}

	public function setIdcompra( $idcompra): void {$this->idcompra = $idcompra;}

	public function setIdcompraestadotipo( $idcompraestadotipo): void {$this->idcompraestadotipo = $idcompraestadotipo;}

	public function setCefechaini( $cefechaini): void {$this->cefechaini = $cefechaini;}

	public function setCefechafin( $cefechafin): void {$this->cefechafin = $cefechafin;}

	public static function setMensajeOperacion($mensajeOperacion){self::$mensajeOperacion = $mensajeOperacion;}

	
}
