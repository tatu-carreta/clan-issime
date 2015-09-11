<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}


require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesCarrito.php';

if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}

if (isset($_POST['idTalle']) && ($_POST['idTalle'] != "")) {

$idTalle = sanearDatos($_POST['idTalle']);
$cantidad = sanearDatos($_POST['cant']);

if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {
    $iderCarro = sanearDatos($_SESSION['iderCarro']);

    if (!is_numeric($idTalle) || (!is_numeric($cantidad))) {
        $estadoModificacion = -1;
    }

    $estadoModificacion = realizarModificacionCarrito($idTalle, $cantidad, $iderCarro);
} else {
    $estadoModificacion = -1;
}
if ($estadoModificacion == 1) {
    $texto = "Las cantidades fueron modificadas.";
} else {
    $texto = "Hubo un error al modificar las cantidades. Vuelva a intentarlo en unos minutos.";
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

$data = array(
    'estado' => $estadoModificacion,
    'texto' => $texto
);

echo json_encode($data);
}
?>
