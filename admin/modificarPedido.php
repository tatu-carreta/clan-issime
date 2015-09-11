<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}

/*
 * Si no está logueado, entonces podrá darse de alta.
 */

require_once '../php/admin/seguridad.php';

permisoLogueado();
$permisos = array(1);
permisoRol($permisos);


require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesPedido.php';

if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}

if (isset($_POST['idPedido']) && ($_POST['idPedido'] != "")) {

    $idPedido = sanearDatos($_POST['idPedido']);
    $estadoNuevo = sanearDatos($_POST['estadoNuevo']);

    $estadoModificacion = realizarModificacionPedido($idPedido, $estadoNuevo);

    if ($estadoModificacion == 1) {
        $texto = "El pedido se modificó correctamente.";
    } else {
        $texto = "Hubo un error al realizar la modificación del pedido. Vuelva a intentarlo en unos minutos.";
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
