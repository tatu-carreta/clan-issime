<?php

function validarDatosLogin() {
    $ok = false;
    $text = "";

    if (!isset($_POST['user']) || ($_POST['user'] == "")) {
        $text = "Problema en el nombre de usuario";
    } elseif (!isset($_POST['pass']) || ($_POST['user'] == "")) {
        $text = "Problema en la contraseña.";
    } else {
        $ok = true;
    }

    $data = array(
        'estado' => $ok,
        'texto' => $text
    );

    return $data;
}

function validarCambios() {
    $ok = false;
    $text = "";

    if (isset($_POST['usuario'])) {
        $infoPersona = obtenerInfoUsuarioPorIderUser($_POST['usuario']);
    } else {
        $infoPersona = -1;
    }

    if (!isset($_POST['nombre'])) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['nombre']) || ($_POST['nombre'] == "") || (!is_string($_POST['nombre']))) {
        $text = "Hubo un error en el nombre.";
    } elseif (!isset($_POST['apellido']) || ($_POST['apellido'] == "") || (!is_string($_POST['apellido']))) {
        $text = "Hubo un error en el apellido.";
    } elseif (!isset($_POST['email']) || ($_POST['email'] == "") || (!is_string($_POST['email'])) || ($infoPersona['email'] != $_POST['email']) || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $text = "Hubo un error en el email.";
    } elseif (!isset($_POST['tipoUsuario']) || ($_POST['tipoUsuario'] == "") || (!is_string($_POST['tipoUsuario']))) {
        $text = "Hubo un error en la selección del tipo.";
    } else {
        if (isset($_POST['fechaNac']) || ($_POST['fechaNac'] != "")) {
            if ((!is_string($_POST['fechaNac'])) || (!validateDate($_POST['fechaNac'], "d/m/Y"))) {
                $text = "Hubo un error en la fecha de nacimiento.";
                $estado = 99;
            } else {
                $estado = 2;
            }
        } else {
            $estado = 1;
        }
        if ($_POST['tipoUsuario'] == "may") {
            if (!isset($_POST['cuit'])) {
                $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
            } elseif (!isset($_POST['fechaNac']) || ($_POST['fechaNac'] == "") || (!is_string($_POST['fechaNac'])) || (!validateDate($_POST['fechaNac'], "d/m/Y"))) {
                $text = "Hubo un error en la fecha de nacimiento.";
            } elseif (!isset($_POST['calle']) || ($_POST['calle'] == "") || (!is_string($_POST['calle']))) {
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
            } elseif (!isset($_POST['cuit']) || ($_POST['cuit'] == "") || (!is_numeric($_POST['cuit']))) {
                $text = "Hubo un error en el cuit.";
            } elseif (!isset($_POST['condIva']) || ($_POST['condIva'] == "") || (!is_numeric($_POST['condIva']))) {
                $text = "Hubo un error en la condición ivat.";
            } elseif (!isset($_POST['razonSocial']) || ($_POST['razonSocial'] == "") || (!is_string($_POST['razonSocial']))) {
                $text = "Hubo un error en la razón social.";
            } elseif (!isset($_POST['nombreFantasia']) || ($_POST['nombreFantasia'] == "") || (!is_string($_POST['nombreFantasia']))) {
                $text = "Hubo un error en el nombre de fantasía.";
            } else {
                if (isset($_POST['clave']) && ($_POST['clave'] != "")) {
                    if (!isset($_POST['confirmClave']) || ($_POST['clave'] != $_POST['confirmClave'])) {
                        $text = "Hubo un error en la confirmación de la contraseña.";
                    } else {
                        $ok = true;
                    }
                } else {
                    $ok = true;
                }
            }
        } else {
            if (isset($_POST['clave']) && ($_POST['clave'] != "")) {
                if (!isset($_POST['confirmClave']) || ($_POST['clave'] != $_POST['confirmClave'])) {
                    $text = "Hubo un error en la confirmación de la contraseña.";
                } else {
                    $ok = true;
                }
            } else {
                $ok = true;
            }
        }
    }


    $result = array(
        'estado' => $ok,
        'texto' => $text
    );

    return $result;
}

function validarRegistroPreguntaSeguridad() {
    $ok = false;
    $text = "";

    if (!isset($_POST['iderUser'])) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['iderUser']) || ($_POST['iderUser'] == "") || (!is_string($_POST['iderUser']))) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['pregunta']) || ($_POST['pregunta'] == "") || (!is_numeric($_POST['pregunta']))) {
        $text = "Hubo un error en la pregunta.";
    } elseif (!isset($_POST['respuesta']) || ($_POST['respuesta'] == "") || (!is_string($_POST['respuesta']))) {
        $text = "Hubo un error en la respuesta.";
    } elseif (!isset($_POST['continue']) || ($_POST['continue'] == "") || (!is_string($_POST['continue'])) || (!filter_var($_POST['continue'], FILTER_VALIDATE_URL))) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } else {
        $ok = true;
    }

    $result = array(
        'estado' => $ok,
        'texto' => $text
    );

    return $result;
}

function validarOlvideClave() {
    $ok = false;
    $text = "";

    if (!isset($_POST['email'])) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['email']) || ($_POST['email'] == "") || (!is_string($_POST['email'])) || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $text = "Hubo un error en el email.";
    } elseif (!isset($_POST['pregunta']) || ($_POST['pregunta'] == "") || (!is_numeric($_POST['pregunta']))) {
        $text = "Hubo un error en la pregunta.";
    } elseif (!isset($_POST['respuesta']) || ($_POST['respuesta'] == "") || (!is_string($_POST['respuesta']))) {
        $text = "Hubo un error en la respuesta.";
    } elseif (!isset($_POST['section']) || ($_POST['section'] == "") || (!is_string($_POST['section']))) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
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
