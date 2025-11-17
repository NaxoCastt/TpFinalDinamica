<?php
class Session
{
    private $mensajeError; // Variable para guardar el error

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Inicia la sesión. Detecta errores específicos.
     */
    public function iniciar($usmail, $uspass)
    {
        $this->mensajeError = "";
        $loginExitoso = false;
        $abmUsuario = new AbmUsuario();

        $listaUsuarios = $abmUsuario->buscar(['usmail' => $usmail]);

        if (count($listaUsuarios) > 0) {
            $usuario = $listaUsuarios[0];

            if ($usuario->getUsdeshabilitado() != NULL) {
                $this->mensajeError = "Esta cuenta no puede iniciar sesión.";
            } else if ($usuario->getUspass() == $uspass) {

                // Login exitoso
                $loginExitoso = true;
                $_SESSION['idusuario'] = $usuario->getIdusuario();
                $_SESSION['usnombre'] = $usuario->getUsnombre();

                // Cargar roles
                $abmUsuarioRol = new AbmUsuarioRol();
                $listaRoles = $abmUsuarioRol->buscar(['idusuario' => $usuario->getIdusuario()]);

                $roles = [];
                foreach ($listaRoles as $objUsuarioRol) {
                    $idRol = $objUsuarioRol->getIdrol();
                    $objRol = new Rol();
                    if ($objRol->buscar($idRol)) {
                        $roles[] = $objRol->getRodescripcion();
                        $idRoles[] = $objRol->getIdrol();
                    }
                }
                $_SESSION['roles'] = $roles;
                $_SESSION['idRoles'] = $idRoles;
                
            } else {
                $this->mensajeError = "Usuario o contraseña incorrectos.";
            }
        } else {
            $this->mensajeError = "Usuario o contraseña incorrectos.";
        }

        if (!$loginExitoso) {
            $this->cerrar();
        }

        return $loginExitoso;
    }

    public function getMensajeError()
    {
        return $this->mensajeError;
    }

    public function validar()
    {
        $esValida = false;
        if ($this->activa()) {
            $abmUsuario = new AbmUsuario();
            $listaUsuarios = $abmUsuario->buscar(['idusuario' => $_SESSION['idusuario']]);
            if (count($listaUsuarios) > 0) {
                $usuario = $listaUsuarios[0];
                if ($usuario->getUsdeshabilitado() == NULL) {
                    $esValida = true;
                }
            }
        }
        if (!$esValida && $this->activa()) {
            $this->cerrar();
        }
        return $esValida;
    }

    public function activa()
    {
        return isset($_SESSION['idusuario']);
    }

    public function getUsuario()
    {
        $usuario = null;
        if ($this->activa()) {
            $abmUsuario = new AbmUsuario();
            $lista = $abmUsuario->buscar(['idusuario' => $_SESSION['idusuario']]);
            if (count($lista) > 0) {
                $usuario = $lista[0];
            }
        }
        return $usuario;
    }

    public function getRol()
    {
        $roles = [];
        if ($this->activa() && isset($_SESSION['roles'])) {
            $roles = $_SESSION['roles'];
        }
        return $roles;
    }

    public function getIdRol(){

        $roles = [];
        if ($this->activa() && isset($_SESSION['idRoles'])) {
            $roles = $_SESSION['idRoles'];
        }
        return $roles;
    }

    public function cerrar()
    {
        session_unset();
        session_destroy();
    }
}
