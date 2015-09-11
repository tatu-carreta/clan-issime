<?php
	require ("../config.php"); 
	require ("../funciones.php");
	$control = "hubo un error";

	if(isset($_POST['id']))
	{
		$control = eliminarPrensa($_POST['id']);
	}

	echo $control;

?>

