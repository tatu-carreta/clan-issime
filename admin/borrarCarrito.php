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

                $idTalle =  sanearDatos ( $_POST['idTalle']);

        if (isset($_SESSION['iderCarro']) &&($_SESSION['iderCarro'] != "")) {
        $iderCarro = sanearDatos($_SESSION[ 'iderCarro']);

$estadoBaja = realizarBajaCarrito($idTalle, $iderCarro );
        } else {
        $estadoBaja = -1;
        }
        if ($estadoBaja == 1) {
$texto = "Los artículos fueron eliminados del carrito.";
        } else {
        $texto = "Hubo un error al eliminar los artículos del carrito. Vuelva a intentarlo en unos minutos.";
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

        $data = array (
        'estado' =>  $estadoBaja,
    'texto' => $texto
);

echo json_encode($data);
}
?>
