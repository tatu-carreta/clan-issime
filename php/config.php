<?php
session_start(); //comentar esta linea si no se trabaja con sesiones
require_once 'admin/sessionControl.php';
ini_set('default_charset','utf8');

if (isset($redirectAdmin)) {
    $_SESSION['redir_login'] = $redirectAdmin;
}

if (!isset($_SESSION['carro'])) {
    $_SESSION['carro'] = array(); //variable de session que lleva los artículos del carrito.           
}
$localhost = false; //define si se esta trabajando a modo local o no
$proyecto = "Clan-Issime";
switch ($_SERVER['HTTP_HOST']) {
    case "www.clan-issime.com":
        define("URL_PRODUCTION", "http://www.clan-issime.com/");
        break;
    case "clan-issime.com":
        define("URL_PRODUCTION", "http://clan-issime.com/");
        break;
    default :
        define("URL_PRODUCTION", "http://www.clan-issime.com/");
        break;
}
define("URL_LOCAL", "http://localhost/clan-issime/");

//Credenciales Mercado Pago

  define("CLIENT_ID","4473558382147124");
  define("CLIENT_SECRET","wIhsMel3IplXnjBYhVJ3Ma7PpuTzbs3H");


//INFORMACIÓN RECAPTCHA
define("RECAPTCHA_PUBLIC_KEY", "6LeCzewSAAAAAFrMloUH_XgKqvIuajb9-1sA80OW");
define("RECAPTCHA_PRIVATE_KEY", "6LeCzewSAAAAALcoUW2IK8dJV6l0kQVFPIbmSu2x");

if (!$localhost) {
    define("DB_HOST", "190.228.29.58");
    define("DB_USER", "clan_user_issime");
    define("DB_PASS", "clan_pass_issime");
    define("DB_SELECTED", "clan_issime_on_air");
    define("REDIRECT_PATH", URL_PRODUCTION); //URL absoluta a donde redirige luego de iniciar o cerrar sesion
    define("PATH_HOME", URL_PRODUCTION);
    define("PATH_CSS", URL_PRODUCTION . "css/");
    define("PATH_PHP", URL_PRODUCTION . "php/");
    define("PATH_HTML", URL_PRODUCTION . "html/");
    define("PATH_JS", URL_PRODUCTION . "js/");
    define("PATH_ADMIN", URL_PRODUCTION . "admin/");
    define("PATH_CONTROLLER", URL_PRODUCTION . "controller/");
    define("PATH_IMAGES", URL_PRODUCTION . "images/");
    define("PATH_IMAGES_PERMANENTES", URL_PRODUCTION . "images/permanentes/");
    define("PATH_IMAGES_HOME", URL_PRODUCTION . "images/home/");
    define("PATH_IMAGES_MINI", URL_PRODUCTION . "images/miniaturas/");
    define("PATH_IMAGES_CHICA", URL_PRODUCTION . "images/chica/");
    define("PATH_IMAGES_CARRITO", URL_PRODUCTION . "images/carrito/");
    define("PATH_IMAGES_AMPLIACION", URL_PRODUCTION . "images/ampliacion/");
    define("PATH_IMAGES_CATALOGO", URL_PRODUCTION . "images/catalogo/");
    define("PATH_IMAGES_FABRICA", URL_PRODUCTION . "images/fabrica/");
	define("PATH_IMAGES_PRENSA", URL_PRODUCTION . "images/prensa/");
    define("PATH_IMAGES_TARJETAS", URL_PRODUCTION . "images/tarjetas/");
    define("PATH_PHP_ADMIN", PATH_PHP . "admin/");
    define("PATH_PHP_MODULES_IMAGES", PATH_PHP . "modules/images/");
    define("PATH_PRENSA", PATH_HOME . "prensa");
} else {
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_SELECTED", "clan_issime");
    define("REDIRECT_PATH", URL_LOCAL); //URL absoluta a donde redirige luego de iniciar o cerrar sesion
    define("PATH_HOME", URL_LOCAL);
    define("PATH_CSS", URL_LOCAL . "css/");
    define("PATH_PHP", URL_LOCAL . "php/");
    define("PATH_HTML", URL_LOCAL . "html/");
    define("PATH_JS", URL_LOCAL . "js/");
    define("PATH_ADMIN", URL_LOCAL . "admin/");
    define("PATH_CONTROLLER", URL_LOCAL . "controller/");
    define("PATH_IMAGES", URL_LOCAL . "images/");
    define("PATH_IMAGES_PERMANENTES", URL_LOCAL . "images/permanentes/");
    define("PATH_IMAGES_HOME", URL_LOCAL . "images/home/");
    define("PATH_IMAGES_MINI", URL_LOCAL . "images/miniaturas/");
    define("PATH_IMAGES_CHICA", URL_LOCAL . "images/chica/");
    define("PATH_IMAGES_CARRITO", URL_LOCAL . "images/carrito/");
    define("PATH_IMAGES_AMPLIACION", URL_LOCAL . "images/ampliacion/");
    define("PATH_IMAGES_FABRICA", URL_LOCAL . "images/fabrica/");
	define("PATH_IMAGES_PRENSA", URL_LOCAL . "images/prensa/");
    define("PATH_IMAGES_TARJETAS", URL_LOCAL . "images/tarjetas/");
    define("PATH_IMAGES_CATALOGO", URL_LOCAL . "images/catalogo/");
    define("PATH_PHP_ADMIN", PATH_PHP . "admin/");
    define("PATH_PHP_MODULES_IMAGES", PATH_PHP . "modules/images/");
    define("PATH_PRENSA", PATH_HOME . "controller/controladorAdmin.php?seccion=prensa");
}

$aDb = array(
    'usuarios' => array(
        "idUsuario" => "INT (11)",
        "usuario" => "VARCHAR(50)",
        "clave" => "VARCHAR(50)",
        "nombre" => "VARCHAR(50)",
        "apellido" => "VARCHAR(50)",
        "email" => "VARCHAR(50)",
        "direccionPorDefecto" => "VARCHAR(200)",
        "ciudad" => "VARCHAR(100)",
        "codigoPostal" => "VARCHAR(10)",
        "provincia" => "VARCHAR(100)",
        "telefono" => "VARCHAR(50)",
        "formaDePagoPreferencial" => "INT(11)", // asociado a las formas de pago
        "categoria" => "VARCHAR(200)" // minorista o mayorista					
    ),
    //datos que se le agregan solo a un usuario mayorista:
    'mayoristas' => array(
        "idUsuario" => "INT (11)", //es la clave unica, se refiere al id del usuario
        "cuit" => "VARCHAR(200)",
        "condicionIva" => "INT (11)"
    ),
    'categoria' => array(
        "idCategoria" => "INT (11)",
        "nombre" => "VARCHAR(200)",
        "vigencia" => "BOOLEAN" //si esta publicada o no								
    ),
    'articulos' => array(
        "codigo" => "VARCHAR (100)", //clave unica, si ya existe no se puede insertar
        "nombre" => "VARCHAR (200)",
        "material" => "VARCHAR(200)",
        "idCategoria" => "INT(11)", // lo asocia a la categoria a la que pertenece
        "precioMinorista" => "REAL",
        "precioMayorista" => "REAL",
        "fotoDestacada" => "INT(11)", // el id de la foto que se muestra en el catalogo
        "esOferta" => "BOOLEAN"
    ),
    //los colores no se repiten en distintos articulos
    'colores' => array(
        "idColor" => "INT (11)",
        "idArticulo" => "INT (11)",
        "descripcion" => "VARCHAR(200)"
    ),
    //los colores tienen talles, no los articulos
    'talles' => array(
        "idTalle" => "INT (11)",
        "idColor" => "INT(11)",
        "talle" => "VARCHAR(50)"
    ),
    'fotos' => array(
        "idFoto" => "INT (11)",
        "idColor" => "INT (11)",
        "tipo" => "CHAR(1)", //ampliada (A) chica (C) o mediana(M)
        "url" => "VARCHAR(200)"
    //ampliada chica y mediana estan relacionadas por el idColor
    ),
    'compras' => array(
        "idCompra" => "INT (11)",
        "idUsuario" => "INT (11)",
        "fecha" => "DATE",
        "estado" => "VARCHAR(200)", //realizada, pendiente, etc
        "montoFinal" => "REAL",
        "tipoDePago" => "INT(11)" //el id del tipo de pago 
    ),
    //el listado de articulos de una determinada compra
    'articulosDeCompra' => array(
        "idCompra" => "INT (11)",
        "idTalle" => "INT (11)", //todos los articulos tienen colores y talles???
        "cantidad" => "INT (11)",
        "montoUnidad" => "REAL"
    ),
    'locales' => array(
        "idLocal" => "INT (11)",
        "idUsuario" => "INT (11)"  //todos los locales son usuarios, si no existe el usuario se crea con un nombre y una clave por defecto que no sea valida para el ingreso
    ),
    'tiposDePago' => array(
        "idTipoDePago" => "INT (11)",
        "nombre" => "VARCHAR (200)"
    ),
    'ofertas' => array(
        "codigo" => "VARCHAR (200)",
        "idUsuario" => "INT (11)",
        "fechaDesde" => "DATE",
        "fechaHasta" => "DATE",
        "descripcion" => "VARCHAR (200)"
    )
);

$aConfig = array(
    //CONFIG BASE DE DATOS
    'usuario_db' => 'root',
    'clave_usuario_db' => '',
    'db_seleccionada' => 'clan-issime',
    'secciones' => array(
        "catalogo" => 1,
        "lookbook" => 2,
        "prensa" => 3,
        "locales" => 4,
        "empresa" => 5,
        "contacto" => 6
    )
);

$talles = array(
    array(),
    array("01", "02", "03", "04"),
    array('40', '42', '44', '46', '48'),
    array('24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36'),
    array('35', '36', '37', '38', '39', '40', '41'),
    array('06', '12', '18', '1', '2', '4', '6', '8', '10', '12', '14'),
    array('24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34'),
);
//require_once 'textos.php';
?>