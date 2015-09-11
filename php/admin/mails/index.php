<?php

require_once '../../../php/config.php';
require_once '../../../php/funciones.php';
require_once '../../../php/admin/securityControl.php';
if ($localhost) {
    header("Location:../../../controller/controladorVista.php?seccion=index");
} else {
    header("Location:" . PATH_HOME);
}
?>
