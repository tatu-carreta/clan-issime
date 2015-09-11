<?php

function obtenerProvincias() {
    $conect = conectar();

    $sql = "SELECT * FROM provincia ORDER BY nombreProvincia";
    $stmt = mysqli_query($conect, $sql);

    $provincias = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($provincias, $result);
    }

    return $provincias;
}

function obtenerLocalidadesPorIdProvincia($idProv) {
    $conect = conectar();

    $sql = "SELECT * FROM ciudad WHERE idProvincia = " . $idProv . " ORDER BY nombreCiudad";
    $stmt = mysqli_query($conect, $sql);

    $ciudades = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($ciudades, $result);
    }

    return $ciudades;
}

function obtenerLocalidades() {
    $conect = conectar();

    $sql = "SELECT * FROM ciudad ORDER BY nombreCiudad";
    $stmt = mysqli_query($conect, $sql);

    $ciudades = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($ciudades, $result);
    }

    return $ciudades;
}

function obtenerCiudadPorId($ciudad)
{
    $conect = conectar();

    $sql = "SELECT c.nombreCiudad, p.nombreProvincia 
            FROM ciudad as c 
            INNER JOIN provincia as p ON (p.idProvincia = c.idProvincia) 
            WHERE c.idCiudad = ?";
    $stmt = mysqli_prepare($conect, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $ciudad);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $nombreCiudad, $nombreProvincia);

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $row = mysqli_stmt_fetch($stmt);

        $result = array(
            'nombreCiudad' => $nombreCiudad,
            'nombreProvincia' => $nombreProvincia
        );
    } else {
        $result = array(
            'nombreCiudad' => false,
            'nombreProvincia' => false
        );
    }

    return $result;
}

function obtenerCondicionIva() {
    $conect = conectar();

    $sql = "SELECT * FROM condicion_iva ORDER BY nombreCondicionIva";
    $stmt = mysqli_query($conect, $sql);

    $condiciones = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($condiciones, $result);
    }

    return $condiciones;
}

function obtenerMayoristas() {
    $conect = conectar();

    $sql = "SELECT * 
            FROM mayorista as may
            INNER JOIN persona as p ON (p.idPersona = may.idPersona)
            INNER JOIN condicion_iva as ci ON (ci.idCondicionIva = may.idCondicionIva)
            INNER JOIN ciudad as c ON (c.idCiudad = p.idCiudad)
            INNER JOIN provincia as prov ON (prov.idProvincia = c.idProvincia)
            WHERE may.estado = 'A'
            AND p.estado = 'A'";
    $stmt = mysqli_query($conect, $sql);

    $mayoristas = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($mayoristas, $result);
    }

    return $mayoristas;
}

function obtenerClientes() {
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
            AND p.email <> '-1'
            ORDER BY p.apellidoPersona, p.nombrePersona";
    $stmt = mysqli_query($conect, $sql);

    $mayoristas = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($mayoristas, $result);
    }

    return $mayoristas;
}

function obtenerUltimoCliente()
{
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
            AND p.email <> '-1'
            ORDER BY p.idPersona DESC
            LIMIT 0,1";
    $stmt = mysqli_query($conect, $sql);

    $mayoristas = array();

    while ($result = mysqli_fetch_assoc($stmt)) {
        array_push($mayoristas, $result);
    }

    return $mayoristas;
}

?>
