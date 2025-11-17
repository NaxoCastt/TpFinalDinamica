<?php
class Compraitem
{

    private $idcompraitem;
    private $idproducto;
    private $idcompra;
    private $cicantidad;
    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idcompraitem = "";
        $this->idproducto = "";
        $this->idcompra = "";
        $this->cicantidad = "";
        self::$mensajeOperacion = "";
    }

    public function cargar($idcompraitem, $idproducto, $idcompra, $cicantidad)
    {
        $this->setIdcompraitem($idcompraitem);
        $this->setIdproducto($idproducto);
        $this->setIdcompra($idcompra);
        $this->setCicantidad($cicantidad);
    }

    public function buscar($idcompraitem)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraitem WHERE idcompraitem=" . $idcompraitem;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idcompraitem'], $row['idproducto'], $row['idcompra'], $row['cicantidad']);
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
        $consulta = "INSERT INTO compraitem(idproducto, idcompra, cicantidad) VALUES (" . $this->getIdproducto() . "," . $this->getIdcompra() . "," . $this->getCicantidad() . ")";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdcompraitem($id);
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
        $resp = false;
        $base = new BaseDatos();
        $consulta = "UPDATE compraitem SET idproducto=" . $this->getIdproducto() . ",idcompra=" . $this->getIdcompra() . ",cicantidad=" . $this->getCicantidad() . " WHERE idcompraitem=" . $this->getIdcompraitem();
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
        $arreglo = array();
        $base = new BaseDatos();
        $consulta = "SELECT * FROM compraitem";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idcompraitem ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Compraitem();
                    $obj->cargar($row['idcompraitem'], $row['idproducto'], $row['idcompra'], $row['cicantidad']);
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
        if ($base->Iniciar()) {
            $consulta = "DELETE FROM compraitem WHERE idcompraitem=" . $this->getIdcompraitem();
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
        return "ID Compra Item: " . $this->getIdcompraitem() . "\nID Producto: " . $this->getIdproducto() . "\nID Compra: " . $this->getIdcompra() . "\nCantidad: " . $this->getCicantidad() . "\n";
    }
    public function getIdcompraitem() {return $this->idcompraitem;}

	public function getIdproducto() {return $this->idproducto;}

	public function getIdcompra() {return $this->idcompra;}

	public function getCicantidad() {return $this->cicantidad;}

	public static function getMensajeOperacion(){return self::$mensajeOperacion;}

	public function setIdcompraitem( $idcompraitem): void {$this->idcompraitem = $idcompraitem;}

	public function setIdproducto( $idproducto): void {$this->idproducto = $idproducto;}

	public function setIdcompra( $idcompra): void {$this->idcompra = $idcompra;}

	public function setCicantidad( $cicantidad): void {$this->cicantidad = $cicantidad;}

	public static function setMensajeOperacion($mensajeOperacion){self::$mensajeOperacion = $mensajeOperacion;}

	

}
