<?php

require_once '../php/config.php';
/* Incluir este configPhpTwig cuando se quiera utilizar Twig
 * importante para los paths absolutos y configuracion
 * del Template
 */
require_once '../php/admin/seguridad.php';
require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesUsuarios.php';
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

if (isset($_GET['seccion']) && is_string($_GET['seccion'])) {
    $seccion = sanearDatos($_GET['seccion']);

    switch ($seccion) {
        case 'index':
            $url = "index.twig";
            //require_once '../html/index.php';
            break;
        case 'lookbook':
            $url = "lookbook.twig";
            //require_once '../html/lookbook.php';
            break;
        case 'prensa':
            $url = "prensa.twig";
            //require_once '../html/prensa.php';
            break;
        case 'locales':
            $url = "locales.twig";
            //require_once '../html/locales.php';
            break;
        case 'empresa':
            $url = "empresa.twig";
            //require_once '../html/empresa.php';
            break;
        case 'consultas':
            $url = "consultas.twig";
            //require_once '../html/consultas.php';
            break;
        case 'unite':
            $url = "unite.twig";
            //require_once '../html/unite.php';
            break;
        case 'ventasxmayor':
            $url = "ventasxmayor.twig";
            //require_once '../html/ventasxmayor.php';
            break;
        case 'corredores':
            $url = "corredores.twig";
            //require_once '../html/corredores.php';
            break;
        case 'informe':
            require_once '../php/admin/securityControl.php';

            if ($_SESSION['user_type'] == 1) {
                if ($localhost) {
                    header("Location:./controladorVista.php?seccion=index");
                } else {
                    header("Location:" . PATH_HOME);
                }
            }

            $url = "paginaError.twig";

            if (isset($_GET['txt'])) {
                $texto = sanearDatos($_GET['txt']);
            } else {
                $texto = NULL;
            }

            $index = array(
                'paths' => $paths,
                'localhost' => $localhost,
                'texto' => html_entity_decode($texto)
            );
        default :
            require_once '../php/admin/securityControl.php';

            $url = "paginaError.twig";
            //require_once '../html/paginaError.php';
            break;
    }
} else {
    require_once '../php/admin/securityControl.php';

    $url = "paginaError.twig";
}

/* Carga del archivo de la vista .twig */
$template = $twig->loadTemplate("/" . $url);

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
/*
  echo "U -> " . hashData("clan") . " - P -> " . hashData("issime");

  var_dump(obtenerUsuarioPorNombreClave("maxi@prueba.com", "888"));

  var_dump($_SESSION);

  echo hashData("8");

  var_dump(comprobar_string_sin_especiales("maxi@prueba.com"));
 * 
 */
?>
