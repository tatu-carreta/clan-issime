<?php
	require ("../config.php"); 
	require ("../funciones.php"); 
	
	$control = "hubo un error";
	
	if(isset($_POST['categoria']) and isset($_POST['idCategoria']))
	{
		$control = crearCategoria($_POST['idCategoria'], $_POST['categoria']);
	}

	echo $control;
?>

