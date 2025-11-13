<?php
include_once '../configuracion.php';
class Producto
{

    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idproducto = "";
        $this->pronombre = "";
        $this->prodetalle = "";
        $this->procantstock = "";
        self::$mensajeOperacion = "";
    }

    public function cargar($idproducto, $pronombre, $prodetalle, $procantstock)
    {
        $this->setIdproducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
    }

    public function buscar($idproducto)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM producto WHERE idproducto=" . $idproducto;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']);
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

    public static function listar($condicion = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $consulta = "SELECT * FROM producto";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idproducto ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Producto();
                    $obj->cargar($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']);
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

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consulta = "INSERT INTO producto(pronombre, prodetalle, procantstock) VALUES ('" . $this->getPronombre() . "','" . $this->getProdetalle() . "'," . $this->getProcantstock() . ")";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdproducto($id);
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
        $consulta = "UPDATE producto SET pronombre='" . $this->getPronombre() . "', prodetalle='" . $this->getProdetalle() . "', procantstock=" . $this->getProcantstock() . " WHERE idproducto=" . $this->getIdproducto();
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
        $base = new BaseDatos();
        $resp = false;
        $consulta = "DELETE FROM producto WHERE idproducto=" . $this->getIdproducto();
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
        return "Id Producto: " . $this->getIdproducto() . "\n" .
            "Nombre: " . $this->getPronombre() . "\n" .
            "Detalle: " . $this->getProdetalle() . "\n" .
            "Cantidad en Stock: " . $this->getProcantstock() . "\n";
    }

    public function getIdproducto() {return $this->idproducto;}

	public function getPronombre() {return $this->pronombre;}

	public function getProdetalle() {return $this->prodetalle;}

	public function getProcantstock() {return $this->procantstock;}

	public static function getMensajeOperacion(){return self::$mensajeOperacion;}

	public function setIdproducto( $idproducto): void {$this->idproducto = $idproducto;}

	public function setPronombre( $pronombre): void {$this->pronombre = $pronombre;}

	public function setProdetalle( $prodetalle): void {$this->prodetalle = $prodetalle;}

	public function setProcantstock( $procantstock): void {$this->procantstock = $procantstock;}

	public static function setMensajeOperacion($mensajeOperacion){self::$mensajeOperacion = $mensajeOperacion;}

	
}
