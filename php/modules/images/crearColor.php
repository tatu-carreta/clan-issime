<?php
	require ("../../config.php"); 
	require ("../../funciones.php"); 
	$nombreColor = $_POST['nombre'];
	$idArticulo = $_POST['idArticulo'];
	
	$articulo = obtenerDatosArticulo($idArticulo);
	
	$idColor = crearColor($idArticulo, $nombreColor);

	$tallesArray = $_POST['talles'];
        
        foreach( $tallesArray as $key => $value)
	{
			$nombreTalle = $_POST['categoriaTalles'][$key];
			$talle = crearTalle($idColor, $articulo['idCategoriaTalle'], $nombreTalle, $value);
	}
	
	echo $idColor;
?>