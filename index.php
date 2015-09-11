<?php

//    header("Location:controller/controladorVista.php?seccion=index");
?>

<?php

require_once 'php/config.php';
/* Incluir este configPhpTwig cuando se quiera utilizar Twig
 * importante para los paths absolutos y configuracion
 * del Template
 */
require_once 'php/admin/seguridad.php';
require_once 'php/funciones.php';
require_once 'php/funcionesPhp.php';
require_once 'php/funcionesUsuarios.php';
require_once 'php/configPhpTwig.php';
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

$url = "index.twig";

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
