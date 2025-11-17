<?php
//include_once('conector/baseDeDatos.php');
class Usuario
{


    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;

    private static $mensajeoperacion;

    public function __construct()
    {
        $this->idusuario = "";
        $this->usnombre = "";
        $this->uspass = "";
        $this->usmail = "";
        $this->usdeshabilitado = "";
        self::$mensajeoperacion = "";
    }

    public function cargar($idusuario, $usnombre, $uspass, $usmail, $usdeshabilitado)
    {
        $this->setIdusuario($idusuario);
        $this->setUsnombre($usnombre);
        $this->setUspass($uspass);
        $this->setUsmail($usmail);
        $this->setUsdeshabilitado($usdeshabilitado);
    }

    public function buscar($idusuario)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM usuario WHERE idusuario = " . $idusuario;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
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
        $consulta = "SELECT * FROM usuario";
        if ($condicion != "") {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY idusuario ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Usuario();
                    $obj->cargar($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
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
        //siempre se va a insertar un usuario que NO esté deshabilitadoS
        $consulta = "INSERT INTO usuario(usnombre, uspass, usmail, usdeshabilitado) 
                     VALUES ('" . $this->getUsnombre() . "','" . $this->getUspass() . "','" .
            $this->getUsmail() . "',NULL)";

        if ($base->Iniciar()) {
            if ($idAutoIncrement = $base->Ejecutar($consulta)) {
                $this->setIdusuario($idAutoIncrement);
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

        if ($this->getUsdeshabilitado() == null) {
            $consulta = "UPDATE usuario SET usnombre='" . $this->getUsnombre() . "',
                     uspass='" . $this->getUspass() . "',
                     usmail='" . $this->getUsmail() . "',
                     usdeshabilitado=NULL
                     WHERE idusuario=" . $this->getIdusuario();
        } else {
            $consulta = "UPDATE usuario SET usnombre='" . $this->getUsnombre() . "',
                     uspass='" . $this->getUspass() . "',
                     usmail='" . $this->getUsmail() . "',
                     usdeshabilitado='" . $this->getUsdeshabilitado() . "'
                     WHERE idusuario=" . $this->getIdusuario();
        }
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
        $resp = false;
        $base = new BaseDatos();
        $consulta = "DELETE FROM usuario WHERE idusuario=" . $this->getIdusuario();
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
        return "Id Usuario: " . $this->getIdusuario() .
            "\nNombre: " . $this->getUsnombre() .
            "\nPassword: " . $this->getUspass() .
            "\nMail: " . $this->getUsmail() .
            "\nDeshabilitado: " . $this->getUsdeshabilitado();
    }

    public function getIdusuario()
    {
        return $this->idusuario;
    }

    public function getUsnombre()
    {
        return $this->usnombre;
    }

    public function getUspass()
    {
        return $this->uspass;
    }

    public function getUsmail()
    {
        return $this->usmail;
    }

    public function getUsdeshabilitado()
    {
        return $this->usdeshabilitado;
    }

    public static function getMensajeoperacion()
    {
        return self::$mensajeoperacion;
    }

    public function setIdusuario($idusuario): void
    {
        $this->idusuario = $idusuario;
    }

    public function setUsnombre($usnombre): void
    {
        $this->usnombre = $usnombre;
    }

    public function setUspass($uspass): void
    {
        $this->uspass = $uspass;
    }

    public function setUsmail($usmail): void
    {
        $this->usmail = $usmail;
    }

    public function setUsdeshabilitado($usdeshabilitado): void
    {
        $this->usdeshabilitado = $usdeshabilitado;
    }

    public static function setMensajeoperacion($mensajeoperacion): void
    {
        self::$mensajeoperacion = $mensajeoperacion;
    }
}
