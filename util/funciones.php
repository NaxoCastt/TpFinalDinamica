<?php 
function data_submitted() {
    $_AAux= array();
    if (!empty($_POST))
        $_AAux =$_POST;
        else
            if(!empty($_GET)) {
                $_AAux =$_GET;
            }
        if (count($_AAux)){
            foreach ($_AAux as $indice => $valor) {
                if ($valor=="")
                    $_AAux[$indice] = 'null' ;
            }
        }
        return $_AAux;
        
}
function verEstructura($e){
    echo "<pre>";
    print_r($e);
    echo "</pre>"; 
}

spl_autoload_register(function ($class_name) {
    $directorios = array(
        $_SESSION['ROOT'] . 'modelo/',
        $_SESSION['ROOT'] . 'modelo/conector/',
        $_SESSION['ROOT'] . 'control/',
        // $_SESSION['ROOT'] . 'util/class/',
    );

    foreach ($directorios as $directorio) {
        $archivo = $directorio . $class_name . '.php';
        if (file_exists($archivo)) {
            require_once($archivo);
            return;
        }
    }
});

function php_alert($data) {
    echo '<script>';
    // json_encode convierte el array/objeto PHP a formato JS seguro
    echo 'alert(' . json_encode($data) . ');';
    echo '</script>';
}

?>