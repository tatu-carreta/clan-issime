<?php
	require ("../../config.php"); 
	require ("../../funciones.php"); 
	$idColor = $_POST['idColor'];

	$control  = eliminarColor($idColor);
	
	echo $control;
?>