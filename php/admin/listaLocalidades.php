<?php
require_once '../config.php';
    require_once '../funciones.php';
    require_once '../funcionesPhp.php';
    require_once '../funcionesPersona.php';

    
    if(isset($_POST['idProvincia']))
    {
        $idProv = sanearDatos($_POST['idProvincia']);
    }
    else
    {
        $idProv = -1;
    }
    
    $localidades = obtenerLocalidadesPorIdProvincia($idProv);
    
    $options = "<option value=''>Seleccione una Localidad</option>";

        foreach ($localidades as $local)
        {
            $options .= "<option value='".$local['idCiudad']."'>".($local['nombreCiudad'])."</option>";
        }
    
        echo $options;    
?>