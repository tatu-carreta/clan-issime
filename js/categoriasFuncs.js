jQuery.ajaxSetup({async:false});
$(document).ready( function(){
	
	
	
	window.onbeforeunload = function() {
		if((arrayCategorias.length > counter) && (noAbandonarLaPagina))
		{
			return "Hay categorías que no han sido guardadas. ¿Está seguro que desea abandonar la página?";
		}
	}
	
	
	$(".sortable").sortable({ appendTo: "parent", revert: "true", delay:50 ,  cursor: "move" ,
			stop: function (event, ui) {
				var data = $(this).sortable('serialize');

				// POST to server using $.post or $.ajax
				$.ajax({
					data: data,
					type: 'POST',
					url: PATH_PHP_ADMIN + 'actualizarOrdenCategorias.php'
				});
			}
	});
	
	$(document).on("click", ".btnEliminar", function(){
		var idDelete = $(this).parent().attr("id");
		var i = parseInt(idDelete.replace(/\D/g, ''), 10);
		$.each(arrayCategorias, function(index) {
			if(this.idCategoria == i)
				{
					arrayCategorias.splice(index,1);
				}
		});
		$("#"+idDelete).fadeOut();
	})
	.on("click", ".eliminarDB", function(){
		var id = $(this).attr("id");
		if(confirm("Está a punto de eliminar una categoría y todos sus artículos. ¿Desea continuar?"))
		{
			$.ajax({
					data: {idCategoria: id},
					type: 'POST',
					url: PATH_PHP_ADMIN + 'eliminarCategoria.php',
					success: function(html)
					{
						console.log(html);
					}
				});
			$(this).parent().fadeOut();
		}
		
	});
	
		$(".debug").click(function(){
		console.log(arrayCategorias);
	});
	
	$(".nombreCat").keyup(function(e){
		if(e.keyCode == 13)
		{
			$(".agregarCat").trigger("click");
			$(".nombreCat").val("");
			
		}

	});
	
	$(".agregarCat").click( function(e)
	{
		e.preventDefault();
		var name = $(".nombreCat").val();
		if(name != '')
		{
			lastId ++;
			arrayCategorias.push({idCategoria: lastId, nombreCategoria: name});
			$(".divCategoriasNuevas").append('<p id="'+lastId+'">'+name+'<a class="btnEliminar"><span>Eliminar</span></a></p>');
		}
		console.log(arrayCategorias);
	});
	
	var control = true;
	
	$(".guardarCampana").click(function(e){
		
		
		
		$(".section").append('<div id="loading"><span>Por favor espere mientras las categorías se cargan.</span><br><img src="'+PATH_PHP_MODULES_IMAGES+'loading.gif" /></div>');
		$("#loading").dialog({ show: 'fold', closeText: "cerrar", create: function() {
			

			if(arrayCategorias.length == 0)
				{
					$("#loading").html('<span>Los cambios han sido guardados</span>');
					noAbandonarLaPagina = false;
					setTimeout(function() {
									location.reload();
								}, 2000);
				}
				else
				{
					$.each(arrayCategorias, function(index) {	//para cada color
						
						$.post(PATH_PHP_ADMIN + 'cargarCategorias.php', {idCategoria: this.idCategoria, categoria: this.nombreCategoria},
						function(data){ 
							console.log(data);
							//alert(data);
							switch(data)
							{

								case '2':
									var content = 'No hay una campaña actual activa';
									control = false;
								break;
								case '3':
									var content = 'Hubo un error en la carga';
									control = false;
								break;
								case '0':
									var content = 'La campaña se ha guardado.';
									control = true;
								break;
								case '1':
									var content = 'Exito.';
									alert("aaaaaaaaaa");
									control = true;
								break;
								case '4':
									var content = 'Ya estaba cargado.';
									control = true;
								break;
								default:
									var content = '';
									control = true;
								break;
								
							}
							if(control)
							{
								content = "Los cambios se han guardado.";
							}
							else
							{
								content = "Es posible que haya ocurrido un error. Verifique el resultado de la operación"
							}
							$("#loading").html('<span>'+content+'</span>');
							noAbandonarLaPagina = false;
							setTimeout(function() {
									location.reload();
								}, 2000);
						});
						

					});
			
				}
		
		}, 'close': function(){
			
				location.reload();
			
			
		}});
		
  
  });
  
});