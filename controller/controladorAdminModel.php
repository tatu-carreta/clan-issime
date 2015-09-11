<?php

require_once '../php/config.php';
require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesPersona.php';
require_once '../php/funcionesUsuarios.php';
require_once '../php/admin/seguridad.php';

if (isset($_POST['section']) && is_string($_POST['section'])) {
    $seccion = sanearDatos($_POST['section']);

    switch ($seccion) {
        case 'registro':
            if (permisoNoLogueado()) {
                require_once('../reCaptcha/recaptchalib.php');
                $privatekey = RECAPTCHA_PRIVATE_KEY;
                $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], sanearDatos($_POST["recaptcha_challenge_field"]), sanearDatos($_POST["recaptcha_response_field"]));

                if (!$resp->is_valid) {
                    // What happens when the CAPTCHA was entered incorrectly
                    $data = array(
                        'estado' => -1,
                        'texto' => "El reCaptcha ingresado es incorrecto. Vuelva a intentarlo para poder registrarse."
                    );

                    echo json_encode($data);
                } else {
                    // Your code here to handle a successful verification
                    require_once '../admin/registroUsuario.php';
                }
            } else {
                $texto = "No tiene permiso.";
                if ($localhost) {
                    header("Location:./controladorVista.php?seccion=informe&txt=" . $texto);
                } else {
                    header("Location:" . PATH_HOME . "informe/" . $texto);
                }
            }

            break;
        case 'securityQuestion':
            permisoLogueado();
            $permisos = array(2, 3);
            permisoRol($permisos);

            $yaHizo = yaRealizoPreguntaSeguridad(sanearDatos($_SESSION['iderUser']));

            if ($yaHizo == 0) {
                require_once '../admin/registroPreguntaSeguridad.php';
            } else {
                if ($localhost) {
                    header("Location:./controladorAdmin.php?seccion=miperfil&iderUser=" . sanearDatos($_SESSION['iderUser']));
                } else {
                    header("Location:" . PATH_CONTROLLER . "miperfil/" . sanearDatos($_SESSION['iderUser']));
                }
            }
            break;
        case 'olvideClave':

            if (permisoNoLogueado()) {
                require_once '../admin/olvideClave.php';
            } else {
                $texto = "No tiene permiso.";
                if ($localhost) {
                    header("Location:./controladorVista.php?seccion=informe&txt=" . $texto);
                } else {
                    header("Location:" . PATH_HOME . "informe/" . $texto);
                }
            }

            break;
        case 'inicio':
            if (permisoNoLogueado()) {
                require_once '../admin/iniciarSesion.php';

                if (isset($_POST['js']) && (sanearDatos($_POST['js']) != "")) {
                    $js = true;
                } else {
                    $js = false;
                }

                if ($error) {
                    if ($js) {
                        $data = array(
                            'error' => $error,
                            'texto' => $texto,
                            'contin' => ""
                        );

                        echo json_encode($data);
                    } else {
                        if ($localhost) {
                            header("Location:./controladorVista.php?seccion=informe&txt=" . urlencode($texto));
                        } else {
                            header("Location:" . PATH_HOME . "informe/" . urlencode($texto));
                        }
                    }
                } else {
                    if (isset($_SESSION['redir_login']) && ($_SESSION['redir_login'] != "")) {
                        unset($_SESSION['redir_login']);
                    }

                    if (($_SESSION['user_type'] != 1) && ($_SESSION['user_type'] != 4)) {

                        $yaHizo = yaRealizoPreguntaSeguridad(sanearDatos($_SESSION['iderUser']));
                        if ($yaHizo > 0) {
                            if ($continue == "miPerfil") {
                                if ($localhost) {
                                    $continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=miperfil&iderUser=" . sanearDatos($_SESSION['iderUser']);
                                } else {
                                    $continue = PATH_HOME . "miperfil/" . sanearDatos($_SESSION['iderUser']);
                                }
                            }

                            if (isset($_POST['registroLogin']) && ($_POST['registroLogin'] != "")) {
                                if ($js) {
                                    $data = array(
                                        'error' => false,
                                        'texto' => "registrado",
                                        'contin' => ""
                                    );

                                    echo json_encode($data);
                                } else {

                                    if (isset($_POST['reg']) && ($_POST['reg'] == "sr")) {
                                        //Se registra desde registrate
                                        if ($localhost) {
                                            echo json_encode(PATH_CONTROLLER . "controladorAdmin.php?seccion=securityQuestions&iderUser=" . sanearDatos($_SESSION['iderUser']) . "&sq=sR");
                                        } else {
                                            echo json_encode(PATH_HOME . "securityQuestions/" . sanearDatos($_SESSION['iderUser']) . "/sR");
                                        }
                                    } else {
                                        //Se registra desde carrito
                                        if ($localhost) {
                                            echo json_encode(PATH_CONTROLLER . "controladorAdmin.php?seccion=securityQuestions&iderUser=" . sanearDatos($_SESSION['iderUser']) . "&sq=sQ");
                                        } else {
                                            echo json_encode(PATH_HOME . "securityQuestions/" . sanearDatos($_SESSION['iderUser']) . "/sQ");
                                        }
                                    }
                                }
                            } else {
                                header("Location:" . $continue);
                            }
                        } else {
                            if ($js) {
                                if (isset($_POST['reg']) && ($_POST['reg'] != "")) {
                                    //Viene por carrito
                                    $reg = "sR";
                                } else {
                                    $reg = "sQ";
                                }

                                if ($localhost) {
                                    $continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=securityQuestions&iderUser=" . sanearDatos($_SESSION['iderUser']) . "&sq=" . $reg;
                                } else {
                                    $continue = PATH_HOME . "securityQuestions/" . sanearDatos($_SESSION['iderUser']) . "/" . $reg;
                                }

                                $data = array(
                                    'error' => false,
                                    'texto' => "registrado",
                                    'contin' => $continue
                                );

                                echo json_encode($data);
                            } else {

                                if (isset($_POST['reg']) && ($_POST['reg'] == "sr")) {
                                    //Se registra desde registrate
                                    if ($localhost) {
                                        echo json_encode(PATH_CONTROLLER . "controladorAdmin.php?seccion=securityQuestions&iderUser=" . sanearDatos($_SESSION['iderUser']) . "&sq=sR");
                                    } else {
                                        echo json_encode(PATH_HOME . "securityQuestions/" . sanearDatos($_SESSION['iderUser']) . "/sR");
                                    }
                                } else {
                                    //Se registra desde carrito
                                    if ($localhost) {
                                        echo json_encode(PATH_CONTROLLER . "controladorAdmin.php?seccion=securityQuestions&iderUser=" . sanearDatos($_SESSION['iderUser']) . "&sq=sQ");
                                    } else {
                                        echo json_encode(PATH_HOME . "securityQuestions/" . sanearDatos($_SESSION['iderUser']) . "/sQ");
                                    }
                                }
                            }
                        }
                    } else {
                        if ($js) {
                            $data = array(
                                'error' => false,
                                'texto' => "registrado",
                                'contin' => $continue
                            );

                            echo json_encode($data);
                        } else {
                            if (isset($continue)) {
                                header("Location:" . $continue);
                            } else {
                                if ($_SESSION['user_type'] == 4) {
                                    if ($localhost) {
                                        header("Location:./controladorAdmin.php?seccion=listadoClientes");
                                    } else {
                                        header("Location:" . PATH_HOME . "listadoClientes/admin");
                                    }
                                } else {
                                    if ($localhost) {
                                        header("Location:./controladorAdmin.php?seccion=pedidos");
                                    } else {
                                        header("Location:" . PATH_HOME . "pedidos/admin");
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($localhost) {
                    header("Location:./controladorVista.php?seccion=index");
                } else {
                    header("Location:" . PATH_HOME);
                }
            }
            break;
        case 'cambiarDatos':
            if (permisoNoLogueado()) {
                if ($localhost) {
                    header("Location:./controladorAdmin.php?seccion=login");
                } else {
                    header("Location:" . PATH_HOME . "login");
                }
            } else {
                require_once '../admin/cambiarDatos.php';
            }
            break;
        case 'registroCliente':
            permisoLogueado();
            $permisos = array(1, 4);
            permisoRol($permisos);

            require_once '../admin/registrarCliente.php';
            break;
        case 'borrarCliente':
            permisoLogueado();
            $permisos = array(1, 4);
            permisoRol($permisos);

            require_once '../admin/borrarCliente.php';
            break;
        case 'agregarCarrito':

            /*
             * INFO QUE LLEGA DESDE INFO ARTICULO
             * PASADO POR IAN A TRAVES DE FORMULARIO
             * HAY QUE AGREGAR A SESION Y A LA BD
             */

            require_once '../admin/agregarCarrito.php';

            break;
        case "modificarCarrito":

            require_once '../admin/modificacionCarrito.php';
            break;
        case 'borrarCarrito':

            /*
             * BORRAR ARTICULO DE LA SESION
             * Y UPDATE EN LA BD B PARA LUEGO NO SUMARLO
             */
            require_once '../admin/borrarCarrito.php';

            break;
        case 'realizarPedido':

            require_once '../admin/registrarPedido.php';
            break;
        case 'borrarPedido':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            require_once '../admin/borrarPedido.php';
            break;
        case 'modificarPedido':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            require_once '../admin/modificarPedido.php';
            break;
        default :
            if ($localhost) {
                header("Location:./controladorVista.php");
            } else {
                header("Location:" . PATH_HOME);
            }
            break;
    }
} else {
    if ($localhost) {
        header("Location:./controladorVista.php");
    } else {
        header("Location:" . PATH_HOME);
    }
}
?>
