<?php
		require_once '../php/funciones.php';
		if(isset($_POST['nombreCampana']))
		{
			$consulta = crearCampana($_POST['nombreCampana']);
			if($consulta)
			{
				echo 'La campaña se ha creado con éxito.';
			}
			else
			{
				'Hubo un error en la consulta.';
			}
		}
?>