<?php

if (file_exists("../php/admin/funcionesSecurity.php")) {
    require_once '../php/admin/funcionesSecurity.php';
} else if (file_exists("admin/funcionesSecurity.php")) {
    require_once 'admin/funcionesSecurity.php';
} else if (file_exists("../../php/admin/funcionesSecurity.php")) {
    require_once '../../php/admin/funcionesSecurity.php';
} else if (file_exists("php/admin/funcionesSecurity.php")) {
    require_once 'php/admin/funcionesSecurity.php';
} else if (file_exists("../funcionesSecurity.php")) {
    require_once '../funcionesSecurity.php';
} else if (file_exists("../../../php/admin/funcionesSecurity.php")) {
    require_once '../../../php/admin/funcionesSecurity.php';
}

if (isset($_SESSION['iderUser']) && ($_SESSION['iderUser'] != "")) {
    $iderUser = sanearDatos($_SESSION['iderUser']);
} else {
    $iderUser = NULL;
}

realizarMonitoreoMalicioso($iderUser);
?>
