<?php

require_once '../php/config.php';
require_once '../php/admin/seguridad.php';
require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';

permisoLogueado();

session_unset();
session_destroy();
session_regenerate_id(true);

if ($localhost) {
    header("Location:../controller/controladorVista.php?seccion=index");
} else {
    header("Location:" . PATH_HOME);
}
?>
