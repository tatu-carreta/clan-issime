<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}

/*
 * Si no está logueado, entonces podrá darse de alta.
 */
require_once '../php/funciones.php';
require_once '../php/admin/validateRegistro.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesUsuarios.php';
require_once '../php/admin/seguridad.php';

permisoLogueado();
$permisos = array(1, 4);
permisoRol($permisos);
/*
 * Pregunta por que todos los datos necesarios se reciban
 * correctamente, en caso de no se así no prosigue con
 * el alta.
 */


if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}

$infoValidate = validarRegistroCliente();

if ($infoValidate['estado']) {
    $nombre = sanearDatos(trim($_POST['nombre']));
    $apellido = sanearDatos(trim($_POST['apellido']));
    $email = sanearDatos(trim($_POST['email']));
    $telefono = sanearDatos(trim($_POST['telefono']));


    $estadoRegistro = realizarAltaCliente($nombre, $apellido, $email, $telefono);

    if ($estadoRegistro == 1) {
        $texto = "El cliente se registró correctamente.";

        if (!$localhost) {
            require_once '../php/admin/mails/clienteCreado.php';
        }
    } else {
        $texto = "Hubo un error al realizar el alta en la base de datos. Vuelva a intentarlo en unos minutos.";
    }
} else {
    $estadoRegistro = -1;
    $texto = $infoValidate['texto'];
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

$data = array(
    'estado' => $estadoRegistro,
    'texto' => $texto
);

echo json_encode($data);
?>
