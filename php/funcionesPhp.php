<?php

function sanearDatos($tags) {
    $tags = strip_tags($tags);
    $tags = stripslashes($tags);
    $tags = htmlentities($tags);
    return $tags;
}

function blow_crypt($input, $rounds = 7) {
    $salt = "";
    $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
    for ($i = 0; $i < 22; $i++) {
        $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
}

function hashData($string) {
    $string = hash("haval224,4", md5(crypt($string, "$2a$%02d$")));
    return $string;
}

function comprobar_string_sin_especiales($string) {
    if (preg_match('/[A-Za-z]/', $string)) {
        return true;
    } else {
        return false;
    }
}

function comprobar_email($email) {
    return false;
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
}

function cambiarFecha($fecha) {
    return implode("-", array_reverse(explode("-", $fecha)));
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function regenerarContrasena()
{
    // recortamos la cadena, conseguimos nueva pass
    $pass = substr(crypt(microtime()), 1, 5);
    
    return "clan_".$pass;
}

function calcularEdad($fecha) {
    return floor((time() - strtotime($fecha)) / 31556926);
}

function obtenerNombreMes($fecha) {
    $fecha = strtotime($fecha);

    switch (date('m', $fecha)) {
        case 1: $mes = "enero";
            break;
        case 2: $mes = "febrero";
            break;
        case 3: $mes = "marzo";
            break;
        case 4: $mes = "abril";
            break;
        case 5: $mes = "mayo";
            break;
        case 6: $mes = "junio";
            break;
        case 7: $mes = "julio";
            break;
        case 8: $mes = "agosto";
            break;
        case 9: $mes = "septiembre";
            break;
        case 10: $mes = "octubre";
            break;
        case 11: $mes = "noviembre";
            break;
        case 12: $mes = "diciembre";
            break;
    }
    return $mes;
}

?>
