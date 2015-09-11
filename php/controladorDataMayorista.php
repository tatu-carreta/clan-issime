<?php

require_once 'config.php';
require_once 'funciones.php';
require_once 'funcionesPhp.php';
require_once 'funcionesPersona.php';
/* Incluir este configPhpTwig cuando se quiera utilizar Twig
 * importante para los paths absolutos y configuracion
 * del Template
 */
require_once 'configPhpTwig.php';

$provincias = obtenerProvincias();
$condiciones = obtenerCondicionIva();


$arreglo = array(
    'paths' => $paths,
    'provincias' => $provincias,
    'condiciones' => $condiciones
);
$template = $twig->loadTemplate("/admin/dataMayorista.twig");
/* Pasaje del arreglo a la vista .twig */
$template->display($arreglo);
?>