<?php
	require ("../config.php"); 
	require ("../funciones.php"); 

	if($_POST['tipoPrecio'] == "mayorista")
	{
		$sql = cambiarPrecioArticulo($_POST['idArticulo'], $_POST['nuevoPrecio'], "precioMayorista");
	}
	elseif($_POST['tipoPrecio'] == "minorista")
	{
		$sql = cambiarPrecioArticulo($_POST['idArticulo'], $_POST['nuevoPrecio'], "precioMinorista");	
	}
	
	echo $sql;

?>