<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}

require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesUsuarios.php';

require_once '../php/admin/validateUsuario.php';
require_once '../php/admin/seguridad.php';

/*
 * Si está logueado no tiene acceso.
 */
yaEstaLogueado();

if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}

/*
 * Se validan los datos del login.
 */
$infoValidate = validarDatosLogin();
$error = true;

if ($infoValidate['estado']) {
    $nombreUsuario = sanearDatos($_POST['user']);
    $claveUsuario = sanearDatos($_POST['pass']);
    $continue = $_POST['continue'];

    $estado = obtenerUsuarioPorNombreClave($nombreUsuario, $claveUsuario);

    if ($estado['estado']) {
        $_SESSION['user_name'] = $estado['nombreApellido'];
        $_SESSION['user_mac'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_user'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['private'] = blow_crypt("C9l1n3s9s39m9", 4);
        $_SESSION['private_alternative'] = $_SESSION['private'];
        $_SESSION['user_type'] = $estado['perfil'];
        $_SESSION['iderUser'] = $estado['iderUser'];
        $_SESSION['user_last_activity'] = time();
        $estado = realizarInformeUsuarioLogueado($estado['idUsuario'], $_SERVER['REMOTE_ADDR']);
        $error = false;


        switch ($_SESSION['user_type']) {
            case 1:
                $continue = "";
                break;
            case 4:
                if ($localhost) {
                    $continue = "./controladorAdmin.php?seccion=listadoClientes";
                } else {
                    $continue = PATH_HOME . "listadoClientes/admin";
                }
                break;
        }
    } else {
        $texto = "Error al iniciar sesión. El nombre de usuario o la contraseña son inválidos.";
    }
} else {
    $texto = $infoValidate['texto'];
}
?>
