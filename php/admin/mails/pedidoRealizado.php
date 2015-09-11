<?php

require_once '../php/funcionesPhp.php';
require_once '../php/funcionesPersona.php';


$datosCiudad = obtenerCiudadPorId($ciudad);

/*
 * CABECERA DEL MAIL
 */
$header = "From: ventasonline@clan-issime.com \r\n";
//$header = "From: max.angletti@gmail.com \r\n";
$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
$header .= "Mime-Version: 1.0 \r\n";
$header .= "Content-Type: text/html; charset=\"utf8\"\n";

/*
 * CUERPO DEL MAIL
 */
$mensaje = "<!DOCTYPE html>";
$mensaje .= "<html lang='es'>";
$mensaje .= "<head>";
$mensaje .= "<meta charset='UTF-8'>";
$mensaje .= "<title>Clan Issime</title>";
$mensaje .= "</head>";

$mensaje .= "<body>";
$mensaje .= "<table width='650' border='0' cellpadding='0' cellspacing='0' style='font-family:arial, sans-serif; font-size:14px; color:#333; border:1px solid #CCC; margin-left:auto; margin-right:auto; margin-bottom:30px'>";
$mensaje .= "<!-- cabecera -->";
$mensaje .= "<tr><td><img src='" . PATH_PHP_ADMIN . "mails/imagenes/cabecera-compra.png' alt='Clan Issime / Compra online'></td></tr>";


$mensaje .= "<!-- cuerpo -->";
$mensaje .= "<tr>";
$mensaje .= "<td>";
$mensaje .= "<!-- tabla cuerpo 2 filas -->";
$mensaje .= "<table width='650' border='0' cellpadding='0' cellspacing='0' style='font-family:arial, sans-serif; font-size:14px; padding:10px 50px'>";
$mensaje .= "<tr>";
$mensaje .= "<td>";
$mensaje .= "<p><strong>NOTA DE PEDIDO Nro: </strong>" . $estadoRegistro . "<br>";
$mensaje .= "<strong>Fecha: </strong>" . date("d") . " de " . obtenerNombreMes(date("Y-m-d")) . " " . date("Y") . "</p>";
$mensaje .= "</td>";
$mensaje .= "</tr>";

$mensaje .= "<tr>";
$mensaje .= "<td>";

$mensaje .= "<p><strong>DATOS DEL COMPRADOR</strong></p>";
$mensaje .= "<table width='550' border='0' style='font-family:arial, sans-serif; font-size:14px'>";
$mensaje .= "<tr><td style='background:#F0F0E1; width:150px; padding:8px 10px'>Nombre y apellido:</td><td style='background:#F0F0E1; padding:5px 10px'>" . utf8_decode($nombre) . utf8_decode($apellido) . "</td></tr>";
$mensaje .= "<tr><td style='background:#F0F0E1; padding:8px 10px'>Email:</td><td style='background:#F0F0E1; padding:5px 10px'>" . utf8_decode($email) . "</td></tr>";
$mensaje .= "<tr><td style='background:#F0F0E1; padding:8px 10px'>Tel&eacute;fono:</td><td style='background:#F0F0E1; padding:5px 10px'>" . utf8_decode($telefono) . "</td></tr>";
$mensaje .= "<tr><td style='background:#F0F0E1; padding:8px 10px'>Direcci&oacute;n:</td><td style='background:#F0F0E1; padding:5px 10px'>" . utf8_decode($calle) . " Nº" . utf8_decode($numero) . " ";
if (($piso != "") && ($departamento != "")) {
    $mensaje .= utf8_decode($piso) . utf8_decode($departamento)." ";
}
$mensaje .= utf8_decode($datosCiudad['nombreCiudad']) . "</td></tr>";
$mensaje .= "<tr><td style='background:#F0F0E1; padding:8px 10px'>Provincia:</td><td style='background:#F0F0E1; padding:5px 10px'>" . utf8_decode($datosCiudad['nombreProvincia']) . "</td></tr>";
$mensaje .= "</table>";

$mensaje .= "<p><strong>PRODUCTOS SELECCIONADOS</strong></p>";

$mensaje .= "<!-- tabla productos -->";
$mensaje .= "<table width='550' border='0' style='font-family:arial, sans-serif; font-size:12px'>";
$articulosPedidos = obtenerArticulosPorCarrito($iderCarro);

$mensaje .= "<tr><td style='background:#F0F0E1; padding:8px 10px; width:72px'>FOTO:</td>";
$mensaje .= "<td style='background:#F0F0E1; padding:8px 10px'>DETALLE:</td>";
$mensaje .= "<td style='background:#F0F0E1; padding:8px 10px; text-align:right; width:72px'>TOTAL:</td></tr>";



foreach ($articulosPedidos as $arts) {
    foreach ($arts['colores'] as $colores) {
        $mensaje .= "<tr><td style='padding:8px 10px'><img style='height:90px;width:70px;' src='" . PATH_IMAGES_CATALOGO . $colores['datosColor']['nombreImagen'] . "' alt='Foto de producto'></td>";

        foreach ($colores['talles'] as $talles) {
            if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 2)) {
                $precioUnidad = $arts['datosArticulo']['precioMayorista'];
            } else {
                if (is_null($arts['datosArticulo']['precioOferta'])) {
                    $precioUnidad = $arts['datosArticulo']['precioMinorista'];
                } else {
                    $precioUnidad = $arts['datosArticulo']['precioOferta'];
                }

                $mensaje .= "<td style='padding:8px 10px'>Art: " . $arts['datosArticulo']['nombreArticulo'] . "<br>Color: " . $colores['datosColor']['nombreColor'] . " / Talle:" . $talles['nombreTalle'] . " / Precio: $" . $precioUnidad . " / Cant: " . $talles['cantidad'] . "</td>";
                $mensaje .= "<td style='padding:8px 10px; text-align:right'>$" . $precioUnidad * $talles['cantidad'] . "</td></tr>";
            }
        }
    }
}




$mensaje .= "<tr><td style='background:#F0F0E1; padding:8px 10px' colspan='2'>TOTAL PRODUCTOS:</td><td style='background:#F0F0E1; padding:5px 10px; text-align:right'>$" . $montoTotal . "</td></tr>";


$mensaje .= "</table>";

$mensaje .= "<p><strong>C&Oacute;MO SIGUE:</strong></p>";

$mensaje .= "<table style='border-bottom:1px solid #666'>";
$mensaje .= "<tr>";
$mensaje .= "<td style='width:40px' valign='top' ><img src='" . PATH_PHP_ADMIN . "mails/imagenes/paso1.png' alt='Foto de producto'></td>";
$mensaje .= "<td style='padding-bottom:12px'>Te enviaremos un mail confirmando la disponibilidad de los productos que elegiste y un link para que realices el pago.</td>";
$mensaje .= "</tr>";
$mensaje .= "<tr>";
$mensaje .= "<td valign='top' ><img src='" . PATH_PHP_ADMIN . "mails/imagenes/paso2.png' alt='Foto de producto'></td>";
$mensaje .= "<td style='padding-bottom:20px'>Ten&eacute;s 72 horas para realizar el pago y cuando lo confirmamos, te enviamos el producto por OCA. Acordate que vos pag&aacute;s el env&iacute;o.</td>";
$mensaje .= "</tr>";

$mensaje .= "</table>";


$mensaje .= "</td>";
$mensaje .= "</tr>";
$mensaje .= "</table>";
$mensaje .= "</td>";
$mensaje .= "</tr>";

$mensaje .= "<!-- pie -->";
$mensaje .= "<tr>";
$mensaje .= "<td style='text-align:center; line-height:18px'>";
$mensaje .= "<p  style='font-size:16px; margin-bottom:12px'>Muchas gracias por elegirnos! Seguimos en contacto</p>";
$mensaje .= "<p style='font-size:22px; margin-top:12px'>www.clan-issime.com</p>";
$mensaje .= "</td>";
$mensaje .= "</tr>";
$mensaje .= "</table>";
$mensaje .= "</body>";
$mensaje .= "</html>";

/*
 * DESTINATARIO DEL MAIL
 */
$para = ''.$email.', ventasonline@clan-issime.com';
//$para = "max.angletti@gmail.com, pocho_pincha@hotmail.com, ventas@clan-issime.com";
$asunto = 'Informe de pedido realizado Clan Issime';


if (mail($para, $asunto, $mensaje, $header)) {
    if ($_SESSION['user_type'] == 2) {
        //Es mayorista
        $texto = "El pedido fue iniciado. Nos comunicaremos con vos para confirmar y coordinar la entrega.";
    } else if ($_SESSION['user_type'] == 3) {
        //Es minorista
        $texto = "La compra fue iniciada. Te enviaremos un mail confirmando la disponibilidad de los productos que elegiste y un link para que realices el pago.";
    } else {
        $texto = "El pedido fue iniciado. Le enviamos un mail con el resumen del mismo.";
    }
} else {
    $texto = 'El pedido fue iniciado, pero no hemos podido enviarle el mail correctamente. Nos pondremos en contacto en breve.';
}
?>
