<?php

if (!isset($localhost)) {
    require_once '../php/config.php';
    $noLocalhost = true;
}


/*
 * Pregunta por que todos los datos necesarios se reciban
 * correctamente, en caso de no se así no prosigue con
 * el alta.
 */
require_once '../php/funciones.php';
require_once '../php/admin/validatePedido.php';
require_once '../php/funcionesPhp.php';
require_once '../php/funcionesCarrito.php';
require_once '../php/funcionesPedido.php';


if (isset($noLocalhost) && ($noLocalhost)) {
    require_once '../php/admin/securityControl.php';
}


if (isset($_POST['idPersona']) && ($_POST['idPersona'] != "")) {
    //Si esta logueado, el idPersona está seteado
    $persona = true;
} else {
    //No esta logueado, por lo tanto se validan
    $persona = false;
}

$infoValidate = validarDatosRegistroPedido();
//$infoValidate['estado'] = true;
if ($infoValidate['estado']) {

    if (!$localhost) {
        require_once '../reCaptcha/lib/mercadopago.php';
        $mp = new MP(CLIENT_ID, CLIENT_SECRET);
    }

    $montoTotal = 0;


    if (isset($_SESSION['iderCarro']) && ($_SESSION['iderCarro'] != "")) {
        $iderCarro = sanearDatos($_SESSION['iderCarro']);

        $articulosCarrito = obtenerArticulosPorCarrito($iderCarro);

        $nombre = sanearDatos($_POST['nombre']);
        $apellido = sanearDatos($_POST['apellido']);
        $email = sanearDatos($_POST['email']);
        $telefono = sanearDatos($_POST['telefono']);
        $calle = sanearDatos($_POST['calle']);
        $numero = sanearDatos($_POST['numero']);
        $piso = sanearDatos($_POST['piso']);
        $dpto = sanearDatos($_POST['departamento']);
        $codPostal = sanearDatos($_POST['codPostal']);
        $ciudad = sanearDatos($_POST['localidad']);
        $provincia = sanearDatos($_POST['provincia']);
        $fecNac = cambiarFecha(str_replace("/", "-", sanearDatos(trim($_POST['fecNac']))));

        if ($persona) {

            //Si esta logueado, entonces el idPersona ya existe
            $idPersona = sanearDatos($_POST['idPersona']);

            $estadoUpdate = realizarModificacionPersona($nombre, $apellido, $telefono, $calle, $numero, $piso, $dpto, $codPostal, $ciudad, $fecNac, $idPersona);

            if ($_SESSION['user_type'] == 2) {
                //Es mayorista
                foreach ($articulosCarrito as $artCarro) {
                    foreach ($artCarro['colores'] as $colores) {
                        foreach ($colores['talles'] as $talles) {
                            $montoTotal = $montoTotal + ($artCarro['datosArticulo']['precioMayorista'] * $talles['cantidad']);
                        }
                    }
                }
            } elseif ($_SESSION['user_type'] == 3) {
                //Es minorista

                foreach ($articulosCarrito as $artCarro) {
                    foreach ($artCarro['colores'] as $colores) {
                        foreach ($colores['talles'] as $talles) {
                            if (is_null($artCarro['datosArticulo']['precioOferta'])) {
                                $montoTotal = $montoTotal + ($artCarro['datosArticulo']['precioMinorista'] * $talles['cantidad']);
                            } else {
                                $montoTotal = $montoTotal + ($artCarro['datosArticulo']['precioOferta'] * $talles['cantidad']);
                            }
                        }
                    }
                }
            }
        } else {

            //No esta logueado, por lo tanto agrego a la persona
            //que realizo el pedido (como no registrado) y obtengo
            //el idPersona correspondiente

            $estadoRegistro = realizarAltaPersona($nombre, $apellido, $email, $telefono, $calle, $numero, $piso, $dpto, $codPostal, $ciudad, $fecNac);

            $idPersona = $estadoRegistro['idPersona'];

            foreach ($articulosCarrito as $artCarro) {
                foreach ($artCarro['colores'] as $colores) {
                    foreach ($colores['talles'] as $talles) {
                        if (is_null($artCarro['datosArticulo']['precioOferta'])) {
                            $montoTotal = $montoTotal + ($artCarro['datosArticulo']['precioMinorista'] * $talles['cantidad']);
                        } else {
                            $montoTotal = $montoTotal + ($artCarro['datosArticulo']['precioOferta'] * $talles['cantidad']);
                        }
                    }
                }
            }
        }

        if (!$localhost) {

            if (isset($mp)) {
                $preference = array(
                    "items" => array(
                        array(
                            "title" => "Clan Issime",
                            "quantity" => 1,
                            "currency_id" => "ARS",
                            "unit_price" => $montoTotal
                        )
                    )
                );

                $preferenceResult = $mp->create_preference($preference);

                $url_mercado_pago = $preferenceResult['response']['init_point'];
                //$url_mercado_pago = $preferenceResult['response']['sandbox_init_point'];
            }

            $linkCompra = $url_mercado_pago;
        } else {
            $linkCompra = "maxi";
        }

        $estadoRegistro = realizarRegistroPedido($idPersona, $linkCompra, $iderCarro, $montoTotal);
    } else {
        $estadoRegistro = -1;
    }

    if ($estadoRegistro != -1) {
        $texto = "El pedido se registró correctamente.";

        if (!$localhost) {
            require_once '../php/admin/mails/pedidoRealizado.php';
        }

        unset($_SESSION['iderCarro']);
    } else {
        $texto = "Hubo un error al realizar el pedido. Vuelva a intentarlo en unos minutos.";
    }
} else {
    $estadoRegistro = -1;
    $texto = $infoValidate['texto'];
}

/*
 * Carga en $data lo necesario para mostrar en el javascript.
 */

$data = array(
    'estado' => $estadoRegistro,
    'texto' => $texto
);

echo json_encode($data);
?>
