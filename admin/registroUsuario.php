<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}

/*
 * Si no está logueado, entonces podrá darse de alta.
 */

require_once '../php/admin/seguridad.php';

if (!permisoNoLogueado()) {
    require_once '../php/admin/securityControl.php';
    if (file_exists("./controladorAdmin.php?seccion=login")) {
        if ($localhost) {
            header("Location: ./controladorAdmin.php?seccion=login&error=ok");
        } else {
            header("Location:" . PATH_HOME . "login/ok");
        }
        header("Location: ./controladorAdmin.php?seccion=login&error=ok");
    } else {
        if ($localhost) {
            header("Location: ../controller/controladorAdmin.php?seccion=login&error=ok");
        } else {
            header("Location:" . PATH_HOME . "login/ok");
        }
    }
}

/*
 * Pregunta por que todos los datos necesarios se reciban
 * correctamente, en caso de no se así no prosigue con
 * el alta.
 */
require_once '../php/funciones.php';
require_once '../php/admin/validateRegistro.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesUsuarios.php';


if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}

$infoValidate = validarRegistro();

if ($infoValidate['estado']) {
    $nombre = sanearDatos(trim($_POST['nombre']));
    $apellido = sanearDatos(trim($_POST['apellido']));
    $email = sanearDatos(trim($_POST['email']));
    $clave = sanearDatos(trim($_POST['clave']));
    $confirmClave = sanearDatos(trim($_POST['confirmClave']));
    $fecNac = cambiarFecha(str_replace("/", "-", sanearDatos(trim($_POST['fechaNac']))));
    $tipoUsuario = sanearDatos(trim($_POST['tipoUsuario']));
    $address = sanearDatos($_SERVER['REMOTE_ADDR']);

    if ($tipoUsuario == "min") {
        $estadoRegistro = realizarAltaMinorista($nombre, $apellido, $email, $clave, $fecNac, $address);
    } else {
        $calle = sanearDatos(trim($_POST['calle']));
        $numero = sanearDatos(trim($_POST['numero']));
        $piso = sanearDatos(trim($_POST['piso']));
        $departamento = sanearDatos(trim($_POST['departamento']));
        $provincia = sanearDatos(trim($_POST['provincia']));
        $localidad = sanearDatos(trim($_POST['localidad']));
        $codPostal = sanearDatos(trim($_POST['codPostal']));
        $telefono = sanearDatos(trim($_POST['telefono']));
        $cuit = sanearDatos(trim($_POST['cuit']));
        $condIva = sanearDatos(trim($_POST['condIva']));
        $razonSocial = sanearDatos(trim($_POST['razonSocial']));
        $nombreFantasia = sanearDatos(trim($_POST['nombreFantasia']));
        
        $calleFiscal = sanearDatos(trim($_POST['calleFiscal']));
        $numeroFiscal = sanearDatos(trim($_POST['numeroFiscal']));
        $pisoFiscal = sanearDatos(trim($_POST['pisoFiscal']));
        $departamentoFiscal = sanearDatos(trim($_POST['departamentoFiscal']));
        $provinciaFiscal = sanearDatos(trim($_POST['provinciaFiscal']));
        $localidadFiscal = sanearDatos(trim($_POST['localidadFiscal']));
        $codPostalFiscal = sanearDatos(trim($_POST['codPostalFiscal']));
        $telefonoFiscal = sanearDatos(trim($_POST['telefonoFiscal']));

        $estadoRegistro = realizarAltaMayorista($nombre, $apellido, $email, $clave, $fecNac, $calle, $numero, $piso, $departamento, $localidad, $codPostal, $telefono, $cuit, $condIva, $razonSocial, $nombreFantasia, $address, $calleFiscal, $numeroFiscal, $pisoFiscal, $departamentoFiscal, $localidadFiscal, $codPostalFiscal, $telefonoFiscal);
    }

    if ($estadoRegistro == 1) {
        $texto = "Usted se registró correctamente.";
    } else {
        if ($estadoRegistro == -99) {
            $texto = "El usuario que intenta registrar ya existe.";
        } else {
            $texto = "Hubo un error al realizar el alta a la base de datos. Vuelva a intentarlo en unos minutos.";
        }
    }
} else {
    $estadoRegistro = -1;
    $texto = $infoValidate['texto'];
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

if ($estadoRegistro == 1) {
    $data = array(
        'estado' => $estadoRegistro,
        'texto' => $texto,
        'usuario' => $email,
        'clave' => $clave
    );
} else {
    $data = array(
        'estado' => $estadoRegistro,
        'texto' => $texto
    );
}


echo json_encode($data);
?>
