<?php

require_once '../php/config.php';
require_once '../php/funciones.php';
require_once '../php/funcionesPhp.php';
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
    if (isset($_SERVER['HTTP_HOST']) && (isset($_SERVER['PHP_SELF']))) {
        /*
        if ($localhost) {
            $arrayRedireccion = array(PATH_CONTROLLER . 'controladorAdmin.php?seccion=carrito', PATH_CONTROLLER . 'controladorAdmin.php?seccion=infoArticulo', PATH_CONTROLLER . 'controladorCatalogo.php?categoria='.  sanearDatos($_GET['categoria']));
        } else {
         * 
         */
            $arrayRedireccion = array(PATH_HOME . 'micarrito/carrito-paso-1', PATH_HOME . 'infoArticulo/', PATH_HOME . 'catalogo');
        //}
        if (in_array("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $arrayRedireccion)) {
            $continue = "../../".$_SERVER['REQUEST_URI'];
        } else {
            $continue = "miPerfil";
        }
    } else {
        $continue = PATH_HOME;
    }

    $header = array('paths' => $paths,
        'localhost' => $localhost,
        'categorias' => $categorias,
        'perfil' => NULL,
        'continue' => $continue
    );
}


/* Pasaje del arreglo a la vista .twig */
$template->display($header);


if (isset($_GET['categoria']) && is_string($_GET['categoria'])) {
    $categoria = trim(sanearDatos($_GET['categoria']));

    $datoCategoria = mysqli_fetch_assoc(obtenerCategoriaPorNombre($categoria));
	$nombreCategoria = $datoCategoria['nombreCategoria'];
	$nombreCategoriaURL = $datoCategoria['nombreCategoriaUrl'];

    if ($datoCategoria) {
        //echo $datoCategoria['idCategoria'] . " - " . $datoCategoria['nombreCategoria'];

        $articulos = obtenerArticulosPorIdCategoria($datoCategoria['idCategoria']);

        if (isset($_SESSION['user_name']) && ($_SESSION['user_name']) != "") {
            $perfil = sanearDatos($_SESSION['user_type']);
        } else {
            $perfil = NULL;
        }

        $index = array(
            'paths' => $paths,
            'localhost' => $localhost,
            'articulos' => $articulos,
            'idCategoria' => $datoCategoria['idCategoria'],
            'perfil' => $perfil,
			'nombreCategoria' => $nombreCategoria,
			'nombreCategoriaURL' =>$nombreCategoriaURL
        );
        $url = "catalogo.twig";
    } else {
        require_once '../php/admin/securityControl.php';
        
        $url = "paginaError.twig";
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
?>
