<?php

require_once '../php/config.php';
require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesPersona.php';
require_once '../php/funcionesUsuarios.php';
require_once '../php/funcionesCarrito.php';
require_once '../php/funcionesPedido.php';
require_once '../php/admin/seguridad.php';
/* Incluir este configPhpTwig cuando se quiera utilizar Twig
 * importante para los paths absolutos y configuracion
 * del Template
 */
require_once '../php/configPhpTwig.php';
/* Carga del archivo de la vista .twig */
$template = $twig->loadTemplate("/head.twig");

/* Pasaje del arreglo a la vista .twig */
$template->display($head);

if (isset($_SESSION['user_name']) && (isset($_SESSION['user_type']))) {
    if (($_SESSION['user_type'] == 1) || ($_SESSION['user_type'] == 4)) {
        /* Carga del archivo de la vista .twig */
        $template = $twig->loadTemplate("/headerAdmin.twig");
    } else {
        /* Carga del archivo de la vista .twig */
        $template = $twig->loadTemplate("/header.twig");
    }
} else {
    /* Carga del archivo de la vista .twig */
    $template = $twig->loadTemplate("/header.twig");
}

$campana = obtenerCampana();
if (!empty($campana)) {
    $categorias = obtenerCategorias($campana[0]['idCampana']);
} else {
    $categorias = array();
}

if (isset($_SESSION['user_name']) && ($_SESSION['user_name']) != "") {
    $header = array(
        'paths' => $paths,
        'localhost' => $localhost,
        'nombreUsuario' => sanearDatos($_SESSION['user_name']),
        'iderUser' => sanearDatos($_SESSION['iderUser']),
        'perfil' => sanearDatos($_SESSION['user_type']),
        'categorias' => $categorias
    );
} else {

    if (isset($_SERVER['HTTP_REFERER'])) {
        if ($localhost) {
            $arrayRedireccion = array(PATH_CONTROLLER . 'controladorAdmin.php?seccion=carrito', PATH_CONTROLLER . 'controladorAdmin.php?seccion=infoArticulo', PATH_CONTROLLER . 'controladorAdmin.php?seccion=catalogo');
        } else {
            $arrayRedireccion = array(PATH_HOME . 'micarrito/carrito-paso-1', PATH_HOME . 'infoArticulo/', PATH_HOME . 'catalogo');
        }
        if (in_array($_SERVER['HTTP_REFERER'], $arrayRedireccion)) {
            $continue = $_SERVER['HTTP_REFERER'];
        } else {
            $continue = "miPerfil";
        }
    } else {
        $continue = PATH_HOME;
    }

    $header = array('paths' => $paths,
        'localhost' => $localhost,
        'categorias' => $categorias,
        'continue' => $continue
    );
}

/* Pasaje del arreglo a la vista .twig */
$template->display($header);
/* Configurando la carga de twig */
$loadTwig = $twig;
$pathVista = "../html/";
if (isset($_GET['seccion']) && is_string($_GET['seccion'])) {
    $seccion = sanearDatos($_GET['seccion']);

    switch ($seccion) {
        case 'registrate':
            if (permisoNoLogueado()) {

                $provincias = obtenerProvincias();
                $condiciones = obtenerCondicionIva();

                require_once('../reCaptcha/recaptchalib.php');
                $publickey = RECAPTCHA_PUBLIC_KEY; // you got this from the signup page

                if (isset($_GET['reg']) && (sanearDatos($_GET['reg']) == "sr")) {
                    if ($localhost) {
                        $continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=carrito-paso-2";
                    } else {
                        $continue = PATH_HOME . "micarrito/carrito-paso-2";
                    }
                    $reg = "sr";
                } else {
                    $continue = "";
                    $reg = "";
                }

                $index = array(
                    'paths' => $paths,
                    'localhost' => $localhost,
                    'provincias' => $provincias,
                    'condiciones' => $condiciones,
                    'reCaptcha' => recaptcha_get_html($publickey),
                    'continue' => $continue,
                    'reg' => $reg
                );

                $url = "admin/registroUsuario.twig";
            } else {
                $url = "catalogo.twig";
            }
            break;
        case 'securityQuestions':
            permisoLogueado();
            $permisos = array(2, 3);
            permisoRol($permisos);

            $yaHizo = yaRealizoPreguntaSeguridad(sanearDatos($_SESSION['iderUser']));

            if ($yaHizo > 0) {
                if ($localhost) {
                    header("Location:./controladorAdmin.php?seccion=miperfil&iderUser=" . sanearDatos($_SESSION['iderUser']));
                } else {
                    header("Location:" . PATH_CONTROLLER . "miperfil/" . sanearDatos($_SESSION['iderUser']));
                }
            } else {

                if (!isset($_GET['iderUser']) || ($_GET['iderUser'] == "") || ($_GET['iderUser'] != $_SESSION['iderUser']) || ($_SESSION['ip_user'] != $_SERVER['REMOTE_ADDR']) || (!isset($_GET['sq']))) {
                    $url = "paginaError.twig";
                } else {

                    if (isset($_GET['sq']) && ($_GET['sq'] != "")) {
                        $sq = sanearDatos($_GET['sq']);
                        switch ($sq) {
                            case "sQ":
                                if ($localhost) {
                                    //$continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=miperfil&iderUser=" . sanearDatos($_SESSION['iderUser']);
                                    $continue = PATH_HOME;
                                } else {
                                    //$continue = PATH_HOME . "miperfil/" . sanearDatos($_SESSION['iderUser']);
                                    $continue = PATH_HOME;
                                }
                                break;
                            case "sR":
                                if ($localhost) {
                                    $continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=carrito-paso-2";
                                } else {
                                    $continue = PATH_HOME . "micarrito/carrito-paso-2";
                                }

                                break;
                        }
                    } else {
                        if (isset($_POST['sq']) && ($_POST['sq'] == "sR")) {
                            if ($localhost) {
                                $continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=carrito-paso-2";
                            } else {
                                $continue = PATH_HOME . "micarrito/carrito-paso-2";
                            }
                        } else {
                            if ($localhost) {
                                //$continue = PATH_CONTROLLER . "controladorAdmin.php?seccion=miperfil&iderUser=" . sanearDatos($_SESSION['iderUser']);
                                $continue = PATH_HOME;
                            } else {
                                //$continue = PATH_HOME . "miperfil/" . sanearDatos($_SESSION['iderUser']);
                                $continue = PATH_HOME;
                            }
                        }
                    }

                    $index = array(
                        'paths' => $paths,
                        'localhost' => $localhost,
                        'iderUser' => sanearDatos($_SESSION['iderUser']),
                        'questions' => $securityQuestions,
                        'continue' => $continue
                    );


                    $url = 'admin/securityQuestions.twig';
                }
            }
            break;
        case 'olvideClave':
            if (permisoNoLogueado()) {
                $index = array(
                    'paths' => $paths,
                    'localhost' => $localhost,
                    'questions' => $securityQuestions
                );

                $url = "admin/olvideClave.twig";
            } else {
                $url = "catalogo.twig";
            }
            break;
        case 'pedidos':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            if (isset($_GET['tipoPersona'])) {
                $tipoPersona = sanearDatos($_GET['tipoPersona']);
                $estadoPedido = sanearDatos($_GET['estadoPedido']);
                $datoBusqueda = sanearDatos($_GET['buscar']);

                $pedidos = obtenerPedidosPorFiltro($tipoPersona, $estadoPedido, $datoBusqueda);
            } else {
                $pedidos = obtenerPedidos();
                $datoBusqueda = NULL;
                $estadoPedido = -1;
                $tipoPersona = -1;
            }

            foreach ($pedidos as $pedido => $value) {
                $pedidos[$pedido]['datosPersona']['edad'] = calcularEdad($value['datosPersona']['fechaNacimiento']);
            }

            $tiposPersona = array(
                0 => array(
                    'values' => -1,
                    'nombreTipo' => "Todos los clentes"
                ),
                1 => array(
                    'values' => 2,
                    'nombreTipo' => "Mayorista"
                ),
                2 => array(
                    'values' => 3,
                    'nombreTipo' => "Minorista"
                ),
                3 => array(
                    'values' => 4,
                    'nombreTipo' => "No registrado"
                )
            );

            $estadosPedido = array(
                0 => array(
                    'values' => -1,
                    'nombreEstado' => "Todos los estados"
                ),
                1 => array(
                    'values' => 'I',
                    'nombreEstado' => "Nuevo Pedido"
                ),
                2 => array(
                    'values' => 'E',
                    'nombreEstado' => "Confirmación Enviada"
                ),
                3 => array(
                    'values' => 'P',
                    'nombreEstado' => "Pagada, Enviar"
                ),
                4 => array(
                    'values' => 'V',
                    'nombreEstado' => "Vendido"
                ),
                5 => array(
                    'values' => 'C',
                    'nombreEstado' => "Cancelado"
                )
            );

            $url = "admin/pedidos.twig";

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'pedidos' => $pedidos,
                'datoBusqueda' => $datoBusqueda,
                'tipPerson' => $tiposPersona,
                'estPedido' => $estadosPedido,
                'estadoPedido' => $estadoPedido,
                'tipoPersona' => $tipoPersona
            );

            break;
        case 'prensa':
            if(isset($_SESSION['user_type']))
            {
                $perfil = sanearDatos($_SESSION['user_type']);
            }
            else
            {
                $perfil = 0;
            }
            $url = "admin/prensa.twig";

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'prensa' => obtenerPrensa(),
                'perfil' => $perfil
            );
            break;
        case 'lookbook':
            if(isset($_SESSION['user_type']))
            {
                $perfil = sanearDatos($_SESSION['user_type']);
            }
            else
            {
                $perfil = 0;
            }

            $url = "admin/lookbook.twig";
            $lookbook = obtenerLookbook();
            $secciones = obtenerSeccionesLookbook();
            //var_dump($lookbook);
            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'lookbook' => $lookbook,
                'perfil' => $perfil,
                'secciones' => $secciones
            );
            break;
        case 'locales':
            if(isset($_SESSION['user_type']))
            {
                $perfil = sanearDatos($_SESSION['user_type']);
            }
            else
            {
                $perfil = 0;
            }

            $url = "admin/locales.twig";
            $locales = obtenerLocales();

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'locales' => $locales,
                'perfil' => $perfil
            );
            break;
        case 'categorias':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            $url = "admin/categoriasEditar.twig";

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'campana' => obtenerCampana(),
                'lastId' => obtenerIdInsertarCategoria(),
                'categorias' => obtenerCategoriasActuales()
            );
            break;
        case 'altaCategorias':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            $url = "admin/categorias.twig";

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'campana' => obtenerCampana(),
                'lastId' => obtenerIdInsertarCategoria(),
                'categorias' => obtenerCategoriasPredefinidas()
            );
            break;
        case 'galeria':
            if (isset($_GET['nombreArticulo'])) {
                /*
                 * BUSCAR POR NOMBRE ARTICULO URL
                 */
                $articulo = obtenerArticuloPorNombre(sanearDatos($_GET['nombreArticulo']));
                $colores = obtenerColoresPorArticulo($articulo[0]['idArticulo']);
                $arrayColores = array();
                $arrayTalles = array();
                foreach ($colores as $cor) {

                    $tall = obtenerTallesPorColor($cor['idColor']);
                    //var_dump($tall);
                    $arr = array("color" => $cor, "imgChicas" => obtenerImagenesPorColor($cor['idColor'], "C"), "imgAmpliadas" => obtenerImagenesPorColor($cor['idColor'], "G"), "talles" => $tall);
                    array_push($arrayColores, $arr);
                }
                if (isset($_SESSION['user_type']) and ($_SESSION['user_type'] != '')) {
                    $user_type = sanearDatos($_SESSION['user_type']);
                } else {
                    $user_type = 3;
                }
                $index = array(
                    'paths' => $paths,
                    'localhost' => $localhost,
                    'articulo' => $articulo[0],
                    'arrayColores' => $arrayColores,
                    'talles' => $talles,
                    'user_type' => $user_type
                );
                $url = "galeria.twig";
            }

            break;
        case 'campa':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            $url = "admin/campa.twig";

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'campanas' => obtenerCampana()
            );
            break;
        case 'agregarArticulos':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            $loadTwig = $twig2;
            $pathVista = "../php/";
            $url = "modules/images/index.twig";

            if (isset($_GET['idCategoria'])) {
                $idCategoria = sanearDatos($_GET['idCategoria']);
            } else {
                $idCategoria = 1;
            }

            if (isset($_GET['nombreCategoria'])) {
                $nombreCategoria = $_GET['nombreCategoria'];
            } else {
                $nombreCategoria = "";
            }
            if (isset($_GET['nombreCategoriaURL'])) {
                $nombreCategoriaURL = $_GET['nombreCategoriaURL'];
            } else {
                $nombreCategoriaURL = "asdasd";
            }

            //trae todas las categorias de talles que existen
            $categoriasTalles = obtenerCategoriasDeTalles();
            foreach ($categoriasTalles as $cat) {
                //para cada categoria arma un arreglo de talles 
                $talles = obtenerTallesPorCategoriaTalle($cat['idCategoriaTalle']);
            }


            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'categoria' => $idCategoria,
                'talles' => $talles,
                'nombreCategoria' => $nombreCategoria,
                'nombreCategoriaURL' => $nombreCategoriaURL
            );
            break;

        case 'editarArticulo':

            if (isset($_GET['nombreCategoriaURL'])) {
                $nombreCategoriaURL = $_GET['nombreCategoriaURL'];
            } else {
                $nombreCategoriaURL = "asdasd";
            }
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            $loadTwig = $twig2;
            $pathVista = "../php/";
            $url = "modules/images/edit.twig";

            $idArticulo = sanearDatos($_GET['idArticulo']);
            $articulo = obtenerArticuloPorId($idArticulo);
            $colores = obtenerColoresPorArticulo($idArticulo);
            $arrayColores = array();
            if (isset($_GET['idCategoria'])) {
                $idCategoria = sanearDatos($_GET['idCategoria']);
            } else {
                $idCategoria = 1;
            }
            $maxIdColor = obtenerMaxIdColor();
            $maxIdImagen = obtenerMaxIdImagen();
            /*
              $count = 0;
              foreach($talles as $tall)
              {
              foreach($tall as $key => $value)
              {
              $conect = conectar();
              $sql = "INSERT INTO talle_categoria (idCategoriaTalle, nombreTalle) VALUES (".$count.", '".$value."')";
              if($res = mysqli_query($conect, $sql))
              {
              echo 'se ha subido la tabla';
              }
              }
              $count ++;
              } */
            $arrayTalles = array();
            $tallesArticulo = obtenerTallesPorArticulo($articulo[0]['idCategoriaTalle']);
            //var_dump($tallesArticulo);
            foreach ($colores as $cor) {

                $tall = obtenerTallesPorColor($cor['idColor']);

                $arr = array("color" => $cor, "imgChicas" => obtenerImagenesPorColor($cor['idColor'], "C"), "imgAmpliadas" => obtenerImagenesPorColor($cor['idColor'], "G"), "talles" => $tall);
                array_push($arrayColores, $arr);



                //$arrayTalles = array();
            }

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'articulo' => $articulo,
                'arrayColores' => $arrayColores,
                'talles' => $talles,
                'catTalles' => obtenerCategoriaTalle(),
                'categoria' => $idCategoria,
                'maxIdColor' => $maxIdColor,
                'maxIdImagen' => $maxIdImagen,
                'nombreCategoriaURL' => $nombreCategoriaURL
            );
            //var_dump($arrayTalles);
            break;
        case 'login':
            if (permisoNoLogueado()) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    if ($localhost) {
                        $arrayRedireccion = array(PATH_CONTROLLER . 'controladorAdmin.php?seccion=carrito', PATH_CONTROLLER . 'controladorAdmin.php?seccion=infoArticulo', PATH_CONTROLLER . 'controladorAdmin.php?seccion=catalogo');
                    } else {
                        $arrayRedireccion = array(PATH_HOME . 'carrito', PATH_HOME . 'infoArticulo/', PATH_HOME . 'catalogo');
                    }
                    if (in_array($_SERVER['HTTP_REFERER'], $arrayRedireccion)) {
                        $continue = $_SERVER['HTTP_REFERER'];
                    } else {
                        $continue = "miPerfil";
                    }
                } else {
                    $continue = PATH_HOME;
                }

                if (isset($_SESSION['redir_login']) && ($_SESSION['redir_login'] != "")) {
                    if (filter_var(PATH_CONTROLLER . $_SESSION['redir_login'], FILTER_VALIDATE_URL)) {
                        if ($localhost) {
                            $continue = PATH_CONTROLLER . "controladorVista.php?seccion=informe";
                        } else {

                            $continue = "http://www.clan-issime.com" . $_SESSION['redir_login'];
//CAMBIAR CUANDO SE PASE A LA BUENA
//$continue = PATH_HOME . $_SESSION['redir_login'];
                        }
                    }
                }

                $url = "login.twig";
                $index = array(
                    'paths' => $paths,
                    'localhost' => $localhost,
                    'continue' => $continue
                );
            } else {
                if (isset($_GET['error'])) {

                    if (isset($_SESSION['redir_login']) && ($_SESSION['redir_login'] != "")) {
                        if (filter_var(PATH_CONTROLLER . $_SESSION['redir_login'], FILTER_VALIDATE_URL)) {
                            $txt = "La sesión expiró. Recargue la página y vuelva a intentar.";
                        }
                    }

                    if ($localhost) {
                        header("Location:./controladorVista.php?seccion=informe&txt=".$txt);
                    } else {
                        header("Location:" . PATH_HOME . "informe/".$txt);
                    }
                } else {
                    if ($localhost) {
                        header("Location:./controladorVista.php?seccion=index");
                    } else {
                        header("Location:" . PATH_HOME);
                    }
                }
            }
            break;
        case 'salir':
            permisoLogueado();

            require_once '../admin/cerrarSesion.php';
            break;
        case 'miperfil':
            permisoLogueado();

            $provincias = obtenerProvincias();
            $condiciones = obtenerCondicionIva();
            $persona = obtenerInfoUsuarioPorIderUser(sanearDatos($_GET['iderUser']));
            if (!is_null($persona['idProvincia'])) {
                $ciudades = obtenerLocalidadesPorIdProvincia($persona['idProvincia']);
            } else {
                $ciudades = array();
            }

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'datoPersona' => $persona,
                'provincias' => $provincias,
                'condiciones' => $condiciones,
                'localidades' => $ciudades
            );
            $url = "admin/miPerfil.twig";
            break;
        case 'listadoClientes':
            permisoLogueado();
            $permisos = array(1, 4);
            permisoRol($permisos);

            switch ($_SESSION['user_type']) {
                case 1:
                    $clientes = obtenerClientes();
                    break;
                case 4:
                    $clientes = obtenerUltimoCliente();
                    break;
            }

            foreach ($clientes as $cliente => $value) {
                $clientes[$cliente]['edad'] = calcularEdad($value['fechaNacimiento']);
            }

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'clientes' => $clientes,
                'datoBusqueda' => NULL,
                'perfil' => sanearDatos($_SESSION['user_type'])
            );

            $url = "admin/listadoClientes.twig";
            break;
        case 'buscarCliente':
            permisoLogueado();
            $permisos = array(1);
            permisoRol($permisos);

            if (isset($_POST['buscar']) && ($_POST['buscar'] != "")) {
                $datoBusqueda = sanearDatos($_POST['buscar']);
                $clientes = obtenerClientesPorCriterio(sanearDatos($_POST['buscar']));
            } else {
                $clientes = obtenerClientes();
                $datoBusqueda = NULL;
            }

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'clientes' => $clientes,
                'datoBusqueda' => $datoBusqueda
            );

            $url = "admin/listadoClientes.twig";
            break;
        case 'infoArticulo':

            if (isset($_GET['nombreArticulo'])) {
                /*
                 * BUSCAR POR NOMBRE ARTICULO URL
                 */
                $articulo = obtenerArticuloPorNombre(sanearDatos($_GET['nombreArticulo']));
                $colores = obtenerColoresPorArticulo($articulo[0]['idArticulo']);
                $arrayColores = array();
                $arrayTalles = array();
                foreach ($colores as $cor) {

                    $tall = obtenerTallesPorColor($cor['idColor']);
                    //var_dump($tall);
                    $arr = array("color" => $cor, "imgChicas" => obtenerImagenesPorColor($cor['idColor'], "C"), "imgAmpliadas" => obtenerImagenesPorColor($cor['idColor'], "G"), "talles" => $tall);
                    array_push($arrayColores, $arr);
                }

                if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 1)) {
                    if ($localhost) {
                        header("Location:.controladorAdmin.php?seccion=editarArticulo&idArticulo=" . $articulo[0]['idArticulo']);
                    } else {
                        header("Location:" . PATH_HOME . "editarArticulo/" . $articulo[0]['idArticulo'] . "/admin");
                    }
                }

                if (isset($_SESSION['user_type']) and ($_SESSION['user_type'] != '')) {
                    $user_type = sanearDatos($_SESSION['user_type']);
                } else {
                    $user_type = 3;
                }

                $index = array(
                    'paths' => $paths,
                    'localhost' => $localhost,
                    'articulo' => $articulo[0],
                    'arrayColores' => $arrayColores,
                    'talles' => $talles,
                    'user_type' => $user_type
                );
                $url = "infoArticulo.twig";
            }

            break;
        case 'carrito':

            //$_SESSION['iderCarro'] = "910c76cbc45541d9c4cfe9db1f78708275bd5d36900408f3742d1126";
            if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {
                $iderCarro = sanearDatos($_SESSION['iderCarro']);
            } else {
                $iderCarro = 2;
            }

            $articulos = obtenerArticulosPorCarrito($iderCarro);

            $ultimoArticulo = obtenerUltimoArticuloCarrito($iderCarro);

            if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] != "")) {
                $perfil = sanearDatos($_SESSION['user_type']);

                if ($_SESSION['user_type'] == 1) {
                    if ($localhost) {
                        header("Location:./controladorVista.php?seccion=index");
                    } else {
                        header("Location:" . PATH_HOME);
                    }
                }
            } else {
                $perfil = 3;
            }



            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'articulos' => $articulos,
                'perfil' => $perfil,
                'seguirCategoria' => $ultimoArticulo
            );

            $url = "admin/carrito.twig";


            break;
        case 'carrito-paso-2':

            if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {

                $carrito = obtenerArticulosPorCarrito(sanearDatos($_SESSION['iderCarro']));

                if (count($carrito) > 0) {

                    $datosPersona = array(
                    );

                    $provincias = obtenerProvincias();

                    $ciudades = array();

                    $url2 = "admin/carrito-paso-2.twig";
                    //$url2 = "admin/carrito-paso-2-error.twig";
                    if (isset($_SESSION['user_name']) && (isset($_SESSION['ip_user'])) && ($_SESSION['private'] == $_SESSION['private_alternative']) && ($_SESSION['user_type'] != 1)) {

                        $datosPersona = obtenerInfoUsuarioPorIderUser(sanearDatos($_SESSION['iderUser']));

                        if (!is_null($datosPersona['idProvincia'])) {
                            $ciudades = obtenerLocalidadesPorIdProvincia($datosPersona['idProvincia']);
                        }
                    } else {
                        if (!isset($_GET['reg']) || (sanearDatos($_GET['reg']) != "sr" )) {
                            $url2 = "admin/registroLogin.twig";
                            //$url2 = "admin/carrito-paso-2-error.twig";
                        }
                    }

                    $index = array(
                        'paths' => $paths,
                        'localhost' => $localhost,
                        'datosPersona' => $datosPersona,
                        'provincias' => $provincias,
                        'localidades' => $ciudades
                    );
                } else {
                    if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {
                        $iderCarro = sanearDatos($_SESSION['iderCarro']);
                    } else {
                        $iderCarro = 2;
                    }

                    $articulos = obtenerArticulosPorCarrito($iderCarro);

                    if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] != "")) {
                        $perfil = sanearDatos($_SESSION['user_type']);
                    } else {
                        $perfil = 3;
                    }

                    $url2 = "admin/carrito.twig";

                    $index = array(
                        'paths' => $paths,
                        'localhost' => $localhost,
                        'articulos' => $articulos,
                        'perfil' => $perfil
                    );
                }
                $url = $url2;
            } else {
                if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {
                    $iderCarro = sanearDatos($_SESSION['iderCarro']);
                } else {
                    $iderCarro = 2;
                }

                $articulos = obtenerArticulosPorCarrito($iderCarro);

                if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] != "")) {
                    $perfil = sanearDatos($_SESSION['user_type']);
                } else {
                    $perfil = 3;
                }

                $url = "admin/carrito.twig";

                $index = array(
                    'paths' => $paths,
                    'localhost' => $localhost,
                    'articulos' => $articulos,
                    'perfil' => $perfil
                );
            }

            break;
        default :

            require_once '../php/admin/securityControl.php';

            $url = "paginaError.twig";
            break;
    }
} else {
    require_once '../php/admin/securityControl.php';

    $url = "paginaError.twig";
}

/* Carga del archivo de la vista .twig */
if (file_exists($pathVista . $url)) {
    $template = $loadTwig->loadTemplate("/" . $url);
} else {
    require_once '../php/admin/securityControl.php';

    $template = $loadTwig->loadTemplate("/paginaError.twig");
}

/* Pasaje del arreglo a la vista .twig */
$template->display($index);

/* Carga del archivo de la vista .twig */
$template = $twig->loadTemplate("/footer.twig");

$footer = array(
    'paths' => $paths,
    'categorias' => $categorias,
    'localhost' => $localhost
);
/* Pasaje del arreglo a la vista .twig */
$template->display($footer);
?>
