  $(document).ready( function(){
		$(".precioMayorista").numeric();
		$(".precioMinorista").numeric();

		$(".sortable").sortable({ appendTo: "parent", revert: "true", delay:50 , 
			stop: function (event, ui) {
				var data = $(this).sortable('serialize');

				// POST to server using $.post or $.ajax
				$.ajax({
					data: data,
					type: 'POST',
					url: PATH_PHP_ADMIN + 'actualizarOrdenArticulos.php'
				});
			}
	});

		$(".sortable").sortable( "option", "cursorAt", { left: 4, top: 4 } );
		$("ul").droppable();

		$(document)
			.on("click", ".setNuevo", function(){
				var idArticulo = $(this).parent().attr("data");
				$(this).addClass("nuevo");
				$(this).removeClass("setNuevo");
				$(this).parent().find(".oferta").addClass("setOferta").removeClass("oferta");
				$.ajax({
					data: {idArticulo: idArticulo, set: 'nuevo'},
					type: 'POST',
					url: PATH_PHP_ADMIN + 'setOfertaNuevoNormal.php',
					success: function(html)
					{
						console.log(html);
					}
				});
			})
			
			.on("click", ".nuevo", function(){
				var idArticulo = $(this).parent().attr("data");
				$(this).addClass("setNuevo");
				$(this).removeClass("nuevo");
				$.ajax({
					data: {idArticulo: idArticulo, set: 'normal'},
					type: 'POST',
					url: PATH_PHP_ADMIN + 'setOfertaNuevoNormal.php',
					success: function(html)
					{
						console.log(html);
					}
				});
			
				
			})
			
			
			.on("click", ".setOferta", function(){
				var idArticulo = $(this).parent().attr("data");
				$(this).addClass("oferta");
				$(this).removeClass("setOferta");
				$(this).parent().find(".nuevo").addClass("setNuevo").removeClass("nuevo");
				$.ajax({
					data: {idArticulo: idArticulo, set: 'oferta'},
					type: 'POST',
					url: PATH_PHP_ADMIN + 'setOfertaNuevoNormal.php'
				});
			})
			
			
			.on("click", ".oferta", function(){
				var idArticulo = $(this).parent().attr("data");
				$(this).addClass("setOferta");
				$(this).removeClass("oferta");
				$.ajax({
					data: {idArticulo: idArticulo, set: 'normal'},
					type: 'POST',
					url: PATH_PHP_ADMIN + 'setOfertaNuevoNormal.php'
				});
			})
		
		
			.on("change", ".precioMayorista", function(){
				var idArticulo = $(this).parent().parent().attr("data");
				var texto = $(this).val();
				if(texto.charAt(0) == '$')
				{
					texto = texto.substring(1);
				}
				
				/*$("#loading").dialog({ show: 'fold', closeText: "cerrar", create: 
					function() {
					*/
						$.post(PATH_PHP_ADMIN + 'cambiarPrecioArticulo.php', { idArticulo: idArticulo, nuevoPrecio: texto, tipoPrecio: "mayorista"}, function(data){ 
							
							console.log(data);
						})//.dialog("close");
						
					
					/*} 
				});*/
			})
			
			.on("change", ".precioMinorista", function(){
				var idArticulo = $(this).parent().parent().attr("data");
				var texto = $(this).val();
				if(texto.charAt(0) == '$')
				{
					texto = texto.substring(1);
				}
				/*$("#loading").dialog({ show: 'fold', closeText: "cerrar", create: 
					function() {*/
						$.post(PATH_PHP_ADMIN + 'cambiarPrecioArticulo.php', { idArticulo: idArticulo, nuevoPrecio: texto, tipoPrecio: "minorista"}, function(data){ 
							
							console.log(data);
						});
						
					
					/*} 
				});*/
			})
			.on("click", ".btnCerrar", function(){
				if (confirm("¿Está seguro que desea eliminar ese artículo?")){
					var parent = $(this).parent();
					var idArticulo = parent.attr("data");
					$.post(PATH_PHP_ADMIN + 'eliminarArticulo.php', { idArticulo: idArticulo}, function(data){ 
						if(data == true)
						{
							parent.fadeOut();
						}
					});
				}
			})
  });