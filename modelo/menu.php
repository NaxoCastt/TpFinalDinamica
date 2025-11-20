<?php

class Menu
{

    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;
    private static $mensajeOperacion;

    public function __construct()
    {
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->idpadre = "";
        $this->medeshabilitado = "";
        self::$mensajeOperacion = "";
    }

    public function cargar($idmenu, $menombre, $medescripcion, $idpadre, $medeshabilitado)
    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setIdpadre($idpadre);
        $this->setMedeshabilitado($medeshabilitado);
    }

    public function buscar($idmenu)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM menu WHERE idmenu=" . $idmenu;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idmenu'], $row['menombre'], $row['medescripcion'], $row['idpadre'], $row['medeshabilitado']);
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
        $idpadre = $this->getIdpadre();
        if ($idpadre == "-1" || $idpadre == "") {
            $idpadreSQL = "NULL";
        } else {
            $idpadreSQL = intval(trim($idpadre));
        }

        $consulta = "INSERT INTO menu(menombre, medescripcion, idpadre, medeshabilitado) VALUES ('" . $this->getMenombre() . "','" . $this->getMedescripcion() . "'," . $idpadreSQL . ", NULL)";

        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consulta)) {
                $this->setIdmenu($id);
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
        $idpadre = $this->getIdpadre();
        $idpadreSQL = ($idpadre === null || $idpadre === '') ? "NULL" : intval($idpadre);

        $medeshabilitado = trim($this->getMedeshabilitado());

        // Determinar qué hacer con medeshabilitado
        if ($medeshabilitado === "-1") {
            // Activar: poner NULL
            $medeshabilitadoSQL = "NULL";
        } elseif ($medeshabilitado === "0") {
            // No tocar: mantener el valor actual
            $medeshabilitadoSQL = "medeshabilitado"; // Campo sin comillas = mantener valor actual
        } else {
            // Tiene un valor (fecha o string): usarlo
            $medeshabilitadoSQL = "'" . $medeshabilitado . "'";
        }

        $consulta = "UPDATE menu SET menombre='" . $this->getMenombre() . "', medescripcion='" . $this->getMedescripcion() . "', idpadre=" . $idpadreSQL . ", medeshabilitado=" . $medeshabilitadoSQL . " WHERE idmenu=" . $this->getIdmenu();

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
        $consulta = "SELECT * FROM menu";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idmenu ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Menu();
                    $obj->cargar($row['idmenu'], $row['menombre'], $row['medescripcion'], $row['idpadre'], $row['medeshabilitado']);
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
            $consulta = "DELETE FROM menu WHERE idmenu=" . $this->getIdmenu();
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
        return "Menu ID: " . $this->getIdmenu() . "\n" .
            "Nombre: " . $this->getMenombre() . "\n" .
            "Descripcion: " . $this->getMedescripcion() . "\n" .
            "ID Padre: " . $this->getIdpadre() . "\n" .
            "Deshabilitado: " . $this->getMedeshabilitado() . "\n";
    }



    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function getMenombre()
    {
        return $this->menombre;
    }

    public function getMedescripcion()
    {
        return $this->medescripcion;
    }

    public function getIdpadre()
    {
        return $this->idpadre;
    }

    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }

    public static function getMensajeOperacion()
    {
        return self::$mensajeOperacion;
    }

    public function setIdmenu($idmenu): void
    {
        $this->idmenu = $idmenu;
    }

    public function setMenombre($menombre): void
    {
        $this->menombre = $menombre;
    }

    public function setMedescripcion($medescripcion): void
    {
        $this->medescripcion = $medescripcion;
    }

    public function setIdpadre($idpadre): void
    {
        $this->idpadre = $idpadre;
    }

    public function setMedeshabilitado($medeshabilitado): void
    {
        $this->medeshabilitado = $medeshabilitado;
    }

    public static function setMensajeOperacion($mensajeOperacion): void
    {
        self::$mensajeOperacion = $mensajeOperacion;
    }
}
