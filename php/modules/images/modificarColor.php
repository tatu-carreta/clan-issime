<?php
	require ("../../config.php"); 
	require ("../../funciones.php"); 
	$nombreColor = $_POST['nombre'];
	$idColor = $_POST['idColor'];
	$idArticulo = $_POST['idArticulo'];
	
	
	$articulo = obtenerDatosArticulo($idArticulo);
	
	$control = modificarColor($idColor, $nombreColor);
	if ($control)
	{
		$control = eliminarTalles($idColor);
		if($control)
		{
			echo 'todo piola';
		}
	}

	$tallesArray = $_POST['talles'];
        
        foreach( $tallesArray as $key => $value)
	{

			$nombreTalle = $_POST['categoriaTalles'][$key];
			$talle = crearTalle($idColor, $articulo['idCategoriaTalle'], $nombreTalle, $value);

	}
	
	
?>