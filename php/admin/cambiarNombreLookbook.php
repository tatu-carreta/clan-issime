<?php
	require ("../config.php"); 
	require ("../funciones.php");

	if(isset($_POST['text']) and isset($_POST['id']))
	{
		$sql = cambiarNombreLookbook($_POST['text'], $_POST['id']);
	}
	else
	{
		$sql = "Hubo un error en el traspaso de datos";
	}

	echo $sql;

	
?>

