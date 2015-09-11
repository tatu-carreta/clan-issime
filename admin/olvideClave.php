<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}


/*
 * Pregunta por que todos los datos necesarios se reciban
 * correctamente, en caso de no se así no prosigue con
 * el alta.
 */
require_once '../php/funciones.php';
require_once '../php/admin/validateUsuario.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesCarrito.php';
require_once '../php/funcionesUsuarios.php';


if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}


$infoValidate = validarOlvideClave();
//$infoValidate['estado'] = true;
if ($infoValidate['estado']) {

    $email = sanearDatos($_POST['email']);
    $pregunta = sanearDatos($_POST['pregunta']);
    $respuesta = sanearDatos($_POST['respuesta']);
    $continue = PATH_HOME;

    $estadoRegistro = realizarRegeneracionClave($email, $pregunta, $respuesta);

    if ($estadoRegistro != -1) {
        $texto = "La nueva contraseña le será enviada por mail. Cualquier inconveniente, póngase en contacto con nosotros.";

        if (!$localhost) {
            require_once '../php/admin/mails/olvidasteContrasena.php';
        }
    } else {
        $texto = "No coinciden el email, la pregunta y la respuesta. Vuelva a intentarlo en unos minutos.";
    }
} else {
    $estadoRegistro = -1;
    $texto = $infoValidate['texto'];
    $continue = "";
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

$data = array(
    'estado' => $estadoRegistro,
    'texto' => $texto,
    'continue' => $continue
);

echo json_encode($data);
?>
