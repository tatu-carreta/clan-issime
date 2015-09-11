<?php

function realizarAltaPersona($nombre, $apellido, $email, $telefono, $calle, $numero, $piso, $dpto, $codPostal, $ciudad, $fecNac) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");

    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Persona Pedido */

        $sql = "INSERT INTO persona (nombrePersona, apellidoPersona, fechaNacimiento, email, idCiudad, calle, numero, piso, departamento, codigoPostal, telefono, estado)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,'A')";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssisissis', $nombre, $apellido, $fecNac, $email, $ciudad, $calle, $numero, $piso, $dpto, $codPostal, $telefono);

        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);

        /* Obtener idCarrito */
        $idPersona = mysqli_insert_id($conect);

        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            $result = array(
                'estado' => true,
                'idPersona' => $idPersona
            );
        } else {
            mysqli_rollback($conect);
            desconectar($conect);
            $result = array(
                'estado' => false,
                'iderCarro' => $idPersona
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

function realizarModificacionPersona($nombre, $apellido, $telefono, $calle, $numero, $piso, $dpto, $codPostal, $ciudad, $fecNac, $idPersona) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");

    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Persona Pedido */

        $sql = "UPDATE persona SET nombrePersona = ?, apellidoPersona = ?, fechaNacimiento = ?, idCiudad = ?, calle = ?, numero = ?, piso = ?, departamento = ?, codigoPostal = ?, telefono = ? 
                WHERE idPersona = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'sssisissisi', $nombre, $apellido, $fecNac, $ciudad, $calle, $numero, $piso, $dpto, $codPostal, $telefono, $idPersona);

        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);

        /* Obtener idCarrito */
        $idPersona = mysqli_insert_id($conect);

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

function realizarRegistroPedido($idPersona, $linkCompra, $iderCarro, $montoTotal) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");

    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Pedido */

        $sql = "INSERT INTO pedido (idPersona, fechaPedido, montoTotal, linkCompra, estadoPedido)
                    VALUES(?,'" . date("Y-m-d H:i:s") . "','" . $montoTotal . "', ?,'I')";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $idPersona, $linkCompra);

        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);

        $idPedido = mysqli_insert_id($conect);

        $articulosPedidos = obtenerArticulosPorCarrito($iderCarro);



        foreach ($articulosPedidos as $arts) {
            foreach ($arts['colores'] as $colores) {
                foreach ($colores['talles'] as $talles) {
                    $sql = "INSERT INTO pedido_articulo (idPedido, idTalleColor, cantidad, precioUnidad)
                            VALUES(?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conect, $sql);

                    if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 2)) {
                        $precioUnidad = $arts['datosArticulo']['precioMayorista'];
                    } else {
                        if (is_null($arts['datosArticulo']['precioOferta'])) {
                            $precioUnidad = $arts['datosArticulo']['precioMinorista'];
                        } else {
                            $precioUnidad = $arts['datosArticulo']['precioOferta'];
                        }
                    }

                    mysqli_stmt_bind_param($stmt, 'iiis', $idPedido, $talles['idTalle'], $talles['cantidad'], $precioUnidad);

                    if (!mysqli_stmt_execute($stmt)) {
                        $estadoConsulta = FALSE;
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }


        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            return $idPedido;
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

function obtenerPedidos() {
    $conect = conectar();

    $sql = "SELECT *
            FROM pedido as p
            INNER JOIN persona as per ON (per.idPersona = p.idPersona)
            LEFT JOIN mayorista as may ON (may.idPersona = per.idPersona)
            LEFT JOIN minorista as mino ON (mino.idPersona = per.idPersona)
            LEFT JOIN ciudad as ci ON (ci.idCiudad = per.idCiudad)
            LEFT JOIN provincia as prov ON (prov.idProvincia = ci.idProvincia)
            WHERE p.estadoPedido <> 'B'
            ORDER BY p.fechaPedido DESC , p.idPedido DESC";
    $stmt = mysqli_query($conect, $sql);

    $articulos = array();

    while ($result = mysqli_fetch_assoc($stmt)) {

        //MIENTRAS SEA LA MISMA PERSONA

        $sql = "SELECT *
                FROM pedido as p
                INNER JOIN pedido_articulo as pa ON (pa.idPedido = p.idPedido)
                INNER JOIN talle as t ON (t.idTalle = pa.idTalleColor)
                INNER JOIN color as c ON (c.idColor = t.idColor)
                INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                INNER JOIN imagen as i ON (i.idColor = t.idColor)
                WHERE p.idPedido = " . $result['idPedido'] . "
                AND i.tipo = 'C'
                GROUP BY a.idArticulo
                ORDER BY a.idArticulo, c.idColor, t.idTalle";

        $stmt2 = mysqli_query($conect, $sql);

        $datos = array(
            'datosPersona' => $result,
            'arts' => array()
        );

        while ($result2 = mysqli_fetch_assoc($stmt2)) {

            //MIENTRAS SEA EL MISMO ARTICULO

            $artis = array(
                'datosArticulo' => $result2,
                'colores' => array()
            );

            $sql = "SELECT *
                    FROM pedido as p
                    INNER JOIN pedido_articulo as pa ON (pa.idPedido = p.idPedido)
                    INNER JOIN talle as t ON (t.idTalle = pa.idTalleColor)
                    INNER JOIN color as c ON (c.idColor = t.idColor)
                    INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                    INNER JOIN imagen as i ON (i.idColor = t.idColor)
                    WHERE p.idPedido = " . $result2['idPedido'] . "
                    AND c.idArticulo = " . $result2['idArticulo'] . "
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
                        FROM pedido as p
                        INNER JOIN pedido_articulo as pa ON (pa.idPedido = p.idPedido)
                        INNER JOIN talle as t ON (t.idTalle = pa.idTalleColor)
                        INNER JOIN color as c ON (c.idColor = t.idColor)
                        INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                        INNER JOIN imagen as i ON (i.idColor = t.idColor)
                        WHERE p.idPedido = " . $result3['idPedido'] . "
                        AND c.idArticulo = " . $result3['idArticulo'] . "
                        AND t.idColor = " . $result3['idColor'] . "
                        GROUP BY t.idTalle";

                $stmt4 = mysqli_query($conect, $sql);

                while ($result4 = mysqli_fetch_assoc($stmt4)) {
                    array_push($colores['talles'], $result4);
                }


                array_push($artis['colores'], $colores);
            }

            array_push($datos['arts'], $artis);
        }

        array_push($articulos, $datos);
    }

    return $articulos;
}

function obtenerPedidosPorFiltro($tipoPersona, $estadoPedido, $datoBusqueda) {
    $conect = conectar();

    if ($estadoPedido == -1) {
        $where = "WHERE p.estadoPedido <> 'B' ";
    } else {
        $where = "WHERE p.estadoPedido = '" . $estadoPedido . "' ";
    }

    switch ($tipoPersona) {
        case -1:
            $consulta = "LEFT JOIN mayorista as may ON (may.idPersona = per.idPersona)
                             LEFT JOIN minorista as mino ON (mino.idPersona = per.idPersona)";
            break;
        case 2:
            $consulta = "INNER JOIN mayorista as may ON (may.idPersona = per.idPersona)";
            break;
        case 3:
            $consulta = "INNER JOIN minorista as mino ON (mino.idPersona = per.idPersona)";
            break;
        default :
            $consulta = "LEFT JOIN mayorista as may ON (may.idPersona = per.idPersona)
                             LEFT JOIN minorista as mino ON (mino.idPersona = per.idPersona)";

            $where .= "AND may.idMayorista IS NULL
                          AND mino.idMinorista IS NULL ";
            break;
    }

    if ($datoBusqueda != "") {
        $where .= "AND ((per.nombrePersona LIKE '%" . $datoBusqueda . "%') 
                        OR (per.apellidoPersona LIKE '%" . $datoBusqueda . "%')
                        OR (per.email LIKE '%" . $datoBusqueda . "%'))";
    }


    $sql = "SELECT *
            FROM pedido as p
            INNER JOIN persona as per ON (per.idPersona = p.idPersona)
            LEFT JOIN ciudad as ci ON (ci.idCiudad = per.idCiudad)
            LEFT JOIN provincia as prov ON (prov.idProvincia = ci.idProvincia)
            " . $consulta . $where . "
            ORDER BY p.fechaPedido DESC , p.idPedido DESC";

    $stmt = mysqli_query($conect, $sql);

    $articulos = array();

    while ($result = mysqli_fetch_assoc($stmt)) {

        //MIENTRAS SEA LA MISMA PERSONA

        $sql = "SELECT *
                FROM pedido as p
                INNER JOIN pedido_articulo as pa ON (pa.idPedido = p.idPedido)
                INNER JOIN talle as t ON (t.idTalle = pa.idTalleColor)
                INNER JOIN color as c ON (c.idColor = t.idColor)
                INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                INNER JOIN imagen as i ON (i.idColor = t.idColor)
                WHERE p.idPedido = " . $result['idPedido'] . "
                AND i.tipo = 'C'
                GROUP BY a.idArticulo
                ORDER BY a.idArticulo, c.idColor, t.idTalle";

        $stmt2 = mysqli_query($conect, $sql);

        $datos = array(
            'datosPersona' => $result,
            'arts' => array()
        );

        while ($result2 = mysqli_fetch_assoc($stmt2)) {

            //MIENTRAS SEA EL MISMO ARTICULO

            $artis = array(
                'datosArticulo' => $result2,
                'colores' => array()
            );

            $sql = "SELECT *
                    FROM pedido as p
                    INNER JOIN pedido_articulo as pa ON (pa.idPedido = p.idPedido)
                    INNER JOIN talle as t ON (t.idTalle = pa.idTalleColor)
                    INNER JOIN color as c ON (c.idColor = t.idColor)
                    INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                    INNER JOIN imagen as i ON (i.idColor = t.idColor)
                    WHERE p.idPedido = " . $result2['idPedido'] . "
                    AND c.idArticulo = " . $result2['idArticulo'] . "
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
                        FROM pedido as p
                        INNER JOIN pedido_articulo as pa ON (pa.idPedido = p.idPedido)
                        INNER JOIN talle as t ON (t.idTalle = pa.idTalleColor)
                        INNER JOIN color as c ON (c.idColor = t.idColor)
                        INNER JOIN articulo as a ON (a.idArticulo = c.idArticulo)
                        INNER JOIN imagen as i ON (i.idColor = t.idColor)
                        WHERE p.idPedido = " . $result3['idPedido'] . "
                        AND c.idArticulo = " . $result3['idArticulo'] . "
                        AND t.idColor = " . $result3['idColor'] . "
                        GROUP BY t.idTalle";

                $stmt4 = mysqli_query($conect, $sql);

                while ($result4 = mysqli_fetch_assoc($stmt4)) {
                    array_push($colores['talles'], $result4);
                }


                array_push($artis['colores'], $colores);
            }

            array_push($datos['arts'], $artis);
        }

        array_push($articulos, $datos);
    }

    return $articulos;
}

function realizarBajaPedido($idPedido) {
    $conect = conectar();
    try {


        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Minorista */
        $sql = "UPDATE pedido SET estadoPedido = 'B' WHERE idPedido = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 's', $idPedido);
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

function realizarModificacionPedido($idPedido, $estadoNuevo) {
    $conect = conectar();
    try {


        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Minorista */
        $sql = "UPDATE pedido SET estadoPedido = ? WHERE idPedido = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $estadoNuevo, $idPedido);
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

?>
