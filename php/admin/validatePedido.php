<?php

function validarDatosRegistroPedido() {
    $ok = false;
    $text = "";

    if (!isset($_POST['nombre'])) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['nombre']) || ($_POST['nombre'] == "") || (!is_string($_POST['nombre'])) || (is_numeric($_POST['nombre']))) {
        $text = "Hubo un error en el nombre.";
    } elseif (!isset($_POST['apellido']) || ($_POST['apellido'] == "") || (!is_string($_POST['apellido'])) || (is_numeric($_POST['nombre']))) {
        $text = "Hubo un error en el apellido.";
    } elseif (!isset($_POST['email']) || ($_POST['email'] == "") || (!is_string($_POST['email'])) || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $text = "Hubo un error en el email.";
    } elseif (!isset($_POST['fecNac']) || ($_POST['fecNac'] == "") || (!is_string($_POST['fecNac'])) || (!validateDate($_POST['fecNac'], "d/m/Y"))) {
        $text = "Hubo un error en la fecha de nacimiento.";
    } elseif (!isset($_POST['calle']) || ($_POST['calle'] == "")) {
        $text = "Hubo un error en la calle.";
    } elseif (!isset($_POST['numero']) || ($_POST['numero'] == "") || (!is_numeric($_POST['numero']))) {
        $text = "Hubo un error en el número.";
    } elseif (!isset($_POST['piso']) || (!is_string($_POST['piso']))) {
        $text = "Hubo un error en el piso.";
    } elseif (!isset($_POST['departamento']) || (!is_string($_POST['departamento']))) {
        $text = "Hubo un error en el departamento.";
    } elseif (!isset($_POST['provincia']) || ($_POST['provincia'] == "") || (!is_numeric($_POST['provincia']))) {
        $text = "Hubo un error en la provincia.";
    } elseif (!isset($_POST['localidad']) || ($_POST['localidad'] == "") || (!is_numeric($_POST['localidad']))) {
        $text = "Hubo un error en la localidad.";
    } elseif (!isset($_POST['codPostal']) || ($_POST['codPostal'] == "") || (!is_numeric($_POST['codPostal']))) {
        $text = "Hubo un error en el código postal.";
    } elseif (!isset($_POST['telefono']) || ($_POST['telefono'] == "") || (!is_string($_POST['telefono']))) {
        $text = "Hubo un error en el teléfono.";
    } else {
        $ok = true;
    }



    $result = array(
        'estado' => $ok,
        'texto' => $text
    );

    return $result;
}
?>
