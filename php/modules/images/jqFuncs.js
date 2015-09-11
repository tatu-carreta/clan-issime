		
  function handleFileSelect(evt, showcase, idColor) {
	

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
					$.each(coloresArray, function(index) {	//para cada color
						if(coloresArray[index].id == idColor)
						{
							coloresArray[index].dataArray.push({id: idImagen ,imagen: e.target.result, name : file.name, value : e.target.result});
							return true;
						}
					});
					
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
			template   = template + '<label  class="labelTalle">'+this+'<input data="'+numeroColor+'"  class="check" checked="checked" type="checkbox" name="'+this+'"  id="'+i+'"></label>';
			i ++;
		});
		return template;
	 }
	 
	  function reiniciarCheckboxes(talle, numeroColor)
	 {
		 var template ='';
		 var i = 0;
		 $.each(categoriasTalles[0][talle], function(index){
			template   = template + '<label class="labelTalle">'+this+'<input data="'+numeroColor+'"  class="check" checked="checked" type="checkbox" name="'+this+'"  id="'+i+'"></label>';
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
			
	 }
	 
	 function eliminarColorHTML(divColor)
	 {
		$(divColor).fadeOut(800, function() { $(this).remove(); });
		cantidadColores = cantidadColores - 1;
	 }
	 
	 function eliminarImagen(idImagen, object)
	 {

		var idColor = object.parent().parent().parent().attr("data");
		$.each(coloresArray, function(index) {	
			if(this.id == idColor)
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
		// eliminar HTML de imagen. Object es el boton eliminar, parent es el li
		object.parent().fadeOut(400, function() { $(this).remove(); });
	 }
	 
	
	 
	 function agregarColor()
	 {
		templateCheckbox = crearCheckboxes(); //crea una template con la cantidad de checkboxes necesarios
		$('<div class="cadaColor dropzone sortable" data="'+numeroColor+'"  ><ul class="list listaImgCadaColor connectedSortable"><li><a class="agregarFoto" href="#">Agregar foto</a><input type="file" accept="image/*" class="imgColor" name="imgColor" style="display:none"></li></ul><div class="checkers" data="'+numeroColor+'">'+templateCheckbox+'</div><p class="nombreColor">Nombre color: <input class="editable sinBorde nombreColor" value="color '+numeroColor+'" placeholder="nombre del color"/></p><a href="" class="eliminar">eliminar color</a></div>').prependTo('.contentColores');
		//setup(); ESTO HACE SORTABLE A LAS IMAGENES ENTRE COLORES
		var arrayTalles = new Array(numTalles)
		inicializarArray(arrayTalles); //pone todo el array en true por default
		coloresArray.push({id: numeroColor , nombre: "", talles: arrayTalles, dataArray: new Array()});
		numeroColor = numeroColor +1;
		cantidadColores ++;
	 }
	 
	 
	 
	 
  $(document).ready( function(){
  

	categoria = idCategoria;
	coloresArray = [];
	tipoTalles = 't01';
	numTalles = 4;
	idImagen=0; 
	numeroColor = 0;
	cantidadColores = 0;
	var imgDestacada = '';
	var nombreImgDestacada = '';
	categoriasTalles = [];
	categoriasTalles.push({t01: ['01','02','03','04'] , t40:['40','42','44','46','48'], t22:['22','24','26','28','30','32','34','36'] , cd:['35','36','37','38','39','40','41'] , rn:['06','12','18','1 ','2','4','6','8','10','12','14'] , cn:['24','25','26','27','28','29','30','31','32','33','34']});

	
	jQuery.event.props.push( "dataTransfer" );
	
	agregarColor();
	
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
		$.each(coloresArray, function(index) {
			if(this.id == id)
			{
				this.nombre = nombre;
			}
		});
		//console.log(coloresArray);
	})
	
	$(".precioMayorista").numeric();
	$(".precioMinorista").numeric();
	
	$(".tipoDeTalles").click( function(){
		var talle = $(this).attr("id");
		tipoTalles = talle;
		numTalles = categoriasTalles[0][talle].length;
		$.each($(".checkers"), function(index){
			$(this).html(reiniciarCheckboxes(talle, $(this).attr("data")));
		});
		var arrayTalles = new Array(numTalles)
		inicializarArray(arrayTalles); //pone todo el array en true por default
		$.each(coloresArray, function(index) {
			this.talles = arrayTalles;
		});
	});
	
	$(".muestraArt").click( function(e){ //al hacer clic en la imagen de portada permite subir archivo
		e.preventDefault();
		$('#imgDestacada').trigger('click');
	});
	
	$('#imgDestacada').change(function(e) { //cuando el archivo cambia genera la visualización
		var fileReader = new FileReader();
		fileReader.readAsDataURL(this.files[0]);
		nombreImgDestacada = this.files[0].name;
		var showcase = $(".list").last(); 
		var idColor = 0;

		fileReader.onloadend = function(event){
			imgDestacada = event.target.result;

			$(".portada").attr("src", event.target.result);
			
			if(coloresArray[0].dataArray.length == 0)
			{
				var span = document.createElement('li');
				$(span).html( '<img class="thumb" data="'+idImagen+'" src="'+event.target.result+'" title="'+nombreImgDestacada+'"/><a href="#" class="eliminarImagen" onclick="eliminarImagen('+idImagen+',$(this)); return false;">X</a>');
				showcase.append(span);

				coloresArray[0].dataArray.push({id: idImagen ,imagen: event.target.result, name : nombreImgDestacada, value : event.target.result});
				return true;
		
				
				idImagen = idImagen + 1;  
			}
			
			
		}

	});
	
	$(document).on("click", ".agregarFoto", function(e){ //al hacer clic en la imagen de portada permite subir archivo
		e.preventDefault();
		$(this).next(".imgColor").trigger('click');

	});
	
	$(document).on("change", ".imgColor", function(e) { //cuando el archivo cambia genera la visualización
		
		var file = this.files[0];
		var showcase = $(this).parent().parent().parent().find(".list");
		var idColor = $(this).parent().parent().parent().attr("data");

			var fileReader = new FileReader();
			fileReader.readAsDataURL(file);
			
			fileReader.onloadend = function(e){
				var span = document.createElement('li');
				$(span).html( '<img class="thumb" data="'+idImagen+'" src="'+this.result+'" title="'+file.name+'"/><a href="#" class="eliminarImagen" onclick="eliminarImagen('+idImagen+',$(this)); return false;">X</a>');
				showcase.append(span);
				$.each(coloresArray, function(index) {	//para cada color
					if(coloresArray[index].id == idColor)
					{
						coloresArray[index].dataArray.push({id: idImagen ,imagen: e.target.result, name : file.name, value : e.target.result});
						return true;
					}
				});
				
				idImagen = idImagen + 1;  
			}

	//console.log(coloresArray);
	});
	

	
	$("#agregarColor").click( function(){
		if(cantidadColores < 20)
		{
			agregarColor();
			//console.log(coloresArray);
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

		$.each(coloresArray, function(index) {	
			if(coloresArray[index].id == idColor)
			{

				coloresArray[index].talles[idTalle] = coloresArray[index].talles[idTalle] * (-1);
				return true;
			}
		});

	});

		
	$(".debug").click(function(){
		console.log(coloresArray);
	});
	
	$("#publicar").click( function(){
		

		var action = validarForm();
		if(action){
			$("#loading").dialog({ show: 'fold', closeText: "cerrar", create: function() {
			
				var form = $("#formArticulo").serialize();
				form = form + "&idCategoria="+categoria + "&imgValue="+imgDestacada + "&imgName="+ nombreImgDestacada;
				//console.log(form);
				$.post(PATH_PHP_MODULES_IMAGES + 'crearArticulo.php', form, function(returned){  //envia primero los datos para crear el articulo
					var idArticulo = returned; //esta variable devuelve el id con el que se inserto el articulo
					//console.log (idArticulo);

				
				
					$.each(coloresArray, function(index) {	//para cada color
						
						$.post(PATH_PHP_MODULES_IMAGES + 'crearColor.php', {categoriaTalles: categoriasTalles[0][tipoTalles] ,idArticulo: idArticulo, nombre: coloresArray[index].nombre, talles: coloresArray[index].talles}, function(returned){  //envia luego los datos para crear el color
							//var idColor = returned; //esta variable devuelve el id con el que se inserto el color
							
							var idColor = returned;
							
							var arrayImagenes = coloresArray[index].dataArray;
													console.log("here it goes");
												   console.log(arrayImagenes);
								
							$.post(PATH_PHP_MODULES_IMAGES + 'subirImagenes.php', { idColor: idColor, arrayImages: arrayImagenes}, function(data) { //envia todo el array de imagenes del color
								//se encarga de limpiar todo cuando termina de subir imagenes, mostrar mensajes etc.
								//alert(data);
								console.log(data);
							//	$("#loading").html("<p>Espere mientras se guardan los cambios.</p>");
								setTimeout(function() {
									//window.location.href = PATH_CONTROLLER + "controladorCatalogo.php?categoria="+categoriaURL;
                                                                        window.location.href = PATH_HOME + "catalogo/"+categoriaURL+"/admin";
								}, 2000);
								
							});
				
						});
						
					});
				});
				
			
			
			} });
		
			
			
		}
	});

  
 });
