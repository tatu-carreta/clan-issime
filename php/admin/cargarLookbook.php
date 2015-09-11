<?php
	require ("../config.php"); 
	require ("../funciones.php");
	require_once ('../modules/images/SimpleImage.php'); 
	$dir_original = '../../images/originales/';
	$dir_catalogo = "../../images/lookbook/";
	$dir_ampliada = '../../images/ampliadas/';
	
$filesArray = $_POST['arrayImages'];

$seccion =  $_POST['section'];

//$decodedArray = json_decode($filesArray);
$control = true;

	foreach($filesArray as $files)
	{
		$name = $files['name'];
		// obtener extension
		$getMime = explode('.', $name);
		$mime = end($getMime);
		//renombrar
		$randomName = substr_replace(sha1(microtime(true)), '', 12);
		$finalName = $randomName.'.'.$mime;
		//decodificar archivo
		$file = $files['value'];
		$data = explode(',', $file);
		$encodedData = str_replace(' ','+',$data[1]);
		$decodedData = base64_decode($encodedData);
		
		if(file_put_contents($dir_original.$finalName, $decodedData)) 
		{
	
			//crear miniaturas
			$image = new SimpleImage();
			$image->load($dir_original.$finalName);
			
			
			//cortar ampliacion
			$image->fit_to_height(500);
			$image->fit_to_width(380);
			$image->save($dir_ampliada.$randomName.'_big.'.$mime);
			
			$image = new SimpleImage();
			$image->load($dir_original.$finalName);
			//cortar catalogo
			$image->fit_to_height(197);
			$image->fit_to_width(150);
			$image->save($dir_catalogo.$randomName.'_small.'.$mime);
			$control = cargarLookbook($seccion, $randomName.'_small.'.$mime, $randomName.'_big.'.$mime);
			
		}
		else
		{
			$control = 'hubo un error';
		}
		
		
	}



echo $control;
	
?>

