<?php
	require ("../config.php"); 
	require ("../funciones.php"); 
	
	switch ($_POST['set']) {
        case 'nuevo':
			$estado = 'N';
            break;
		case 'oferta':
			$estado = 'O';
            break;
		default:
			$estado = 'R';
            break;
	}	
	
	if (isset($_POST['idArticulo']))
	{
		$control = setOfertaNuevoNormal($_POST['idArticulo'], $estado);
		echo $control;
	}
	
	
?>