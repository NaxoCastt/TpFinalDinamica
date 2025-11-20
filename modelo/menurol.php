<?php

class Menurol
{

    private $idmenu;

    private $idrol;
    private $idmenuOriginal;
    private $idrolOriginal;


    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idmenu = "";
        $this->idrol = "";
        self::$mensajeOperacion = "";
    }

    public function cargar($idmenu, $idrol)
    {
        $this->setIdmenu($idmenu);
        $this->setIdrol($idrol);
    }
    public function setOriginal($idmenu, $idrol)
    {
        $this->idmenuOriginal = $idmenu;
        $this->idrolOriginal = $idrol;
    }

    public function buscar($idrol, $idmenu)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM menurol WHERE idrol=" . $idrol . " AND idmenu=" . $idmenu;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idmenu'], $row['idrol']);
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
        $consulta = "INSERT INTO menurol(idmenu, idrol) VALUES (" . $this->getIdmenu() . "," . $this->getIdrol() . ")";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
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
        $consulta = "SELECT * FROM menurol";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idmenu ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Menurol();
                    $obj->cargar($row['idmenu'], $row['idrol']);
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
        $consulta = "DELETE FROM menurol WHERE idmenu=" . $this->getIdmenu() . " AND idrol =" . $this->getIdrol();
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

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consulta = "UPDATE menurol SET idmenu = " . intval($this->getIdmenu()) . ", idrol = " . intval($this->getIdrol()) .
            " WHERE idmenu = " . intval($this->getIdmenuOriginal()) . " AND idrol = " . intval($this->getIdrolOriginal());


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

    public function __tostring()
    {
        return "Menurol [idmenu=" . $this->getIdmenu() . ", idrol=" . $this->getIdrol() . "]";
    }

    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function getIdrol()
    {
        return $this->idrol;
    }

    public static function getMensajeOperacion()
    {
        return self::$mensajeOperacion;
    }

    public function setIdmenu($idmenu): void
    {
        $this->idmenu = $idmenu;
    }

    public function setIdrol($idrol): void
    {
        $this->idrol = $idrol;
    }
    public function getIdmenuOriginal()
    {
        return $this->idmenuOriginal;
    }

    public function getIdrolOriginal()
    {
        return $this->idrolOriginal;
    }

    public function setIdmenuOriginal($idmenuOriginal): void
    {
        $this->idmenuOriginal = $idmenuOriginal;
    }

    public function setIdrolOriginal($idrolOriginal): void
    {
        $this->idrolOriginal = $idrolOriginal;
    }
    public static function setMensajeOperacion($mensajeOperacion)
    {
        self::$mensajeOperacion = $mensajeOperacion;
    }
}
