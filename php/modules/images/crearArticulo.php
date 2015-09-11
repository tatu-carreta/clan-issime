<?php
	require ("../../config.php"); 
	require ("../../funciones.php"); 
	require_once ('SimpleImage.php');
	
	$idImagen = null;
	
	if(isset($_POST['imgValue']) and isset($_POST['imgName']) and ($_POST['imgValue'] != ""))
	{
	
	
		$dir_catalogo = "../../../images/catalogo/";

		$name = $_POST['imgName'];
		$getMime = explode('.', $name);
		$mime = end($getMime);
		//renombrar
		$randomName = substr_replace(sha1(microtime(true)), '', 12);
		$finalName = $randomName.'.'.$mime;
		
		$file = $_POST['imgValue'];
		$data = explode(',', $file);
		$encodedData = str_replace(' ','+',$data[1]);
		$decodedData = base64_decode($encodedData);
		
		if(file_put_contents($dir_catalogo.$finalName, $decodedData)) 
			{
		
				//crear miniaturas
				$image = new SimpleImage();
				$image->load($dir_catalogo.$finalName);
				
				//cortar ampliacion
				$image->fit_to_height(195);
				$image->fit_to_width(150);
				$image->save($dir_catalogo.$finalName);
				
			}
		$img = $finalName;
		$idColor = null;
		$idImagen = crearImagen($idColor, 'C', $img);
	
	}	
	
	$orden = obtenerOrdenArticuloAInsertar();
	$orden ++;
	
	
	 $nombreURL = strtolower(urlAmigable($_POST['nombre']));
	 
	$sql = crearArticulo($_POST['nombre'], $nombreURL, $_POST['codigo'], $_POST['material'], $_POST['idCategoria'], $_POST['precioMayorista'], $_POST['precioMinorista'], $idImagen, $_POST['talles'], $_POST['estado'], $orden);
	if($sql)
	{
		echo $sql;
	}
	else
	{
		echo $sql;
	}

?>