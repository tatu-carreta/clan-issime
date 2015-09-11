<?php

/*
 * *****************************************************************************
 * *************** ALTA DE IP MALICIOSA *******************************************
 */

function realizarMonitoreoMalicioso($iderUser) {
    $conect = conectar();
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    try {

        /* Autocommit false para la transaccion */
        mysqli_autocommit($conect, FALSE);
        $estadoConsulta = TRUE;

        /* Inserción de Minorista */
        $sql = "INSERT INTO maliciosa_ip (maliciosa_address, archivo, iderUser, fechaIntento)
                VALUES(?,?,?,'".  date("Y-m-d H:i:s")."')";
        $stmt = mysqli_prepare($conect, $sql);
        $uri = urldecode($_SERVER['REQUEST_URI']);
        $address = $_SERVER['REMOTE_ADDR'];
        mysqli_stmt_bind_param($stmt, 'sss', $address, $uri, $iderUser);
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
