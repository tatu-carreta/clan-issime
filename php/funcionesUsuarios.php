<?php

/*
 * Busqueda para el login
 */

function obtenerUsuarioPorNombreClave($nombre, $clave) {
    $conect = conectar();
    $sql = "SELECT u.idUsuario, u.idPerfil, u.iderUser, p.apellidoPersona, p.nombrePersona 
            FROM usuario as u
            INNER JOIN persona as p ON (p.idPersona = u.idPersona)
            WHERE u.estado = 'A'
            AND p.estado = 'A'
            AND u.nombreUsuario = ? 
            AND u.claveUsuario = ?";
    $stmt = mysqli_prepare($conect, $sql);
    $nombre = hashData($nombre);
    $clave = hashData($clave);
    mysqli_stmt_bind_param($stmt, 'ss', $nombre, $clave);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $idUsuario, $idPerfil, $iderUser, $ape, $nom);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $row = mysqli_stmt_fetch($stmt);

        if (is_null($ape)) {
            $nombreApellido = $nom;
        } else {
            $nombreApellido = $ape . " " . $nom;
        }

        $result = array(
            'estado' => true,
            'perfil' => $idPerfil,
            'nombreApellido' => $nombreApellido,
            'idUsuario' => $idUsuario,
            'iderUser' => $iderUser
        );
    } else {
        $result = array(
            'estado' => false
        );
    }

    return $result;
}

/*
 * Informacion del usuario a partir de su iderUser
 */

function obtenerInfoUsuarioPorIderUser($iderUser) {
    $conect = conectar();
    $sql = "SELECT u.idUsuario, p.idPersona, p.nombrePersona, p.apellidoPersona, p.fechaNacimiento, p.email, p.calle, p.piso, 
            p.numero, p.departamento, p.codigoPostal, p.telefono, ciu.idCiudad, ciu.idProvincia, may.cuit, may.razonSocial,
            may.idCondicionIva, may.nombreFantasia, may.calleFiscal, may.numeroFiscal, may.pisoFiscal, may.departamentoFiscal,
            may.idCiudadFiscal, may.codPostalFiscal, may.telefonoFiscal, ciuFiscal.idProvincia
            FROM usuario as u
            INNER JOIN persona as p ON (p.idPersona = u.idPersona)
            LEFT JOIN ciudad as ciu ON (ciu.idCiudad = p.idCiudad)
            LEFT JOIN mayorista as may ON (may.idPersona = p.idPersona)
            LEFT JOIN minorista as min ON (min.idPersona = p.idPersona)
            LEFT JOIN ciudad as ciuFiscal ON (ciuFiscal.idCiudad = may.idCiudadFiscal)
            WHERE u.estado = 'A'
            AND p.estado = 'A'
            AND u.iderUser = ?";
    $stmt = mysqli_prepare($conect, $sql);
    mysqli_stmt_bind_param($stmt, 's', $iderUser);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $idUsuario, $idPersona, $nombre, $apellido, $fecha, $email, $calle, $piso, $numero, $depto, $cod, $tel, $idCiudad, $idProvincia, $cuit, $razonSocial, $idIva, $fantasia, $calleFiscal, $numeroFiscal, $pisoFiscal, $deptoFiscal, $idCiudadFiscal, $codPostalFiscal, $telFiscal, $idProvFiscal);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $row = mysqli_stmt_fetch($stmt);
        $datos = array(
            "iderUser" => $iderUser,
            "idUsuario" => $idUsuario,
            "idPersona" => $idPersona,
            "nombrePersona" => $nombre,
            "apellidoPersona" => $apellido,
            "fechaNacimiento" => $fecha,
            "email" => $email,
            "calle" => $calle,
            "piso" => $piso,
            "numero" => $numero,
            "departamento" => $depto,
            "codigoPostal" => $cod,
            "telefono" => $tel,
            "idCiudad" => $idCiudad,
            "idProvincia" => $idProvincia,
            "cuit" => $cuit,
            "razonSocial" => $razonSocial,
            "idCondicionIva" => $idIva,
            "nombreFantasia" => $fantasia,
            "calleFiscal" => $calleFiscal,
            "numeroFiscal" => $numeroFiscal,
            "pisoFiscal" => $pisoFiscal,
            "departamentoFiscal" => $deptoFiscal,
            "idCiudadFiscal" => $idCiudadFiscal,
            "codPostalFiscal" => $codPostalFiscal,
            "telefonoFiscal" => $telFiscal,
            "idProvinciaFiscal" => $idProvFiscal
        );

        return $datos;
    }
    return false;
}

/*
 * Monitoreo del usuario cuando se loguea
 */

function realizarInformeUsuarioLogueado($idUsuario, $ipUsuario) {
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $ok = false;
    $conect = conectar();
    $sql = "UPDATE usuario 
            SET cantidadIngresos = cantidadIngresos+1 
            WHERE idUsuario = ?";
    $stmt = mysqli_prepare($conect, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idUsuario);
    mysqli_stmt_execute($stmt);

    $sql2 = "INSERT INTO usuario_address (idUsuario, address, fecha) VALUES(?, ?, '" . date("Y-m-d H:i:s") . "')";
    $stmt2 = mysqli_prepare($conect, $sql2);
    mysqli_stmt_bind_param($stmt2, 'is', $idUsuario, $ipUsuario);
    mysqli_stmt_execute($stmt2);
    $ok = true;

    return $ok;
}

/*
 * *****************************************************************************
 * *************** ALTA DE CLIENTE *******************************************
 */

function realizarAltaCliente($nombre, $apellido, $email, $telefono) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Obtener si existe ya ese usuario y contraseña para no dejarlo */

        $yaHay = obtenerUsuarioPorNombreClave($email, $email);

        if (!$yaHay['estado']) {

            /* Autocommit false para la transaccion */
            mysqli_autocommit($conect, FALSE);
            $estadoConsulta = TRUE;

            /* Inserción de Minorista */
            $sql = "INSERT INTO persona (nombrePersona, apellidoPersona, email, telefono, estado)
                VALUES(?,?,?,?,'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $apellido, $email, $telefono);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Obtener idPersona */
            $idPersona = mysqli_insert_id($conect);

            /* Inserción de Minorista */
            $sql = "INSERT INTO minorista (idPersona, estado) VALUES(?, 'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $idPersona);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Inserción de Usuario */
            $sql = "INSERT INTO usuario (nombreUsuario, claveUsuario, idPersona, idPerfil, cantidadIngresos, estado, fechaCreacion) VALUES(?, ?, ?, '3', '1', 'A', '" . date("Y-m-d H:i:s") . "')";
            $stmt = mysqli_prepare($conect, $sql);
            $email = hashData($email);

            mysqli_stmt_bind_param($stmt, 'ssi', $email, $email, $idPersona);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Obtener idUsuario */
            $idUsuario = mysqli_insert_id($conect);

            /* Inserción de iderUser */
            $sql = "UPDATE usuario SET iderUser = ? WHERE idUsuario = ?";
            $stmt = mysqli_prepare($conect, $sql);
            $idUsuarioHash = hashData($idUsuario);
            mysqli_stmt_bind_param($stmt, 'si', $idUsuarioHash, $idUsuario);
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
        } else {
            desconectar($conect);
            return -99;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        return -1;
    }
}

/*
 * *****************************************************************************
 * *************** BAJA DE CLIENTE *******************************************
 */

function realizarBajaCliente($iderUser) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Obtener si existe ya ese usuario y contraseña para no dejarlo */

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Minorista */
        $sql = "UPDATE usuario SET estado = 'B', fechaBaja = '" . date("Y-m-d H:i:s") . "'
                    WHERE iderUser = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 's', $iderUser);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);


        /* Obtener idPersona por Usuario */
        $sql = "SELECT idPersona FROM usuario WHERE iderUser = ?";
        $stmt2 = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt2, 's', $iderUser);
        if (!mysqli_stmt_execute($stmt2)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_bind_result($stmt2, $idPersona);
        mysqli_stmt_store_result($stmt2);
        $row = mysqli_stmt_fetch($stmt2);

        mysqli_stmt_close($stmt2);

        /* Inserción de Minorista */
        $sql = "UPDATE persona SET estado = 'B' WHERE idPersona = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $idPersona);
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

/*
 * *****************************************************************************
 * *************** BÚSQUEDA DE CLIENTE *******************************************
 */

function obtenerClientesPorCriterio($datoBusqueda) {
    $conect = conectar();

    $sql = "SELECT * 
            FROM persona as p
            LEFT JOIN mayorista as may ON (may.idPersona = p.idPersona)
            LEFT JOIN minorista as min ON (min.idPersona = p.idPersona)
            LEFT JOIN condicion_iva as ci ON (ci.idCondicionIva = may.idCondicionIva)
            LEFT JOIN ciudad as c ON (c.idCiudad = p.idCiudad)
            LEFT JOIN provincia as prov ON (prov.idProvincia = c.idProvincia)
            LEFT JOIN usuario as us ON (us.idPersona = p.idPersona)
            WHERE p.estado = 'A'
            AND ((p.nombrePersona LIKE '%" . $datoBusqueda . "%') 
                 OR (p.apellidoPersona LIKE '%" . $datoBusqueda . "%')
                 OR (p.email LIKE '%" . $datoBusqueda . "%')
                 OR (p.telefono LIKE '%" . $datoBusqueda . "%'))
            AND p.email <> '-1'
            ORDER BY p.apellidoPersona, p.nombrePersona";
    $stmt = mysqli_query($conect, $sql);

    $clientes = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($clientes, $result);
    }

    return $clientes;
}

/*
 * *****************************************************************************
 * *************** ALTA DE MINORISTA *******************************************
 */

function realizarAltaMinorista($nombre, $apellido, $email, $clave, $fecNac, $address) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Obtener si existe ya ese usuario y contraseña para no dejarlo */

        $yaHay = obtenerUsuarioPorNombreClave($email, $clave);

        if (!$yaHay['estado']) {

            /* Autocommit false para la transaccion */
            mysqli_autocommit($conect, FALSE);
            $estadoConsulta = TRUE;

            /* Inserción de Minorista */
            $sql = "INSERT INTO persona (nombrePersona, apellidoPersona, fechaNacimiento, email, estado)
                VALUES(?,?,?,?,'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $apellido, $fecNac, $email);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Obtener idPersona */
            $idPersona = mysqli_insert_id($conect);

            /* Inserción de Minorista */
            $sql = "INSERT INTO minorista (idPersona, estado) VALUES(?, 'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $idPersona);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Inserción de Usuario */
            $sql = "INSERT INTO usuario (nombreUsuario, claveUsuario, idPersona, idPerfil, cantidadIngresos, estado, fechaCreacion) VALUES(?, ?, ?, '3', '1', 'A', '" . date("Y-m-d H:i:s") . "')";
            $stmt = mysqli_prepare($conect, $sql);
            $email = hashData($email);
            $clave = hashData($clave);
            mysqli_stmt_bind_param($stmt, 'ssi', $email, $clave, $idPersona);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Obtener idUsuario */
            $idUsuario = mysqli_insert_id($conect);

            /* Inserción de iderUser */
            $sql = "UPDATE usuario SET iderUser = ? WHERE idUsuario = ?";
            $stmt = mysqli_prepare($conect, $sql);

            $idUsuarioHash = hashData($idUsuario);

            mysqli_stmt_bind_param($stmt, 'si', $idUsuarioHash, $idUsuario);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);


            /* Inserción de Usuario */
            $sql = "INSERT INTO usuario_address (idUsuario, address, fecha) VALUES(?, ?, '" . date("Y-m-d H:i:s") . "')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'is', $idUsuario, $address);
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
        } else {
            desconectar($conect);
            return -99;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        return -1;
    }
}

/*
 * *****************************************************************************
 * *************** MODIFICACION DE MINORISTA *******************************************
 */

function realizarModificacionMinorista($nombre, $apellido, $fecNac, $calle, $numero, $piso, $departamento, $localidad, $codPostal, $telefono, $iderUser, $clave, $confirmClave) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtencion de datos de la persona por iderUser */
        $infoPersona = obtenerInfoUsuarioPorIderUser($iderUser);

        if ($localidad == "") {
            $localidad = NULL;
        }
        if ($calle == "") {
            $calle = NULL;
        }
        if ($numero == "") {
            $numero = NULL;
        }
        if ($piso == "") {
            $piso = NULL;
        }
        if ($departamento == "") {
            $departamento = NULL;
        }
        if ($codPostal == "") {
            $codPostal = NULL;
        }
        if ($telefono == "") {
            $telefono = NULL;
        }

        /* Inserción de Minorista */
        $sql = "UPDATE persona SET nombrePersona = ?, apellidoPersona = ?, fechaNacimiento = ?, idCiudad = ?, calle = ?, numero = ?, piso = ?, 
                    departamento = ?, codigoPostal = ?, telefono = ? WHERE idPersona = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'sssisissisi', $nombre, $apellido, $fecNac, $localidad, $calle, $numero, $piso, $departamento, $codPostal, $telefono, $infoPersona['idPersona']);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
            die(print_r(mysqli_stmt_errno($stmt)) . " - " . (mysqli_stmt_error($stmt)));
        }
        mysqli_stmt_close($stmt);

        if (($clave != "") && ($clave == $confirmClave)) {
            /* Inserción de iderUser */
            $sql = "UPDATE usuario SET claveUsuario = ? WHERE iderUser = ?";
            $stmt = mysqli_prepare($conect, $sql);
            $clave = hashData($clave);
            mysqli_stmt_bind_param($stmt, 'si', $clave, $iderUser);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);
        }
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

/*
 * *****************************************************************************
 * *************** ALTA DE MAYORISTA *******************************************
 */

function realizarAltaMayorista($nombre, $apellido, $email, $clave, $fecNac, $calle, $numero, $piso, $departamento, $localidad, $codPostal, $telefono, $cuit, $condIva, $razonSocial, $nombreFantasia, $address, $calleFiscal, $numeroFiscal, $pisoFiscal, $departamentoFiscal, $localidadFiscal, $codPostalFiscal, $telefonoFiscal) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        $yaHay = obtenerUsuarioPorNombreClave($email, $clave);

        if (!$yaHay['estado']) {
            /* Autocommit false para la transaccion */
            mysqli_autocommit($conect, FALSE);
            $estadoConsulta = TRUE;

            /* Inserción de Minorista */
            $sql = "INSERT INTO persona (nombrePersona, apellidoPersona, fechaNacimiento, email, idCiudad, calle, numero, piso, departamento, codigoPostal, telefono, estado)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'ssssisissis', $nombre, $apellido, $fecNac, $email, $localidad, $calle, $numero, $piso, $departamento, $codPostal, $telefono);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Obtener idPersona */
            $idPersona = mysqli_insert_id($conect);

            /* Inserción de Mayorista */
            $sql = "INSERT INTO mayorista (idPersona, cuit, razonSocial, idCondicionIva, nombreFantasia, calleFiscal, numeroFiscal, pisoFiscal, 
                    departamentoFiscal, idCiudadFiscal, codPostalFiscal, telefonoFiscal, estado) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, 'A')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'iisississiis', $idPersona, $cuit, $razonSocial, $condIva, $nombreFantasia, $calleFiscal, $numeroFiscal, $pisoFiscal, $departamentoFiscal, $localidadFiscal, $codPostalFiscal, $telefonoFiscal);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Inserción de Usuario */
            $sql = "INSERT INTO usuario (nombreUsuario, claveUsuario, idPersona, idPerfil, cantidadIngresos, estado, fechaCreacion) VALUES(?, ?, ?, '2', '1', 'A', '" . date("Y-m-d H:i:s") . "')";
            $stmt = mysqli_prepare($conect, $sql);
            $email = hashData($email);
            $clave = hashData($clave);
            mysqli_stmt_bind_param($stmt, 'ssi', $email, $clave, $idPersona);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Obtener idUsuario */
            $idUsuario = mysqli_insert_id($conect);

            /* Inserción de iderUser */
            $sql = "UPDATE usuario SET iderUser = ? WHERE idUsuario = ?";
            $stmt = mysqli_prepare($conect, $sql);
            $idUsuarioHash = hashData($idUsuario);
            mysqli_stmt_bind_param($stmt, 'si', $idUsuarioHash, $idUsuario);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);

            /* Inserción de Usuario */
            $sql = "INSERT INTO usuario_address (idUsuario, address, fecha) VALUES(?, ?, '" . date("Y-m-d H:i:s") . "')";
            $stmt = mysqli_prepare($conect, $sql);
            mysqli_stmt_bind_param($stmt, 'is', $idUsuario, $address);
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
        } else {
            desconectar($conect);
            return -99;
        }
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($conect);
        desconectar($conect);
        return -1;
    }
}

/*
 * *****************************************************************************
 * *************** MODIFICACION DE MAYORISTA *******************************************
 */

function realizarModificacionMayorista($nombre, $apellido, $fecNac, $calle, $numero, $piso, $departamento, $localidad, $codPostal, $telefono, $cuit, $condIva, $razonSocial, $nombreFantasia, $iderUser, $clave, $confirmClave, $calleFiscal, $numeroFiscal, $pisoFiscal, $departamentoFiscal, $localidadFiscal, $codPostalFiscal, $telefonoFiscal) {

    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtencion de datos de la persona por iderUser */
        $infoPersona = obtenerInfoUsuarioPorIderUser($iderUser);

        /* Inserción de Mayorista */
        $sql = "UPDATE persona SET nombrePersona = ?, apellidoPersona = ?, fechaNacimiento = ?, idCiudad = ?, calle = ?, numero = ?, piso = ?, 
                    departamento = ?, codigoPostal = ?, telefono = ? WHERE idPersona = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'sssisissisi', $nombre, $apellido, $fecNac, $localidad, $calle, $numero, $piso, $departamento, $codPostal, $telefono, $infoPersona['idPersona']);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);

        /* Obtener idPersona */
        $idPersona = mysqli_insert_id($conect);

        /* Inserción de Mayorista */
        $sql = "UPDATE mayorista SET cuit = ?, razonSocial = ?, idCondicionIva = ?, nombreFantasia = ?, calleFiscal = ?, numeroFiscal = ?,
                pisoFiscal = ?, departamentoFiscal = ?, idCiudadFiscal = ?, codPostalFiscal = ?, telefonoFiscal = ? WHERE idPersona = ?";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'isississiisi', $cuit, $razonSocial, $condIva, $nombreFantasia, $calleFiscal, $numeroFiscal, $pisoFiscal, $departamentoFiscal, $localidadFiscal, $codPostalFiscal, $telefonoFiscal, $infoPersona['idPersona']);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }
        mysqli_stmt_close($stmt);

        if (($clave != "") && ($clave == $confirmClave)) {
            /* Inserción de iderUser */
            $sql = "UPDATE usuario SET claveUsuario = ? WHERE iderUser = ?";
            $stmt = mysqli_prepare($conect, $sql);
            $clave = hashData($clave);
            mysqli_stmt_bind_param($stmt, 'si', $clave, $iderUser);
            if (!mysqli_stmt_execute($stmt)) {
                $estadoConsulta = FALSE;
            }
            mysqli_stmt_close($stmt);
        }

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

function realizarRegistroPreguntaSeguridad($pregunta, $respuesta, $iderUser) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtencion de datos de la persona por iderUser */
        $infoPersona = obtenerInfoUsuarioPorIderUser($iderUser);

        /* Inserción de Mayorista */
        $sql = "INSERT persona_pregunta (idPersona, idPregunta, respuesta)
                VALUES(?,?,?)";
        $stmt = mysqli_prepare($conect, $sql);
        mysqli_stmt_bind_param($stmt, 'iis', $infoPersona['idPersona'], $pregunta, $respuesta);
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

function yaRealizoPreguntaSeguridad($iderUser) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Obtencion de datos de la persona por iderUser */
        $infoPersona = obtenerInfoUsuarioPorIderUser($iderUser);

        /* Inserción de Mayorista */
        $sql = "SELECT *
                FROM persona_pregunta
                WHERE idPersona = ?";
        $stmt = mysqli_prepare($conect, $sql);

        if (!isset($infoPersona['idPersona'])) {
            $infoPersona['idPersona'] = -1;
        }

        mysqli_stmt_bind_param($stmt, 'i', $infoPersona['idPersona']);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
            $cantidad = -1;
        } else {
            mysqli_stmt_store_result($stmt);
            $cantidad = mysqli_stmt_num_rows($stmt);
        }


        mysqli_stmt_close($stmt);

        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            return $cantidad;
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

function realizarRegeneracionClave($email, $pregunta, $respuesta) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Mayorista */
        $sql = "SELECT u.idUsuario
                FROM persona as p
                INNER JOIN persona_pregunta as pp ON (pp.idPersona = p.idPersona)
                INNER JOIN usuario as u ON (u.idPersona = p.idPersona)
                WHERE p.email = ?
                AND p.estado = 'A'
                AND pp.idPregunta = ?
                AND pp.respuesta = ?
                AND u.estado = 'A'
                AND u.nombreUsuario = ?";
        $stmt = mysqli_prepare($conect, $sql);

        $nombreUsuario = hashData($email);

        mysqli_stmt_bind_param($stmt, 'siss', $email, $pregunta, $respuesta, $nombreUsuario);
        if (!mysqli_stmt_execute($stmt)) {
            $estadoConsulta = FALSE;
        }

        mysqli_stmt_bind_result($stmt, $idUsuario);
        mysqli_stmt_store_result($stmt);


        if (mysqli_stmt_num_rows($stmt) > 0) {
            $row = mysqli_stmt_fetch($stmt);

            /* Inserción de Mayorista */
            $sql2 = "UPDATE usuario SET claveUsuario = ? WHERE idUsuario = ?";
            $stmt2 = mysqli_prepare($conect, $sql2);
            $nuevaClaveUsuario = regenerarContrasena();
            $claveUsuario = hashData($nuevaClaveUsuario);

            mysqli_stmt_bind_param($stmt2, 'si', $claveUsuario, $idUsuario);

            if (!mysqli_stmt_execute($stmt2)) {
                $estadoConsulta = FALSE;
            }

            mysqli_stmt_close($stmt2);
        } else {
            $estadoConsulta = FALSE;
        }

        mysqli_stmt_close($stmt);

        /* Commit or rollback transaction */
        if ($estadoConsulta) {
            mysqli_commit($conect);
            desconectar($conect);
            return $nuevaClaveUsuario;
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
