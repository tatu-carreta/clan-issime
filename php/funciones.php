<?php	

    function conectar()
    {
            $conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SELECTED);

            if (!$conexion) {
                die('Error de Conexión (' . mysqli_connect_errno() . ') '
                        . mysqli_connect_error());
            }
            mysqli_set_charset($conexion, 'utf8');
            return $conexion;
    }
    
    function desconectar($conexion)
    {
        mysqli_close($conexion);
    }
	
	
function urlAmigable($string, $replacement = '-', $map = array()) {
    if (is_array($replacement)) {
        $map = $replacement;
        $replacement = '+';
    }
    $quotedReplacement = preg_quote($replacement, '/');

    $merge = array(
        '/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
        '/\\s+/' => $replacement,
        sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
    );
      
    $_transliteration = array(
    '/ä|æ|ǽ/' => 'ae',
'/ö|œ/' => 'oe',
'/ü/' => 'ue',
'/Ä/' => 'Ae',
'/Ü/' => 'Ue',
     '/Ö/' => 'Oe',
     '/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
     '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',
     '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
     '/ç|ć|ĉ|ċ|č/' => 'c',
     '/Ð|Ď|Đ/' => 'D',
     '/ð|ď|đ/' => 'd',
     '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
     '/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
     '/Ĝ|Ğ|Ġ|Ģ/' => 'G',
     '/ĝ|ğ|ġ|ģ/' => 'g',
     '/Ĥ|Ħ/' => 'H',
     '/ĥ|ħ/' => 'h',
     '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' => 'I',
     '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/' => 'i',
     '/Ĵ/' => 'J',
     '/ĵ/' => 'j',
     '/Ķ/' => 'K',
     '/ķ/' => 'k',
     '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
     '/ĺ|ļ|ľ|ŀ|ł/' => 'l',
     '/Ñ|Ń|Ņ|Ň/' => 'N',
     '/ñ|ń|ņ|ň|ŉ/' => 'n',
     '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
     '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
     '/Ŕ|Ŗ|Ř/' => 'R',
     '/ŕ|ŗ|ř/' => 'r',
     '/Ś|Ŝ|Ş|Š/' => 'S',
     '/ś|ŝ|ş|š|ſ/' => 's',
     '/Ţ|Ť|Ŧ/' => 'T',
     '/ţ|ť|ŧ/' => 't',
     '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
     '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
     '/Ý|Ÿ|Ŷ/' => 'Y',
     '/ý|ÿ|ŷ/' => 'y',
     '/Ŵ/' => 'W',
     '/ŵ/' => 'w',
     '/Ź|Ż|Ž/' => 'Z',
     '/ź|ż|ž/' => 'z',
     '/Æ|Ǽ/' => 'AE',
     '/ß/'=> 'ss',
     '/Ĳ/' => 'IJ',
     '/ĳ/' => 'ij',
     '/Œ/' => 'OE',
     '/ƒ/' => 'f'
     );

    $map = $map + $_transliteration + $merge;
    return preg_replace(array_keys($map), array_values($map), $string);
}  
///////////////////////////////////////SQL///////////////////////////////////

        
	
	/*
	trae el contenido de una tabla con o sin filtro
	la variable filtro debe incluir JOIN, WHERE, ORDER BY, etc..
	si el filtro no esta seteado y no estan seteados los campos trae todo
	html es la estructura html en la que se desea imprimir, si es null no se imprime nada
	*/
	function selectFromTable ($tabla, $campos="*" , $filtros = "", $html = "")
	{
		$conexion = conectar();
		if(! (isset($campos)) or $campos == '')
		{
			$campos = "*";
		}
		$query = "SELECT ".$campos." FROM ".$tabla;
		if($filtros != "")// si esta seteado algun filtro u orden lo agrega a la consulta
		{
			$query .= " ".$filtros;
		}
		$result = mysqli_query($query, $conexion);
		if (!$result)
		{
			echo 'no se realizo la consulta';
		}
		echo $html;
		
		if ($html != "")// si esta seteada alguna estructura html la imprime
		{
			imprimir($result, $html);
		}
		
		if( isset($_GET['debug']))
			{
				echo $query;
			}
                desconectar($conexion);
		return $result;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/*
	imprime producto en html
	*/
	function imprimir($result, $html)
	{
		
		while ($row = mysql_fetch_assoc($result))
		{
			echo $html; //html contiene la estructura html en la que se deben insertar los resultados
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/*
	Dar de alta en la tabla
	
	
	
	devuelve boolean si la consulta se realizo o no
	
	el arreglo de datos debe llegar con los datos en el mismo orden que deben ser insertados en la tabla
	el arreglo atributos son los campos de la tabla, tiene que tener el mismo orden que datos
	tabla es la tabla donde se insertan los datos
	*/
	function alta($datos = array(), $tabla, $atributos = array())
	{
		$conexion = conectar();
		$query = "INSERT INTO ".$tabla." (";
		$i = count($atributos) - 1;
		for ($j = 0; $j<= $i; $j++)
		{
			if ($j == $i)
			{
				$query .= " ".$atributos[$j]." ";
			}
			else
			{
				$query .= " ".$atributos[$j]." , ";
			}
		}
		$query .= ") VALUES (";
		
		$i = count($datos) - 1;
		for ($j = 0; $j<= $i; $j++)
		{
			if ($j == $i)
			{
				$query .= " '".$datos[$j]."' ";
			}
			else
			{
				$query .= " '".$datos[$j]."' , ";
			}
		}
		
		$query .= " )";
		
		
		$result = mysqli_query($query, $conexion);
		desconectar($conexion);
		return ($result);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	/*
	elimina de la tabla segun el id
	devuelve true or false segun si se llevo a cabo o no la consulta
	*/
	function eliminarDeterminado ($id, $tabla)
	{
		$conexion = conectar();
		$query = " DELETE FROM ".$tabla." WHERE id='".$id."'";
		$result = mysqli_query($query, $conexion);
                desconectar($conexion);
		return ($result);
	}

        ////////////////////////////////////////////////////////////////////////////////////////////////
	
	function insertarColor($color, $imagen)
	{
		if ($color== 'Coloque el nombre del color')
		{
			$color = ' ';
		}
		$conexion = conectar();
		$query = " INSERT INTO colores (codigoArticulo, descripcion) VALUES ('1','".$color."') ";
		$result = mysqli_query($conexion, $query);
		$idColor = mysqli_insert_id($conexion);
		$query = " INSERT INTO fotos (idColor, tipo, url) VALUES ('".$idColor."', 'C' ,'".$imagen."') ";
		$result = mysqli_query($conexion, $query);
        desconectar($conexion);
		return ($result);
	}
	
	
	/*
	inicia sesion para el administrador
	*/
	function iniciar_sesion()
	{
		/*
		en algun futuro
		*/
	}
	
        ////////////////////////////////////////////////////////////////////////////////////////////////
        
	/*
	cierra sesion para el administrador
	
	
	*/
	function cerrar_sesion()
	{
		session_start();
		session_unset();
		session_destroy();
		?>
		<script type="text/javascript">
			alert("Se ha cerrado la sesión");
			window.location.href = <?php echo REDIRECT_PATH ?>;
		</script>
		<?php
	}
	
        ////////////////////////////////////////////////////////////////////////////////////////////////
        function obtenerCampana()
		{
			$conect = conectar();
			$sql = "SELECT * FROM campana WHERE estado = 'A' ORDER BY idCampana LIMIT 1";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $campanas = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($campanas, $row);
				}

				return $campanas;
			}
			else
			{
				return false;
			}
		}

		function obtenerPrensa()
		{
			$conect = conectar();
			$sql = "SELECT * FROM prensa WHERE estado = 'A' ORDER BY idPrensa";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $prensa = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($prensa, $row);
				}

				return $prensa;
			}
			else
			{
				return false;
			}
		}
		
		function crearCampana($nombre, $temporada, $anio)
		{
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$fecha = date("Y-n-j");
			$conect = conectar();
			$sql = "INSERT INTO campana (nombreCampana, fechaInicio, temporada, anio, estado) VALUES ('".$nombre."','".$fecha."', '".$temporada."', ".$anio." ,'A')" ;
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idCampana = mysqli_insert_id($conect);
				return $idCampana;
			}
			else
			{
				return false;
			}
		}

		function cargarPrensa($imagen, $titulo)
		{
			
			$conect = conectar();
			$sql = "INSERT INTO prensa (imagen, titulo, estado) VALUES ('".$imagen."','".$titulo."', 'A')" ;
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idPrensa = mysqli_insert_id($conect);
				return $idPrensa;
			}
			else
			{
				return false;
			}
		}

		function eliminarPrensa($id)
		{
			
			$conect = conectar();
			$sql = "UPDATE prensa SET prensa.estado = 'B' WHERE idPrensa = ".$id ;
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		
		
		function crearCategoria($idCategoria, $nombre)
		{
			$conect = conectar();
			$sql = "SELECT * FROM categoria WHERE idCategoria = ".$idCategoria;
			$res = mysqli_query($conect, $sql);
			if(!$res)
			{
				return 0;
			}
			if($row = mysqli_fetch_row($res) <= 0 )
			{
				$url = strtolower (urlAmigable($nombre));
				$conect = conectar();
				$sql = "INSERT INTO categoria (nombreCategoria, nombreCategoriaUrl, estado) VALUES ('".$nombre."','".$url."', 'A')" ;
				$res = mysqli_query($conect, $sql);
				$idCategoria = mysqli_insert_id($conect);
			}
					
					
			$sql = "SELECT * FROM campana_categoria WHERE idCategoria = ".$idCategoria;
			$res = mysqli_query($conect, $sql);
			if(!$res)
			{
				return 0;
			}
			if($row = mysqli_fetch_row($res) <= 0 )
			{
					$campanas = obtenerCampana();
					if(empty($campanas))
					{
						return 2;
					}
					$orden = obtenerOrdenCategoriaAInsertar();
					$sql = "INSERT INTO campana_categoria (idCampana, idCategoria, orden) VALUES ('".$campanas[0]['idCampana']."','".$idCategoria."', '".$orden."')" ;
					$res = mysqli_query($conect, $sql);
					if($res)
					{
						return 1;
					}
					else
					{
						return 3;
					}
			}	
					

			
			return 4;
				
		
		}
		
		function eliminarCampana()
		{
			$conect = conectar();
			$sql = "UPDATE campana SET campana.estado = 'B' " ;
			$res = mysqli_query($conect, $sql);
			
			$sql = "UPDATE articulo SET articulo.estado = 'B' " ;
			$res = mysqli_query($conect, $sql);
			return $sql;
		}
	
        function obtenerCategoriaPorNombre($categoria)
        {
            $conect = conectar();
            
            $sql = "SELECT * FROM categoria WHERE nombreCategoriaUrl = '".$categoria."' ";
            /*
            if($sentencia = mysqli_prepare($conect, $sql))
            {
                mysqli_stmt_bind_param($sentencia, "s", $categoria);
            
                mysqli_stmt_execute($sentencia);
                
                mysqli_stmt_bind_result($sentencia, $dato);
                
                echo $dato;
                
                var_dump($dato);
                
                return mysqli_stmt_fetch($sentencia);
            }
            /*
            desconectar($conect);
            
            return false;
            
            */
            $res = mysqli_query($conect, $sql);
            return $res;
        }
		
		function obtenerTallesPorCategoriaTalle($idCategoria)
		{
			$conect = conectar();
            
            $sql = "SELECT * FROM talle_categoria WHERE idCategoriaTalle = ".$idCategoria." ORDER BY talle_categoria.idTalleCategoria";
         
           $res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $talles = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($talles, $row);
				}

				return $talles;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerCategoriasDeTalles()
		{
			$conect = conectar();
            
            $sql = "SELECT * FROM categoria_talle";
         
           $res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $talles = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($talles, $row);
				}

				return $talles;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerCategorias($idCampana)
        {
            $conect = conectar();
            
            $sql = "SELECT * FROM campana_categoria LEFT JOIN categoria ON (campana_categoria.idCategoria = categoria.idCategoria) WHERE idCampana = ".$idCampana." ORDER BY campana_categoria.orden";
         
           $res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $categorias = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($categorias, $row);
				}

				return $categorias;
			}
			else
			{
				return false;
			}
        }
		
		function crearArticulo($nombre, $nombreUrl, $codigo, $material, $idCategoria, $precioMayorista, $precioMinorista, $idImagenDestacada, $idCategoriaTalle, $ofertaNuevo, $orden)
		{
			$conect = conectar();
			if ($idImagenDestacada == null)
			{
				$sql = "INSERT INTO articulo (nombreArticulo, nombreArticuloUrl, codigoArticulo, material, idCategoria, precioMayorista, precioMinorista, idCategoriaTalle, ofertaNuevo, orden, estado) VALUES 
				('".$nombre."','".$nombreUrl."','".$codigo."', '".$material."' ,'".$idCategoria."' ,'".$precioMayorista."' ,'".$precioMinorista."' , '".$idCategoriaTalle."' ,'".$ofertaNuevo."' ,'".$orden."' , 'A')" ;
			}
			else
			{
				$sql = "INSERT INTO articulo (nombreArticulo, nombreArticuloUrl, codigoArticulo, material, idCategoria, precioMayorista, precioMinorista, idImagenDestacada, idCategoriaTalle, ofertaNuevo, orden, estado) VALUES 
				('".$nombre."','".$nombreUrl."','".$codigo."', '".$material."' ,'".$idCategoria."' ,'".$precioMayorista."' ,'".$precioMinorista."' ,'".$idImagenDestacada."' , '".$idCategoriaTalle."' ,'".$ofertaNuevo."' ,'".$orden."' , 'A')" ;
			}
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idArticulo = mysqli_insert_id($conect);
				return $idArticulo;
			}
			else
			{
				return false;
			}
		}
		
		function modificarArticulo($idArticulo, $nombre, $nombreUrl, $codigo, $material, $idCategoria, $precioMayorista, $precioMinorista, $idImagenDestacada,  $ofertaNuevo)
		{
			$conect = conectar();
			if ($idImagenDestacada == null)
			{
				$sql = "UPDATE  articulo SET nombreArticulo ='".$nombre."', nombreArticuloUrl ='".$nombreUrl."', codigoArticulo = '".$codigo."', material = '".$material."' , idCategoria=  '".$idCategoria."' , precioMayorista = '".$precioMayorista."', precioMinorista = '".$precioMinorista."',  ofertaNuevo = '".$ofertaNuevo."' WHERE idArticulo = ".$idArticulo ;
			}
			else
			{
			
				$sql = "UPDATE  articulo SET nombreArticulo ='".$nombre."', nombreArticuloUrl ='".$nombreUrl."', codigoArticulo = '".$codigo."', material = '".$material."' , idCategoria=  '".$idCategoria."' , precioMayorista = '".$precioMayorista."', precioMinorista = '".$precioMinorista."', idImagenDestacada = '".$idImagenDestacada."' , ofertaNuevo = '".$ofertaNuevo."' WHERE idArticulo = ".$idArticulo ;
		
			}
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idArticulo = mysqli_insert_id($conect);
				return $idArticulo;
			}
			else
			{
				return $sql;
			}
		}
		
		function crearImagen($idColor, $tipo, $nombreImagen, $idCorrelativa = null)
		{
			if ($idCorrelativa == null)
			{
				$idCorrelativa = "NULL";
			}
			$conect = conectar();
			if ($idColor == null)
			{
				$sql = "INSERT INTO imagen ( tipo, nombreImagen, estado, idCorrelativa) VALUES 
				('".$tipo."', '".$nombreImagen."' , 'A', ".$idCorrelativa.")" ;
			}
			else
			{
				$sql = "INSERT INTO imagen (idColor, tipo, nombreImagen, estado, idCorrelativa) VALUES 
				('".$idColor."','".$tipo."', '".$nombreImagen."' , 'A', ".$idCorrelativa.")" ;
			}
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idImagen = mysqli_insert_id($conect);
				return $idImagen;
			}
			else
			{
				return $sql;
			}
		}

		function cargarLookbook($idLookbook, $imgChica, $imgGrande = null)
		{
			
			if ($imgGrande == null)
			{
				$imgGrande = "NULL";
			}
			$conect = conectar();
			if ($idLookbook == null)
			{
				$idLookbook = 3;
			}
			
				$sql = "INSERT INTO imagenes_lookbook (idLookbook, imgChica, imgGrande) VALUES 
				('".$idLookbook."', '".$imgChica."', '".$imgGrande."')" ;
			
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				return true;
			}
			else
			{
				return $sql;
			}
		}
		
		function crearColor($idArticulo, $nombreColor)
		{
			$conect = conectar();

			$sql = "INSERT INTO color (idArticulo, nombreColor, estado) VALUES 
			('".$idArticulo."', '".$nombreColor."' , 'A')" ;
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idColor = mysqli_insert_id($conect);
				return $idColor;
			}
			else
			{
				return false;
			}
		}
        
		function modificarColor($idColor, $nombreColor)
		{
			$conect = conectar();

			$sql = "UPDATE color SET nombreColor = '".$nombreColor."' WHERE idColor = ".$idColor ;
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				$idColor = mysqli_insert_id($conect);
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function crearTalle($idColor, $idCategoriaTalle, $nombreTalle, $value)
		{
			$conect = conectar();

			$sql = "INSERT INTO talle (idColor, idCategoriaTalle, nombreTalle, valor) VALUES 
			('".$idColor."', '".$idCategoriaTalle."', '".$nombreTalle."' , ".$value." )" ;
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				return "talle ok";
			}
			else
			{
				return $sql;
			}
		}
		
		
		function eliminarTalles($idColor)
		{
			$conect = conectar();

			$sql = "DELETE FROM talle WHERE idColor = ".$idColor ;
			
			$res = mysqli_query($conect, $sql);
			if($res)
			{
				return true;
			}
			else
			{
				return $sql;
			}
		}
		
         function obtenerOrdenArticuloAInsertar()
		{
			$conect = conectar();
			$sql = "SELECT orden FROM articulo ORDER BY orden DESC LIMIT 1";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$row = mysqli_fetch_assoc($res);
			}
			return $row['orden'];
		}
		
		  function obtenerOrdenCategoriaAInsertar()
		{
			$conect = conectar();
			$sql = "SELECT orden FROM campana_categoria ORDER BY orden DESC LIMIT 1";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$row = mysqli_fetch_assoc($res);
			}
			return $row['orden'];
		}
		
		 function obtenerDatosArticulo($idArticulo)
		{
			$conect = conectar();
			$sql = "SELECT * FROM articulo WHERE idArticulo ='".$idArticulo."' LIMIT 1";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$row = mysqli_fetch_assoc($res);
				return $row;
			}
			else
			{
				return false;
			}
		}

		function obtenerLookbook()
		{
			$conect = conectar();
			$sql = "SELECT lookbook.idLookbook, lookbook.titulo, imagenes_lookbook.idImagenLookbook, imagenes_lookbook.imgChica, imagenes_lookbook.imgGrande FROM lookbook LEFT JOIN imagenes_lookbook ON (lookbook.idLookbook = imagenes_lookbook.idLookbook)";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$locales = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($locales, $row);
				}

				return $locales;
			}
			else
			{
				return false;
			}
		}

		function obtenerLocales()
		{
			$conect = conectar();
			$sql = "SELECT * FROM texto_locales LIMIT 1";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$lookbook = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($lookbook, $row);
				}

				return $lookbook;
			}
			else
			{
				return false;
			}
		}

		function modificarLocales($texto, $id)
		{
			$conect = conectar();
			$sql = "UPDATE texto_locales SET texto = '".$texto."' WHERE idTexto = ".$id;
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function cambiarNombreLookbook($titulo, $idLookbook){
			$conect = conectar();
			$sql = "UPDATE lookbook SET titulo = '".$titulo."' WHERE idLookbook = ".$idLookbook;
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function obtenerSeccionesLookbook()
		{
			$conect = conectar();
			$sql = "SELECT * FROM lookbook";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$lookbook = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($lookbook, $row);
				}

				return $lookbook;
			}
			else
			{
				return false;
			}
		}

		function obtenerArticulosPorIdCategoria($idCategoria)
		{
			$conect = conectar();
			$sql = "SELECT * FROM articulo LEFT JOIN imagen ON (imagen.idImagen = articulo.idImagenDestacada)  WHERE articulo.estado = 'A' AND idCategoria ='".$idCategoria."' ORDER BY articulo.orden
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $articulos = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($articulos, $row);
				}

				return $articulos;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerArticuloPorNombre($nombreArticulo)
		{
			$conect = conectar();
			$sql = "SELECT * FROM articulo LEFT JOIN imagen ON (imagen.idImagen = articulo.idImagenDestacada) INNER JOIN categoria ON (articulo.idCategoria = categoria.idCategoria) WHERE articulo.nombreArticuloUrl = '".$nombreArticulo."' AND articulo.estado = 'A'
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $articulos = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($articulos, $row);
				}

				return $articulos;
			}
			else
			{
				return false;
			}
		}
		
		
		function cambiarPrecioArticulo($idArticulo, $nuevoPrecio, $tipoPrecio)
		{
			
			$conect = conectar();
			$sql = "UPDATE  articulo SET ".$tipoPrecio." = ".$nuevoPrecio." WHERE idArticulo ='".$idArticulo."' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function eliminarArticulo($idArticulo)
		{
			
			$conect = conectar();
			$sql = "UPDATE articulo SET estado = 'B' WHERE idArticulo ='".$idArticulo."' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function eliminarLookbook($idImagenLookbook)
		{
			
			$conect = conectar();
			$sql = "DELETE FROM imagenes_lookbook  WHERE idImagenLookbook ='".$idImagenLookbook."' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function eliminarCategoria($idCategoria)
		{
			
			$conect = conectar();
			$sql = "DELETE c FROM campana_categoria c  INNER JOIN campana s ON (c.idCampana = s.idCampana) WHERE c.idCategoria ='".$idCategoria."' AND s.estado = 'A' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return $sql;
			}
		}
		
		function obtenerArticuloPorId($idArticulo)
		{
			$conect = conectar();
			$sql = "SELECT * FROM articulo LEFT JOIN imagen ON (imagen.idImagen = articulo.idImagenDestacada) INNER JOIN categoria ON (articulo.idCategoria = categoria.idCategoria) WHERE  articulo.idArticulo ='".$idArticulo."' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$articulo = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($articulo, $row);
				}

				return $articulo;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerColoresPorArticulo($idArticulo)
		{
			$conect = conectar();
			$sql = "SELECT * FROM color WHERE idArticulo ='".$idArticulo."' AND color.estado != 'B'  ORDER BY idColor DESC
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $colores = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($colores, $row);
				}

				return $colores;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerImagenesPorColor($idColor, $tipo)
		{
			$conect = conectar();
			$sql = "SELECT * FROM imagen  WHERE tipo = '".$tipo."' AND idColor ='".$idColor."'  AND estado = 'A'
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $imagenes = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($imagenes, $row);
				}

				return $imagenes;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerTallesPorColor($idColor)
		{
			$conect = conectar();
			$sql = "SELECT * FROM talle  WHERE idColor ='".$idColor."' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $talles = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($talles, $row);
				}

				return $talles;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerTallesPorArticulo($idCategoria)
		{
			$conect = conectar();
			$sql = "SELECT * FROM talle_categoria LEFT JOIN categoria_talle ON (talle_categoria.idCategoriaTalle = categoria_talle.idCategoriaTalle) WHERE talle_categoria.idCategoriaTalle = ".$idCategoria;
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $talles = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($talles, $row);
				}

				return $talles;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerCategoriaTalle()
		{
			$conect = conectar();
			$sql = "SELECT * FROM categoria_talle";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $talles = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($talles, $row);
				}

				return $talles;
			}
			else
			{
				return false;
			}
		}

		
		function modificarOrdenArticulos($i, $idArticulo)
		{
			$conect = conectar();
			$sql = "UPDATE articulo SET Orden =".$i." WHERE idArticulo =".$idArticulo;
			$res = mysqli_query($conect, $sql);
			return $res;
		}
		
		function modificarOrdenCategorias($i, $idCategoria)
		{
			$conect = conectar();
			$sql = "UPDATE campana_categoria SET orden =".$i." WHERE idCategoria =".$idCategoria;
			$res = mysqli_query($conect, $sql);
			return $res;
		}
		
		function setOfertaNuevoNormal($idArticulo, $estado)
		{
			$conect = conectar();
			$sql = "UPDATE articulo SET ofertaNuevo = '".$estado."' WHERE idArticulo =".$idArticulo;
			$res = mysqli_query($conect, $sql);
			return $res;
		}

	
		function obtenerCategoriasPredefinidas()
		{
			$conect = conectar();
			$sql = "SELECT * FROM categoria ORDER BY idCategoria ";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $cat = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($cat, $row);
				}

				return $cat;
			}
			else
			{
				return false;
			}
		}
		
		function obtenerCategoriasActuales()
		{
			$conect = conectar();
			$sql = "SELECT * FROM campana_categoria LEFT JOIN categoria ON  (campana_categoria.idCategoria = categoria.idCategoria ) INNER JOIN campana ON (campana_categoria.idCampana = campana.idCampana) WHERE campana.estado = 'A' ORDER BY campana_categoria.orden";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				 $cat = array();

				while ($row = mysqli_fetch_assoc($res)) {
					array_push($cat, $row);
				}

				return $cat;
			}
			else
			{
				return false;
			}
		}
		
		
		function obtenerMaxIdColor()
		{
			$conect = conectar();
			$sql = "SELECT idColor FROM color ORDER BY idColor DESC LIMIT 1 ";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$row = mysqli_fetch_assoc($res);
				return $row['idColor'];
			}
			else
			{
				return false;
			}
		}
		
		function obtenerIdInsertarCategoria()
		{
			$conect = conectar();
			$sql = "SELECT idCategoria FROM categoria ORDER BY idCategoria DESC LIMIT 1 ";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$row = mysqli_fetch_assoc($res);
				return $row['idCategoria'] ++;
			}
			else
			{
				return 0;
			}
		}
		
		function obtenerMaxIdImagen()
		{
			$conect = conectar();
			$sql = "SELECT idImagen FROM imagen ORDER BY idImagen DESC LIMIT 1 ";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				$row = mysqli_fetch_assoc($res);
				return $row['idImagen'];
			}
			else
			{
				return false;
			}
		}
		
		function eliminarColor($idColor)
		{
			
			$conect = conectar();
			
			$sql = "UPDATE color SET estado = 'B' WHERE idColor ='".$idColor."' 
			";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function eliminarImagen($idImagen)
		{
			
  
  
			$conect = conectar();
			$sql = "UPDATE imagen as I LEFT JOIN imagen as Y  ON I.idCorrelativa = Y.idImagen SET I.estado= 'B', Y.estado = 'B'  WHERE I.idImagen ='".$idImagen."' ";
			$res = mysqli_query($conect, $sql);
			if($res != '')
			{
				return true;
			}
			else
			{
				return $sql;
			}
		}
?>