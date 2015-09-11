<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}

/*
 * Si no está logueado, entonces podrá darse de alta.
 */
require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesUsuarios.php';
require_once '../php/admin/seguridad.php';

permisoLogueado();
$permisos = array(1, 2, 3);
permisoRol($permisos);

/*
 * Pregunta por que todos los datos necesarios se reciban
 * correctamente, en caso de no se así no prosigue con
 * el alta.
 */
if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}


require_once '../php/admin/validateUsuario.php';

$infoValidate = validarCambios();

if ($infoValidate['estado']) {
    $nombre = sanearDatos(trim($_POST['nombre']));
    $apellido = sanearDatos(trim($_POST['apellido']));
    $email = sanearDatos(trim($_POST['email']));
    $fecNac = cambiarFecha(str_replace("/", "-", sanearDatos(trim($_POST['fechaNac']))));
    $calle = sanearDatos(trim($_POST['calle']));
    $numero = sanearDatos(trim($_POST['numero']));
    $piso = sanearDatos(trim($_POST['piso']));
    $departamento = sanearDatos(trim($_POST['departamento']));
    $localidad = sanearDatos(trim($_POST['localidad']));
    $codPostal = sanearDatos(trim($_POST['codPostal']));
    $telefono = sanearDatos(trim($_POST['telefono']));
    $tipoUsuario = sanearDatos(trim($_POST['tipoUsuario']));
    $iderUser = sanearDatos(trim($_POST['usuario']));
    $clave = sanearDatos(trim($_POST['clave']));
    $confirmClave = sanearDatos(trim($_POST['confirmClave']));

    if ($tipoUsuario == "min") {
        $estadoCambio = realizarModificacionMinorista($nombre, $apellido, $fecNac, $calle, $numero, $piso, $departamento, $localidad, $codPostal, $telefono, $iderUser, $clave, $confirmClave);
    } else {
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

        $estadoCambio = realizarModificacionMayorista($nombre, $apellido, $fecNac, $calle, $numero, $piso, $departamento, $localidad, $codPostal, $telefono, $cuit, $condIva, $razonSocial, $nombreFantasia, $iderUser, $clave, $confirmClave, $calleFiscal, $numeroFiscal, $pisoFiscal, $departamentoFiscal, $localidadFiscal, $codPostalFiscal, $telefonoFiscal);
    }

    if ($estadoCambio == 1) {
        $texto = "Sus datos fueron actualizados correctamente.";
    } else {
        $texto = "Hubo un error al realizar la modificación de sus datos. Vuelva a intentarlo en unos minutos.";
    }
} else {
    $estadoCambio = -1;
    $texto = $infoValidate['texto'];
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

$data = array(
    'estado' => $estadoCambio,
    'texto' => $texto
);

echo json_encode($data);
?>
