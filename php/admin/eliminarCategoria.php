<?php
	require ("../config.php"); 
	require ("../funciones.php"); 

	
	$sql = eliminarCategoria($_POST['idCategoria']);

	echo $sql;

?>