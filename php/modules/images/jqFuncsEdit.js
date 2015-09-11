		
  function handleFileSelect(evt, showcase, idColor) {
	
	var i = parseInt(idColor.replace(/\D/g, ''), 10);
    var files = evt.dataTransfer.files; // FileList object.

    // files es un FileList de imagenes
    var output = [];
    $.each(files, function(index, file){
	
			// Para validar que sean solo imagenes
		  if (!files[index].type.match('image.*')) {
			return;
		  }

		  var fileReader = new FileReader();
		  // Captura la informacion del archivo a subir
		  fileReader.onload = (function(file) {
			  
				return function(e) {
					// Renderiza la miniatura

					var span = document.createElement('li');
					$(span).html( '<img class="thumb" data="'+idImagen+'" src="'+this.result+'" title="'+file.name+'"/><a href="#" class="eliminarImagen" onclick="eliminarImagen('+idImagen+',$(this)); return false;">X</a>');
					showcase.append(span);
					var hallado = false;
			
				$.each(coloresArray, function(index) {	//para cada color
					if(coloresArray[index].id == i)
					{
						hallado = true;
						coloresArray[index].dataArray.push({id: idImagen ,imagen: e.target.result, name : file.name, value : e.target.result});
						return true;
					}
				});
				
				

				if ( (i<= maxIdColor) &&( !hallado))
				{
					//es un color viejo que no se agrego
						newArray = [];
						newArray.push({id: idImagen ,imagen: e.target.result, name : file.name, value : e.target.result});
						coloresArray.push({id: i , nombre: "", talles: arrayTalles, dataArray: newArray});
					
				}
				

				idImagen = idImagen + 1;  
					
				};
				
		  })(files[index]);
		  
		  // ldata URL.
		  fileReader.readAsDataURL(file);
		 
    });
  }

  function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
  }

  function validarForm(){

	if(  $('#codigoArticulo').val() == ""  )
	{
		alert("falta el código de artículo");
		return false;
	}
	if(  $('#nombreArticulo').val() == ""  )
	{
		alert("falta el nombre del artículo");
		return false;
	}
	if(  $('#material').val() == ""  )
	{
		alert("falta el material");
		return false;
	}
	if(  $('#precioMayorista').val() == "0"  )
	{
		alert("el precio mayorista está en cero");
		return false;
	}
	if(  $('#precioMinorista').val() == "0"  )
	{
		alert("el precio minorista está en cero");
		return false;
	}
	
	return true;
}
  	
	function setup(){
	
			$( ".list" ).sortable({ connectWith: ".connectedSortable", 
				update: function( event, ui ) { 
					var idColorDestino = ui.item.parent().parent().attr("data");
					var idImagen = ui.item.find("img").attr("data");
					
				}
			}).selectable({
				selecting: function (event, ui)
				{
					if ($(".ui-selected, .ui-selecting").length > 1) {
						$(ui.selecting).removeClass("ui-selecting");
					}
				}
			})
				.find( "li" ).addClass( "ui-corner-all" ).prepend( "<div class='handle'><span class='ui-icon ui-icon-carat-2-n-s'></span></div>" );

				
			// Setup the dnd listeners.
			
			
	  
	  }
	  
	 function inicializarArray(arrayTalles)
	 {
			for ( var i = 0; i < arrayTalles.length; i = i + 1 ) 
			{
					arrayTalles[i] = 1;
			}
	 }
	  
	 function crearCheckboxes()
	 {
		var template ='';
		var i = 0;
		$.each(categoriasTalles[0][tipoTalles], function(index){
			template   = template + '<label class="labelTalle">'+this+'</label><input data="'+numeroColor+'"  class="check" checked="checked" type="checkbox" name="'+this+'"  id="'+i+'">';
			i ++;
		});
		return template;
	 }
	 
	  function reiniciarCheckboxes(talle, numeroColor)
	 {
		 var template ='';
		 var i = 0;
		 $.each(categoriasTalles[0][talle], function(index){
			template   = template + '<label  class="labelTalle">'+this+'</label><input data="'+numeroColor+'"  class="check" checked="checked" type="checkbox" name="'+this+'"  id="'+i+'">';
			i ++;
		 })
		 return template;
	 }
	 
	 function eliminarColor(idDelete){

			$.each( coloresArray, function(index){
				
				if(this.id == idDelete)
				{
					coloresArray.splice(index,1);
				}
				
			});
			
			var i = parseInt(idDelete.replace(/\D/g, ''), 10);

			if  (i<= maxIdColor)
			{
				$.ajax({
					data: {idColor: i},
					type: 'POST',
					url: PATH_PHP_MODULES_IMAGES + 'eliminarColor.php',
					success: function(e)
					{
						console.log(e);
					}
				});
			}
			
	 }
	 
	 function eliminarColorHTML(divColor)
	 {
		$(divColor).fadeOut(800, function() { $(this).remove(); });
		cantidadColores = cantidadColores - 1;
	 }
	 
	 function eliminarImagen(idImagen, object)
	 {

		var idColor = object.parent().parent().parent().attr("data");
		var i = parseInt(idColor.replace(/\D/g, ''), 10);
		
		$.each(coloresArray, function(index) {	
			if(this.id == i)
			{
				$.each(coloresArray[index].dataArray , function(index2){
					if(this.id == idImagen)
					{
						
						coloresArray[index].dataArray.splice(index2,1);
						
						return true;
					}
				});
				return true;
			}
		});
		


		if(idImagen <= maxIdImagen)
			{
				$.ajax({
					data: {idImagen: idImagen},
					type: 'POST',
					url: PATH_PHP_MODULES_IMAGES + 'eliminarImagen.php',
					success: function(e)
					{
						console.log(e);
					}
				});
			}
		// eliminar HTML de imagen. Object es el boton eliminar, parent es el li
		object.parent().fadeOut(400, function() { $(this).remove(); });
	 }
	 
	
	 
	 function agregarColor()
	 {
		templateCheckbox = crearCheckboxes(); //crea una template con la cantidad de checkboxes necesarios
		$('<div class="cadaColor dropzone sortable" data="'+numeroColor+'"  ><ul class="list listaImgCadaColor connectedSortable"><li><a class="agregarFoto" href="#">Agregar foto</a><input type="file" accept="image/*" class="imgColor" name="imgColor" style="display:none"></li></ul><div class="checkers" data="'+numeroColor+'">'+templateCheckbox+'</div><p class="">Nombre color: <input class="editable sinBorde nombreColor" value="color #'+numeroColor+'" placeholder="nombre del color"/></p><a href="" class="eliminar">eliminar color</a></div>').prependTo('.contentColores');
		//setup(); ESTO HACE SORTABLE A LAS IMAGENES ENTRE COLORES
		var arrayTalles = new Array(numTalles)
		inicializarArray(arrayTalles); //pone todo el array en true por default
		coloresArray.push({id: numeroColor , nombre: "", talles: arrayTalles, dataArray: new Array()});
		numeroColor = numeroColor +1;
		cantidadColores ++;
		console.log(coloresArray);
	 }
	 
	 
	 
	 
  $(document).ready( function(){
  

	categoria = idCategoria;
	coloresArray = [];
	tipoTalles = nombreCategoriaTalle;
	numTalles = numeroDeTalles;
	idImagen=maxIdImagen +  1; 
	numeroColor = maxIdColor + 1;
	cantidadColores = 0;
	var imgDestacada = '';
	var nombreImgDestacada = '';
	categoriasTalles = [];
	categoriasTalles.push({t01: ['01','02','03','04'] , t40:['40','42','44','46','48'], t22:['22','24','26','28','30','32','34','36'] , cd:['35','36','37','38','39','40','41'] , rn:['06','12','18','1 ','2','4','6','8','10','12','14'] , cn:['24','25','26','27','28','29','30','31','32','33','34']});

	
	jQuery.event.props.push( "dataTransfer" );
	jQuery.ajaxSetup({async:false});

	
	$(document).on("dragover", ".dropzone", handleDragOver);
	$(document).on("drop", ".dropzone", function(evt){
		evt.stopPropagation();
		evt.preventDefault();
		var showcase = $(this).find(".list");
		var idColor  = $(this).attr("data");
		handleFileSelect(evt, showcase, idColor);
	});
	
	//setup(); ESTO HACE SORTABLE A LAS IMAGENES ENTRE COLORES
	
	$(document).on("change", ".nombreColor", function(){
		var id = $(this).parent().parent().attr("data");
		var nombre = $(this).val();
		var hallado = false;
		$.each(coloresArray, function(index) {
			if(this.id == id)
			{
				this.nombre = nombre;
				hallado = true;
			}
		});
		
		var i = parseInt(id.replace(/\D/g, ''), 10);
		
		

		var hallado = false;
			
		$.each(coloresArray, function(index) {	//para cada color
			if(coloresArray[index].id == i)
			{
				hallado = true;
				coloresArray[index].nombre= nombre;
				return true;
			}
		});
				
		tallesInsertar = [];
		
		$.each(coloresArrayDB, function(index) {	
				if(coloresArrayDB[index].id == i)
				{
					 tallesInsertar = coloresArrayDB[index].talles;
					return true;
				}
			});

		console.log(tallesInsertar);

		if ( (i<= maxIdColor) &&( !hallado))
		{
			//es un color viejo que no se agrego
			coloresArray.push({id: i , nombre: nombre, talles: tallesInsertar, dataArray: new Array()});
		}
				
		console.log(coloresArray);
	})
	
	
	$(".precioMayorista").numeric();
	$(".precioMinorista").numeric();
	
	
	$(".muestraArt").click( function(e){ //al hacer clic en la imagen de portada permite subir archivo
		e.preventDefault();
		$('#imgDestacada').trigger('click');
	});
	
	$('#imgDestacada').change(function(e) { //cuando el archivo cambia genera la visualización
		var fileReader = new FileReader();
		fileReader.readAsDataURL(this.files[0]);
		nombreImgDestacada = this.files[0].name;

		fileReader.onloadend = function(event){
			imgDestacada = event.target.result;

			$(".portada").attr("src", event.target.result);
		}

	});
	
	$(document).on("click", ".agregarFoto", function(e){ //al hacer clic en la imagen de portada permite subir archivo
		e.preventDefault();
		$(this).next(".imgColor").trigger('click');

	});
	
	$(document).on("change", ".imgColor", function(e) { //cuando el archivo cambia genera la visualización
		
		var file = this.files[0];
		var showcase = $(this).parent().parent();
		var idColor = $(this).parent().parent().parent().attr("data");
		var i = parseInt(idColor.replace(/\D/g, ''), 10);
		
			var fileReader = new FileReader();
			fileReader.readAsDataURL(file);
			
			fileReader.onloadend = function(e){
				var span = document.createElement('li');
				$(span).html( '<img class="thumb" data="'+idImagen+'" src="'+this.result+'" title="'+file.name+'"/><a href="#" class="eliminarImagen" onclick="eliminarImagen(' +idImagen+',$(this)); return false;">X</a>');
				showcase.append(span);
				
				var hallado = false;
			
				$.each(coloresArray, function(index) {	//para cada color
					if(coloresArray[index].id == i)
					{
						hallado = true;
						coloresArray[index].dataArray.push({id: idImagen ,imagen: e.target.result, name : file.name, value : e.target.result});
						return true;
					}
				});

				if ( (i<= maxIdColor) &&( !hallado))
				{
					//es un color viejo que no se agrego
						newArray = [];
						newArray.push({id: idImagen ,imagen: e.target.result, name : file.name, value : e.target.result});
						coloresArray.push({id: i , nombre: "", talles: arrayTalles, dataArray: newArray});
					
				}

				idImagen = idImagen + 1;  
			}

	console.log(coloresArray);
	});
	

	
	$("#agregarColor").click( function(){
		if(cantidadColores < 20)
		{
			agregarColor();
		}
	});
	
	$(document).on('click', '.eliminar', function(e){	
		e.preventDefault();
		var dropzone = $(this).parent();
		var idColor = dropzone.attr("data");
		if(confirm("Está a punto de eliminar este color y todas sus imágenes. ¿Desea continuar?"))
		{
			eliminarColor(idColor);
			eliminarColorHTML(dropzone);
		}
	});
	
	$(document).on('click', '.check', function(){	
		var idColor = $(this).attr("data");
		var idTalle = $(this).attr("id");
		var hallado = false;
		$.each(coloresArray, function(index) {	
			if(coloresArray[index].id == idColor)
			{
				coloresArray[index].talles[idTalle] = coloresArray[index].talles[idTalle] * (-1);
				hallado = true;
				return true;
			}
		});
		
		var i = parseInt(idColor.replace(/\D/g, ''), 10);

		if ( (i<= maxIdColor) &&( !hallado))
		{
			
			$.each(coloresArrayDB, function(index) {	
				if(coloresArrayDB[index].id == idColor)
				{
					var tallesInsertar = coloresArrayDB[index].talles;
					tallesInsertar[idTalle] = tallesInsertar[idTalle] * (-1);
					coloresArray.push({id: i , nombre: "", talles: tallesInsertar, dataArray: new Array()});
					return true;
				}
			});
			
		}

	});
	
	$(document).ajaxComplete(function() {
		if($("#loading").length > 0)
		{
			
			setTimeout(function() {
				$("#loading").dialog("close");
				//window.location.href = PATH_CONTROLLER + "controladorCatalogo.php?categoria="+categoriaURL;
                                window.location.href = PATH_HOME + "catalogo/"+categoriaURL+"/admin";
			}, 2000);
		}
	});

		
	$(".debug").click(function(){

		console.log(coloresArrayDB);
	});
	
	$("#publicar").click( function(){
		
		$(".section").append('<div id="loading"><p>Por favor espere mientras las imágenes se cargan.</p><img src="'+PATH_PHP_MODULES_IMAGES+'loading.gif" /></div>');
		$("#loading").dialog({ show: 'fold', closeText: "cerrar", create: function() {
		
			var form = $("#formArticulo").serialize();
			form = form + "&idCategoria="+categoria + "&imgValue="+imgDestacada + "&imgName="+ nombreImgDestacada+'&idArticulo='+idArticulo;
			console.log(form);
			$.post(PATH_PHP_MODULES_IMAGES + 'modificarArticulo.php', form, function(returned){  //envia primero los datos para crear el articulo

				console.log(returned);
			
			
				$.each(coloresArray, function(index) {	//para cada color
					if(coloresArray[index].id <= maxIdColor)
					{
						
						//color viejo
						$.post(PATH_PHP_MODULES_IMAGES + 'modificarColor.php', {categoriaTalles: categoriasTalles[0][tipoTalles], idArticulo: idArticulo, idColor: coloresArray[index].id, nombre: coloresArray[index].nombre, talles: coloresArray[index].talles}, function(returned){ 
							
							var arrayImagenes = coloresArray[index].dataArray;
							console.log(returned);
							if(arrayImagenes.length)
							{
						

								$.post(PATH_PHP_MODULES_IMAGES + 'subirImagenes.php', { idColor: coloresArray[index].id, arrayImages: arrayImagenes}, function(data) { //envia todo el array de imagenes del color
									//se encarga de limpiar todo cuando termina de subir imagenes, mostrar mensajes etc.
									//alert(data);
									console.log(data);
									
								});
							}
					
						});
			
					}
					else
					{
						$.post(PATH_PHP_MODULES_IMAGES + 'crearColor.php', {categoriaTalles: categoriasTalles[0][tipoTalles] , idArticulo: idArticulo, nombre: coloresArray[index].nombre, talles: coloresArray[index].talles}, function(returned){  //envia luego los datos para crear el color
							//var idColor = returned; //esta variable devuelve el id con el que se inserto el color
							
							console.log(returned);
							var arrayImagenes = coloresArray[index].dataArray;


								
							if(arrayImagenes.length)
							{
	
								$.post(PATH_PHP_MODULES_IMAGES + 'subirImagenes.php', { idColor: coloresArray[index].id, arrayImages: arrayImagenes}, function(data) { //envia todo el array de imagenes del color
									//se encarga de limpiar todo cuando termina de subir imagenes, mostrar mensajes etc.
									//alert(data);
									console.log(data);
									
								});
							}
					
				
						});
					}
				});
				
		/*
					$("#loading").html("<span>Las imágenes han sido subidas correctamente.</span>");
					setTimeout(function() {
						  location.reload();
					}, 2500);*/
				
			});
			
		
		
		} });
		
			
			
		
	});

  
 });
