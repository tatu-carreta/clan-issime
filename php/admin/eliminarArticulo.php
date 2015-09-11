<?php
	require ("../config.php"); 
	require ("../funciones.php"); 

	
	$sql = eliminarArticulo($_POST['idArticulo']);

	echo $sql;

?>