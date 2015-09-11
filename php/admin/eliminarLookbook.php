<?php
	require ("../config.php"); 
	require ("../funciones.php"); 

	
	$sql = eliminarLookbook($_POST['id']);

	echo $sql;

?>