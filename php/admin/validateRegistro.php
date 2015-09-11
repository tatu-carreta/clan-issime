<?php

function validarRegistro() {
    $ok = false;
    $text = "";

    if (!isset($_POST['nombre'])) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['nombre']) || ($_POST['nombre'] == "") || (!is_string($_POST['nombre']))) {
        $text = "Hubo un error en el nombre.";
    } elseif (!isset($_POST['apellido']) || ($_POST['apellido'] == "") || (!is_string($_POST['apellido']))) {
        $text = "Hubo un error en el apellido.";
    } elseif (!isset($_POST['email']) || ($_POST['email'] == "") || (!is_string($_POST['email'])) || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $text = "Hubo un error en el email.";
    } elseif (!isset($_POST['clave']) || ($_POST['clave'] == "") || (!is_string($_POST['clave']))) {
        $text = "Hubo un error en la contraseña.";
    } elseif (!isset($_POST['confirmClave']) || ($_POST['confirmClave'] == "") || (!is_string($_POST['confirmClave'])) || ($_POST['confirmClave'] != $_POST['clave'])) {
        $text = "Hubo un error en la contraseña.";
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
                $text = "2Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
            } elseif (!isset($_POST['fechaNac']) || ($_POST['fechaNac'] == "") || (!is_string($_POST['fechaNac'])) || (!validateDate($_POST['fechaNac'], "d/m/Y")) || ($estado != 2)) {
                $text = "Hubo un error en la fecha de nacimiento.";
            } elseif (!isset($_POST['calle']) || ($_POST['calle'] == "") || ($estado != 2)) {
                $text = "Hubo un error en la calle.";
            } elseif (!isset($_POST['numero']) || ($_POST['numero'] == "") || (!is_numeric($_POST['numero'])) || ($estado != 2)) {
                $text = "Hubo un error en el número.";
            } elseif (!isset($_POST['piso']) || (!is_string($_POST['piso'])) || ($estado != 2)) {
                $text = "Hubo un error en el piso.";
            } elseif (!isset($_POST['departamento']) || (!is_string($_POST['departamento'])) || ($estado != 2)) {
                $text = "Hubo un error en el departamento.";
            } elseif (!isset($_POST['provincia']) || ($_POST['provincia'] == "") || (!is_numeric($_POST['provincia'])) || ($estado != 2)) {
                $text = "Hubo un error en la provincia.";
            } elseif (!isset($_POST['localidad']) || ($_POST['localidad'] == "") || (!is_numeric($_POST['localidad'])) || ($estado != 2)) {
                $text = "Hubo un error en la localidad.";
            } elseif (!isset($_POST['codPostal']) || ($_POST['codPostal'] == "") || (!is_numeric($_POST['codPostal'])) || ($estado != 2)) {
                $text = "Hubo un error en el código postal.";
            } elseif (!isset($_POST['telefono']) || ($_POST['telefono'] == "") || (!is_string($_POST['telefono'])) || ($estado != 2)) {
                $text = "Hubo un error en el teléfono.";
            } elseif (!isset($_POST['cuit']) || ($_POST['cuit'] == "") || (!is_numeric($_POST['cuit'])) || ($estado != 2)) {
                $text = "Hubo un error en el cuit.";
            } elseif (!isset($_POST['condIva']) || ($_POST['condIva'] == "") || (!is_numeric($_POST['condIva'])) || ($estado != 2)) {
                $text = "Hubo un error en la condición iva.";
            } elseif (!isset($_POST['razonSocial']) || ($_POST['razonSocial'] == "") || (!is_string($_POST['razonSocial'])) || ($estado != 2)) {
                $text = "Hubo un error en la razón social.";
            } elseif (!isset($_POST['nombreFantasia']) || ($_POST['nombreFantasia'] == "") || (!is_string($_POST['nombreFantasia'])) || ($estado != 2)) {
                $text = "Hubo un error en el nombre de fantasía.";
            } elseif (!isset($_POST['calleFiscal']) || ($_POST['calleFiscal'] == "") || ($estado != 2)) {
                $text = "Hubo un error en la calle fiscal.";
            } elseif (!isset($_POST['numeroFiscal']) || ($_POST['numeroFiscal'] == "") || (!is_numeric($_POST['numeroFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en el número fiscal.";
            } elseif (!isset($_POST['pisoFiscal']) || (!is_string($_POST['pisoFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en el piso fiscal.";
            } elseif (!isset($_POST['departamentoFiscal']) || (!is_string($_POST['departamentoFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en el departamento fiscal.";
            } elseif (!isset($_POST['provinciaFiscal']) || ($_POST['provinciaFiscal'] == "") || (!is_numeric($_POST['provinciaFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en la provincia fiscal.";
            } elseif (!isset($_POST['localidadFiscal']) || ($_POST['localidadFiscal'] == "") || (!is_numeric($_POST['localidadFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en la localidad fiscal.";
            } elseif (!isset($_POST['codPostalFiscal']) || ($_POST['codPostalFiscal'] == "") || (!is_numeric($_POST['codPostalFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en el código postal fiscal.";
            } elseif (!isset($_POST['telefonoFiscal']) || ($_POST['telefonoFiscal'] == "") || (!is_string($_POST['telefonoFiscal'])) || ($estado != 2)) {
                $text = "Hubo un error en el teléfono fiscal.";
            } else {
                $ok = true;
            }
        } else {
            if ($estado != 99) {
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

function validarRegistroCliente() {
    $ok = false;
    $text = "";

    if (!isset($_POST['nombre'])) {
        $text = "Hubo un error al recibir los datos solicitados. Intente nuevamente en unos minutos.";
    } elseif (!isset($_POST['nombre']) || ($_POST['nombre'] == "nombre") || (!is_string($_POST['nombre']))) {
        $text = "Hubo un error en el nombre.";
    } elseif (!isset($_POST['apellido']) || ($_POST['apellido'] == "apellido") || (!is_string($_POST['apellido']))) {
        $text = "Hubo un error en el apellido.";
    } elseif (!isset($_POST['email']) || ($_POST['email'] == "email") || (!is_string($_POST['email'])) || (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
        $text = "Hubo un error en el email.";
    } elseif (!isset($_POST['telefono']) || ($_POST['telefono'] == "teléfono") || (!is_string($_POST['telefono']))) {
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
