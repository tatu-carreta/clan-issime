<?php

if (file_exists("../Twig-1.14.2/lib/Twig/Autoloader.php")) {
    require_once '../Twig-1.14.2/lib/Twig/Autoloader.php';
} else {
    require_once 'Twig-1.14.2/lib/Twig/Autoloader.php';
}
Twig_Autoloader::register();

$i= rand(0,50);
//PARA CARPETA HTML
if (file_exists("../html")) {
    $templateDir = "../html";
} else {
    $templateDir = "html";
}
$loader = new Twig_Loader_Filesystem($templateDir);
$twig = new Twig_Environment($loader);

//PARA CARPETA PHP
if (file_exists("../php")) {
    $templateDir2 = "../php";
} else {
    $templateDir2 = "php";
}
$loader2 = new Twig_Loader_Filesystem($templateDir2);
$twig2 = new Twig_Environment($loader2);


$paths = array('PATH_HOME' => PATH_HOME,
    'PATH_CSS' => PATH_CSS,
    'PATH_PHP' => PATH_PHP,
    'PATH_HTML' => PATH_HTML,
    'PATH_JS' => PATH_JS,
    'PATH_ADMIN' => PATH_ADMIN,
    'PATH_CONTROLLER' => PATH_CONTROLLER,
    'PATH_IMAGES' => PATH_IMAGES,
    'PATH_IMAGES_PERMANENTES' => PATH_IMAGES_PERMANENTES,
    'PATH_IMAGES_HOME' => PATH_IMAGES_HOME,
    'PATH_IMAGES_MINI' => PATH_IMAGES_MINI,
    'PATH_IMAGES_CHICA' => PATH_IMAGES_CHICA,
    'PATH_IMAGES_CARRITO' => PATH_IMAGES_CARRITO,
    'PATH_IMAGES_CATALOGO' => PATH_IMAGES_CATALOGO,
    'PATH_IMAGES_AMPLIACION' => PATH_IMAGES_AMPLIACION,
    'PATH_IMAGES_FABRICA' => PATH_IMAGES_FABRICA,
	'PATH_IMAGES_PRENSA' => PATH_IMAGES_PRENSA,
    'PATH_IMAGES_TARJETAS' => PATH_IMAGES_TARJETAS,
    'PATH_PHP_ADMIN' => PATH_PHP_ADMIN,
    'PATH_PHP_MODULES_IMAGES' => PATH_PHP_MODULES_IMAGES
);

$pathsJson = array('PATH_HOME' => json_encode(PATH_HOME),
    'PATH_CSS' => json_encode(PATH_CSS),
    'PATH_PHP' => json_encode(PATH_PHP),
    'PATH_HTML' => json_encode(PATH_HTML),
    'PATH_JS' => json_encode(PATH_JS),
    'PATH_ADMIN' => json_encode(PATH_ADMIN),
    'PATH_CONTROLLER' => json_encode(PATH_CONTROLLER),
    'PATH_IMAGES' => json_encode(PATH_IMAGES),
    'PATH_IMAGES_PERMANENTES' => json_encode(PATH_IMAGES_PERMANENTES),
    'PATH_IMAGES_HOME' => json_encode(PATH_IMAGES_HOME),
    'PATH_IMAGES_MINI' => json_encode(PATH_IMAGES_MINI),
    'PATH_IMAGES_CHICA' => json_encode(PATH_IMAGES_CHICA),
    'PATH_IMAGES_CARRITO' => json_encode(PATH_IMAGES_CARRITO),
    'PATH_IMAGES_CATALOGO' => json_encode(PATH_IMAGES_CATALOGO),
    'PATH_IMAGES_AMPLIACION' => json_encode(PATH_IMAGES_AMPLIACION),
    'PATH_IMAGES_FABRICA' => json_encode(PATH_IMAGES_FABRICA),
	'PATH_IMAGES_PRENSA' => json_encode(PATH_IMAGES_PRENSA),
    'PATH_IMAGES_TARJETAS' => json_encode(PATH_IMAGES_TARJETAS),
    'PATH_PHP_ADMIN' => json_encode(PATH_PHP_ADMIN),
    'PATH_PHP_MODULES_IMAGES' => json_encode(PATH_PHP_MODULES_IMAGES)
);


$head = array('paths' => $paths,
    'pathsJson' => $pathsJson,
    'rand' => $i
);

$header = array('paths' => $paths,
    'localhost' => $localhost
);

$menu = array(
    'paths' => $paths
);

$footer = array(
    'paths' => $paths
);

$index = array(
    'paths' => $paths,
);
?>
