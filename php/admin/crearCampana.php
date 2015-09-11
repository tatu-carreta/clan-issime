<?php
	require ("../config.php"); 
	require ("../funciones.php"); 
	
	$control = "hubo un error";
	
	if(isset($_POST['nombreCampana']) and isset($_POST['temporada']) and isset($_POST['anio']))
	{
		$control = crearCampana($_POST['nombreCampana'], $_POST['temporada'], $_POST['anio']);
	}

	header('Location: ../../controller/controladorAdmin.php?seccion=categorias');
?>