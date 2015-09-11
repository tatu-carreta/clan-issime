<?php
	require ("../config.php"); 
	require ("../funciones.php"); 
	
	$control = "hubo un error";
	
	if(isset($_POST['locales']) and isset($_POST['idLocal']) )
	{
		$control = modificarLocales($_POST['locales'], $_POST['idLocal']);
	}

	header('Location: ../../controller/controladorAdmin.php?seccion=locales');
?>