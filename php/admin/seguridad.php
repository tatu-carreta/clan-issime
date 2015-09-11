<?php

$securityQuestions = array(
    0 => array(
        'idPregunta' => 1,
        'nombrePregunta' => "¿Cuál es tu libro infantil favorito?"
    ),
    1 => array(
        'idPregunta' => 2,
        'nombrePregunta' => "¿Cómo se llamaba tu primera mascota?"
    ),
    2 => array(
        'idPregunta' => 3,
        'nombrePregunta' => "¿Cuál es tu película favorita?"
    ),
    3 => array(
        'idPregunta' => 4,
        'nombrePregunta' => "¿Cuál es tu deporte favorito?"
    ),
    4 => array(
        'idPregunta' => 5,
        'nombrePregunta' => "¿Cuál es tu color favorito?"
    ),
    5 => array(
        'idPregunta' => 6,
        'nombrePregunta' => "¿Cuál es el apellido de tu madre?"
    )
);

function permisoLogueado() {
    if (!isset($_SESSION['user_name']) || (!isset($_SESSION['ip_user'])) || ($_SESSION['ip_user'] != $_SERVER['REMOTE_ADDR']) || ($_SESSION['private'] != $_SESSION['private_alternative'])) {

        require_once '../php/admin/securityControl.php';
        if (file_exists("./controladorAdmin.php?seccion=login")) {
            if ($localhost) {
                header("Location: ./controladorAdmin.php?seccion=login&error=ok");
            } else {
                header("Location:" . PATH_HOME . "login/ok");
            }
        } else {
            if ($localhost) {
                header("Location: ../controller/controladorAdmin.php?seccion=login&error=ok");
            } else {
                header("Location:" . PATH_HOME . "login/ok");
            }
        }

        exit();
    }
}

function permisoRol($arrayRoles) {
    if (!in_array($_SESSION['user_type'], $arrayRoles) || ($_SESSION['ip_user'] != $_SERVER['REMOTE_ADDR']) || ($_SESSION['private'] != $_SESSION['private_alternative'])) {
        require_once '../php/admin/securityControl.php';
        if (file_exists("./controladorAdmin.php?seccion=login")) {
            if ($localhost) {
                header("Location: ./controladorAdmin.php?seccion=login&error=ok");
            } else {
                header("Location:" . PATH_HOME . "login/ok");
            }
        } else {
            if ($localhost) {
                header("Location: ../controller/controladorAdmin.php?seccion=login&error=ok");
            } else {
                header("Location:" . PATH_HOME . "login/ok");
            }
        }
        exit();
    }
}

function permisoNoLogueado() {
    $ok = false;
    if (!isset($_SESSION['user_name']) || (!isset($_SESSION['ip_user'])) || (!isset($_SESSION['private'])) || (!isset($_SESSION['private_alternative']))) {
        $ok = true;
    }

    return $ok;
}

function yaEstaLogueado() {
    if (isset($_SESSION['user_name']) && (isset($_SESSION['ip_user'])) && ($_SESSION['ip_user'] == $_SERVER['REMOTE_ADDR']) && ($_SESSION['private'] == $_SESSION['private_alternative'])) {
        require_once '../php/admin/securityControl.php';
        if (file_exists("./controladorAdmin.php?seccion=login")) {
            if ($localhost) {
                header("Location: ./controladorAdmin.php?seccion=login&error=ok");
            } else {
                header("Location:" . PATH_HOME . "login/ok");
            }
        } else {
            if ($localhost) {
                header("Location: ../controller/controladorAdmin.php?seccion=login&error=ok");
            } else {
                header("Location:" . PATH_HOME . "login/ok");
            }
        }

        exit();
    }
}

?>