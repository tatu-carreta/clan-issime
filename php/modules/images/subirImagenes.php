<?php
require ("../../config.php"); 
require ("../../funciones.php"); 
require_once ('SimpleImage.php');
 
 $idColor = $_POST['idColor'];
// directorios por defecto
$dir_original = '../../../images/originales/';
$dir_ampliada = '../../../images/ampliadas/';
$dir_miniatura = "../../../images/miniaturas/";
$dir_catalogo = "../../../images/catalogo/";

// The posted data, for reference
$filesArray = $_POST['arrayImages'];

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
			$idImagenGrande = crearImagen($idColor, 'G', $randomName.'_big.'.$mime);
			
			$image = new SimpleImage();
			$image->load($dir_original.$finalName);
			//cortar catalogo
			$image->fit_to_height(195);
			$image->fit_to_width(150);
			$image->save($dir_catalogo.$randomName.'_small.'.$mime);
			$control = crearImagen($idColor, 'C', $randomName.'_small.'.$mime, $idImagenGrande);
			
		}
		else
		{
			$control = 'hubo un error';
		}
		
		
	}

echo $control;
	
?>