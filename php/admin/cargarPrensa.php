<?php
	require ("../config.php"); 
	require ("../funciones.php");
	require_once ('../modules/images/SimpleImage.php'); 
	$dir_original = '../../images/originales/';
	$dir_catalogo = "../../images/prensa/";
	

	$control = "hubo un error";


		$name = $_FILES['imagen']['name'];
		// obtener extension
		$getMime = explode('.', $name);
		$mime = end($getMime);
		//renombrar
		$randomName = substr_replace(sha1(microtime(true)), '', 12);
		$finalName = $randomName.'.'.$mime;
		//decodificar archivo
		$file = $_FILES['imagen']['tmp_name'];
		
		
		if(move_uploaded_file ($file, $dir_original.$finalName )) 
		{
	
			//crear miniaturas
			$image = new SimpleImage();
			$image->load($dir_original.$finalName);
			
			//cortar catalogo
			$image->fit_to_height(415);
			$image->fit_to_width(308);
			$image->save($dir_catalogo.$randomName.'.'.$mime);
			
		}
		else
		{
			$control = 'hubo un error';
		}
	
	if(isset($_FILES['imagen']) and isset($_POST['titulo']))
	{
		$control = cargarPrensa($randomName.'.'.$mime, $_POST['titulo']);
	}


	header('Location: '.PATH_PRENSA);
?>

