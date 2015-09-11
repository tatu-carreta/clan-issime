<?php

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
	$mensaje .= "<tr><td><img src='".PATH_PHP_ADMIN."mails/imagenes/cabecera-clave.png' alt='Clan Issime / Compra online'></td></tr>";


$mensaje .= "<!-- cuerpo -->";
	$mensaje .= "<tr>";
	$mensaje .= "<td>";
		$mensaje .= "<!-- tabla cuerpo 2 filas -->";
		$mensaje .= "<table width='650' border='0' cellpadding='0' cellspacing='0' style='font-family:arial, sans-serif; font-size:14px; padding:10px 50px'>";
			$mensaje .= "<tr>";
			$mensaje .= "<td style='font-size:16px; text-align:center'>";
			$mensaje .= "<p>	Tu nueva clave es: <strong>".$estadoRegistro."</strong><br>
				Pod&eacute;s entrar a tu perfil de usuario para cambiarla.</p>";
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
    $asunto = utf8_decode('Recuperación de Contraseña Clan Issime');;
    
    
if(mail($para, $asunto, $mensaje, $header))
{
    $texto = "Le hemos enviado un mail con la nueva contraseña. Una vez que ingrese en el sistema, cámbiela. Sea prudente.";
}
else
{
    $texto = 'Hemos tenido un problema al enviarle la nueva contraseña. Intentelo nuevamente.';
}


?>
