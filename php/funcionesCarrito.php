<?php

function crearCarrito($iderUser, $user_address) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");

    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Carrito */

        if ($iderUser != "") {
            $sql = "INSERT INTO carrito (iderUser, user_address, fechaCarrito, estado)
                    VALUES(?,?,'" . date("Y-m-d H:i:s") . "','A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $iderUser, $user_address);
        } else {
            $sql = "INSERT INTO carrito (user_address, fechaCarrito, estado)
                    VALUES(?,'" . date("Y-m-d H:i:s") . "','A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 's', $user_address);
        }
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);

        /* Obtener idCarrito */
        $idCarrito = mysqli_insert_id($conect);

        /* Inserción de iderCarro */
        $sql = "UPDATE carrito SET iderCarro = ? WHERE idCarrito = ?";
        $stmt = mysqli_prepare($conect, $sql);
        $idCarritoHash = hashData($idCarrito);
        mysqli_stmt_bind_param($stmt, 'si', $idCarritoHash, $idCarrito);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);


        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            $result = array(
                'estado' => true,
                'iderCarro' => $idCarritoHash
            );
        } else {
            mysqli_rollback($conect);
            desconectar($conect);
            $result = array(
                'estado' => false,
                'iderCarro' => $idCarritoHash
            );
        }
        return $result;
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        $result = array(
            'estado' => false
        );
        return $result;
    }
}

function realizarAgregacionCarrito($idTalle, $cantidad, $iderCarro) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtener idPersona por Usuario */
        $sql = "SELECT idCarrito FROM carrito WHERE iderCarro = ?";
        $stmt2 = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt2, 's', $iderCarro);
        if (!mysqli_stmt_execute($stmt2)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt2, $idCarrito);
        mysqli_stmt_store_result($stmt2);
        $row = mysqli_stmt_fetch($stmt2);

        mysqli_stmt_close($stmt2);

        /* Obtener idPersona por Usuario */
        $sql = "SELECT cantidad 
                FROM carrito_articulo 
                WHERE idTalle = ?
                AND idCarrito = ?
                AND estado = 'A'";
        $stmt2 = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt2, 'ii', $idTalle, $idCarrito);
        if (!mysqli_stmt_execute($stmt2)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt2, $cant);
        mysqli_stmt_store_result($stmt2);

        if (mysqli_stmt_num_rows($stmt2) > 0) {
            $row = mysqli_stmt_fetch($stmt2);

            $cant = $cant + $cantidad;

            /* Inserción de Minorista */
            $sql = "UPDATE carrito_articulo SET cantidad = ? WHERE idCarrito = ? AND idTalle = ?";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'iii', $cant, $idCarrito, $idTalle);

            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);
        } else {
            /* Inserción de Minorista */
            $sql = "INSERT INTO carrito_articulo (idCarrito, idTalle, cantidad, estado) VALUES(?, ?, ?, 'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'iii', $idCarrito, $idTalle, $cantidad);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);
        }

        /* Obtener idPersona por Usuario */
        $sql5 = "SELECT a.nombreArticulo, a.precioMayorista, a.precioMinorista, i.nombreImagen, ca.cantidad
                FROM carrito_articulo as ca
                INNER JOIN carrito ON (carrito.idCarrito = ca.idCarrito)
                INNER JOIN talle as t ON (t.idTalle = ca.idTalle)
                INNER JOIN color as c ON (c.idColor = t.idColor)
                INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                INNER JOIN imagen as i ON (i.idColor = t.idColor)
                WHERE carrito.iderCarro = ?
                AND ca.estado = 'A'
                AND i.tipo = 'C'
                AND ca.idTalle = ?
                GROUP BY a.idArticulo
                ORDER BY a.idArticulo, c.idColor, t.idTalle";
        $stmt5 = mysqli_prepare($conect, $sql5);
        mysqli_stmt_bind_param($stmt5, 'si', $iderCarro, $idTalle);
        if (!mysqli_stmt_execute($stmt5)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt5, $nombreArt, $precioMayorista, $precioMinorista, $nombreImagen, $talleCant);
        mysqli_stmt_store_result($stmt5);
        $row = mysqli_stmt_fetch($stmt5);

        mysqli_stmt_close($stmt5);

        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            $resul = array(
                'estado' => 1,
                'nombreArticulo' => $nombreArt,
                'precioMay' => $precioMayorista,
                'precioMin' => $precioMinorista,
                'nombreImagen' => $nombreImagen,
                'cantidad' => $talleCant
            );
        } else {
            mysqli_rollback($conect);
            desconectar($conect);
            $resul = array(
                'estado' => -1,
                'nombreArticulo' => null,
                'precioMay' => null,
                'precioMin' => null,
                'nombreImagen' => null,
                'cantidad' => null
            );
        }
        return $resul;
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        $resul = array(
            'estado' => -1,
            'nombreArticulo' => null,
            'precioMay' => null,
            'precioMin' => null,
            'nombreImagen' => null,
            'cantidad' => null
        );
        return $resul;
    }
}

function realizarBajaCarrito($idTalle, $iderCarro) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {
        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtener idPersona por Usuario */
        $sql = "SELECT idCarrito FROM carrito WHERE iderCarro = ?";
        $stmt2 = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt2, 's', $iderCarro);
        if (!mysqli_stmt_execute($stmt2)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt2, $idCarrito);
        mysqli_stmt_store_result($stmt2);
        $row = mysqli_stmt_fetch($stmt2);

        mysqli_stmt_close($stmt2);

        /* Inserción de Minorista */
        $sql = "UPDATE carrito_articulo SET estado = 'B' WHERE idCarrito = ? AND idTalle = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $idCarrito, $idTalle);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);
        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            return 1;
        } else {
            mysqli_rollback($conect);
            desconectar($conect);
            return -1;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        return -1;
    }
}

function realizarModificacionCarrito($idTalle, $cantidad, $iderCarro) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {
        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtener idPersona por Usuario */
        $sql = "SELECT idCarrito FROM carrito WHERE iderCarro = ?";
        $stmt2 = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt2, 's', $iderCarro);
        if (!mysqli_stmt_execute($stmt2)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt2, $idCarrito);
        mysqli_stmt_store_result($stmt2);
        $row = mysqli_stmt_fetch($stmt2);

        mysqli_stmt_close($stmt2);

        /* Inserción de Minorista */
        $sql = "UPDATE carrito_articulo SET cantidad = ? WHERE idCarrito = ? AND idTalle = ? AND estado = 'A'";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'iii', $cantidad, $idCarrito, $idTalle);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);
        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            return 1;
        } else {
            mysqli_rollback($conect);
            desconectar($conect);
            return -1;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        return -1;
    }
}

function obtenerUltimoArticuloCarrito($iderCarro){
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {
        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtener idPersona por Usuario */
        $sql = "SELECT cat.nombreCategoriaUrl
                FROM carrito_articulo as ca
                INNER JOIN carrito ON (carrito.idCarrito = ca.idCarrito)
                INNER JOIN talle as t ON (t.idTalle = ca.idTalle)
                INNER JOIN color as c ON (c.idColor = t.idColor)
                INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                INNER JOIN categoria as cat ON (cat.idCategoria = a.idCategoria)
                INNER JOIN imagen as i ON (i.idColor = t.idColor)
                WHERE carrito.iderCarro = ?
                AND ca.estado = 'A'
                AND i.tipo = 'C'
                ORDER BY ca.idCarritoArticulo DESC
                LIMIT 0,1";
        $stmt2 = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt2, 's', $iderCarro);
        if (!mysqli_stmt_execute($stmt2)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt2, $nombreCategoriaUrl);
        mysqli_stmt_store_result($stmt2);
        $row = mysqli_stmt_fetch($stmt2);

        mysqli_stmt_close($stmt2);

        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            return $nombreCategoriaUrl;
        } else {
            mysqli_rollback($conect);
            desconectar($conect);
            return -1;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        return -1;
    }
}

function obtenerArticulosPorCarrito($iderCarro) {

    $conect = conectar();


    $articulos = array();

    $sql = "SELECT *
            FROM carrito_articulo as ca
            INNER JOIN carrito ON (carrito.idCarrito = ca.idCarrito)
            INNER JOIN talle as t ON (t.idTalle = ca.idTalle)
            INNER JOIN color as c ON (c.idColor = t.idColor)
            INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
            INNER JOIN categoria as cat ON (cat.idCategoria = a.idCategoria)
            INNER JOIN imagen as i ON (i.idColor = t.idColor)
            WHERE carrito.iderCarro = '" . $iderCarro . "'
            AND ca.estado = 'A'
            AND i.tipo = 'C'
            GROUP BY a.idArticulo
            ORDER BY ca.idCarritoArticulo,a.idArticulo, c.idColor, t.idTalle";

    $stmt2 = mysqli_query($conect, $sql);


    while ($result2 = mysqli_fetch_assoc($stmt2)) {

//MIENTRAS SEA EL MISMO ARTICULO

        $artis = array(
            'datosArticulo' => $result2,
            'colores' => array()
        );

        $sql = "SELECT *
                FROM carrito_articulo as ca
                INNER JOIN carrito ON (carrito.idCarrito = ca.idCarrito)
                INNER JOIN talle as t ON (t.idTalle = ca.idTalle)
                INNER JOIN color as c ON (c.idColor = t.idColor)
                INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                INNER JOIN imagen as i ON (i.idColor = t.idColor)
                WHERE carrito.idCarrito = " . $result2['idCarrito'] . "
                AND c.idArticulo = " . $result2['idArticulo'] . "
                AND ca.estado = 'A'
                AND i.tipo = 'C'
                GROUP BY c.idColor";

        $stmt3 = mysqli_query($conect, $sql);



        while ($result3 = mysqli_fetch_assoc($stmt3)) {

//MIENTRAS SEA EL MISMO COLOR

            $colores = array(
                'datosColor' => $result3,
                'talles' => array()
            );

            $sql = "SELECT *
                    FROM carrito_articulo as ca
                    INNER JOIN carrito ON (carrito.idCarrito = ca.idCarrito)
                    INNER JOIN talle as t ON (t.idTalle = ca.idTalle)
                    INNER JOIN color as c ON (c.idColor = t.idColor)
                    INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                    INNER JOIN imagen as i ON (i.idColor = t.idColor)
                    WHERE carrito.idCarrito = " . $result3['idCarrito'] . "
                    AND c.idArticulo = " . $result3['idArticulo'] . "
                    AND t.idColor = " . $result3['idColor'] . "
                    AND ca.estado = 'A'
                    GROUP BY t.idTalle";

            $stmt4 = mysqli_query($conect, $sql);

            while ($result4 = mysqli_fetch_assoc($stmt4)) {
                array_push($colores['talles'], $result4);
            }


            array_push($artis['colores'], $colores);
        }

        array_push($articulos, $artis);
    }


    return $articulos;
}

?>
