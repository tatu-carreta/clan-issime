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
    $user_address = sanearDatos($_SERVER['REMOTE_ADDR']);


    if (!isset($_SESSION['iderCarro']) || ($_SESSION['iderCarro'] == "")) {
        //Entonces es la primera vez que agrega al carrito
        if (isset($_SESSION['iderUser']) && ($_SESSION['iderUser'] != "")) {
            //Entonces está logueado
            $iderUser = sanearDatos($_SESSION['iderUser']);

            $carrito = crearCarrito($iderUser, $user_address);
        } else {
            //No está logueado
            $iderUser = "";

            $carrito = crearCarrito($iderUser, $user_address);
        }

        if ($carrito['estado']) {
            $_SESSION['iderCarro'] = $carrito['iderCarro'];
        } else {
            $_SESSION['iderCarro'] = "";
        }
    }

    if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {
        $iderCarro = sanearDatos($_SESSION['iderCarro']);

        $estadoAgregacion = realizarAgregacionCarrito($idTalle, $cantidad, $iderCarro);
    } else {
        $estadoAgregacion['estado'] = -1;
    }
    if ($estadoAgregacion['estado'] == 1) {
        $texto = "Los artículos fueron agregados al carrito.";
    } else {
        $texto = "Hubo un error al agregar los artículos al carrito. Vuelva a intentarlo en unos minutos.";
    }

    /*
     * Carga en $data lo necesario para mostrar en el javascript.
     */

    $data = array(
        'estado' => $estadoAgregacion['estado'],
        'texto' => $texto,
        'datos' => $estadoAgregacion
    );

    echo json_encode($data);
}
?>
