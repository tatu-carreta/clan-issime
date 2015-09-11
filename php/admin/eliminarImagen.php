<?php
	require ("../config.php"); 
	require ("../funciones.php"); 
	$idImagen = $_POST['idImagen'];

	$control  = eliminarImagen($idImagen);
	
	echo $control;
?>