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


$infoValidate = validarRegistroPreguntaSeguridad();
//$infoValidate['estado'] = true;
if ($infoValidate['estado']) {

    $pregunta = sanearDatos($_POST['pregunta']);
    $respuesta = sanearDatos($_POST['respuesta']);
    $iderUser = sanearDatos($_POST['iderUser']);
    $continue = $_POST['continue'];

    $estadoRegistro = realizarRegistroPreguntaSeguridad($pregunta, $respuesta, $iderUser);

    if ($estadoRegistro == 1) {
        $texto = "La pregunta de seguridad se registró correctamente.";
    } else {
        $texto = "Hubo un error al registrar la pregunta de seguridad. Vuelva a intentarlo en unos minutos.";
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
