<?php
	require ("../config.php"); 
	require ("../funciones.php"); 
	
	$i = 0;

	foreach ($_POST['item'] as $value) {
		// Execute statement:
		$control = modificarOrdenCategorias($i, $value);
		
		$i++;
	}
	echo $control;
?>