$(document).ready( function(){
	arrayTalles = [];
	//$(".textoTalle").numeric();
	var colorActivo = $(".coloresLi").first();
	colorActivo.addClass("colorActivo");
	var idColor = colorActivo.attr("id");
	$("#talles-"+idColor).addClass("visible");
	
	$(document).on("click", ".coloresLi", function(){
		$(".coloresLi").removeClass("colorActivo");
		var idColor = $(this).attr("id");
		$(".contentCheckTallesInvisible").removeClass("visible");
		$(this).addClass("colorActivo");
		$("#talles-"+idColor).addClass("visible");

	})
	.on("click", ".actualizarCarrousel", function(){
		var idColor = $(this).attr("id");
		$(".carrouselColores").addClass("invisible");
		$("."+idColor).removeClass("invisible");
	});
	
	$("#ventanaCarrito").hide();
	$("#ventanaLogin").hide();
	$(document).on("click", ".cerrarEmergente", function(e){
		e.preventDefault();
		$("#ventanaCarrito").fadeOut();
		$("#ventanaLogin").fadeOut();
	});
	$(document)
	
		.on("click", ".agregarMinorista", function(){
			$(this).addClass("active");

			$(this).removeClass("agregarMinorista");
			var idTalle = $(this).find(".checkTalle").val();
			
			var i = parseInt(idTalle.replace(/\D/g, ''), 10);
			arrayTalles.push({idTalle: i, cantidad: 1});
			
			
		})
		
		.on("click", ".agregarMayorista", function(){
			$(this).addClass("active2");
			var idTalle = $(this).find(".checkTalle").val();
			var campo = $(this).parent().find(".textoTalle");
			var cantidad = campo.val();
			var i = parseInt(idTalle.replace(/\D/g, ''), 10);
			var cant = parseInt(cantidad.replace(/\D/g, ''), 10);
			cant ++;
			campo.val(cant);
			
			var flag = false;
			$.each(arrayTalles, function(index){
				if( this.idTalle == i)
				{
					this.cantidad = cant;
					flag = true;
				}
			})
			
			if(!flag)
			{
				arrayTalles.push({idTalle: i, cantidad: cant});
			}
			
			console.log(arrayTalles);
		})
		
		
		.on("click", ".active", function(){
			$(this).addClass("agregarMinorista");
			$(this).removeClass("active");
			var idTalle = $(this).find(".checkTalle").val();
			var i = parseInt(idTalle.replace(/\D/g, ''), 10);
			$.each(arrayTalles, function(index){
				if( this.idTalle == i)
				{
					arrayTalles.splice(index,1);
				}
			})
		}).on("keyup", ".textoTalle", function(){
			var idTalle = $(this).parent().find(".checkTalle").val();
			var cantidad =$(this).val();
			var i = parseInt(idTalle.replace(/\D/g, ''), 10);
			var cant = parseInt(cantidad.replace(/\D/g, ''), 10);
			if( cantidad > 0)
			{
				$(this).parent().find(".agregarMayorista").addClass("active2");
				var flag = false;
				$.each(arrayTalles, function(index){
					if( this.idTalle == i)
					{
						this.cantidad = cant;
						flag = true;
					}
				})
				
				if(!flag)
				{
					arrayTalles.push({idTalle: i, cantidad: cant});
				}
			}
			else
			{
				$(this).parent().find(".agregarMayorista").removeClass("active2");
				$.each(arrayTalles, function(index){
					if( this.idTalle == i)
					{
						arrayTalles.splice(index,1);
					}
				})
			}
			
			
			console.log(arrayTalles);
		
		})
		.on("click", "#agregarAlCarrito", function(){
			if(arrayTalles.length == 0)
			{
				alert("No ha seleccionado ningún talle.");
			}
			else
			{
			
			
				$.each(arrayTalles, function(index){
					$.post( PATH_CONTROLLER + 'controladorAdminModel.php',{section: "agregarCarrito", idTalle: arrayTalles[index].idTalle, cant:arrayTalles[index].cantidad}, function(data){
					
									
						var arrayDatos = data.datos;
						
						var precio;
						if(tipoUsuario == 2)
						{
							precio = arrayDatos.precioMay;
						}
						else
						{
							precio = arrayDatos.precioMin;
						}
						$("#ventanaCarrito").fadeIn();
						$("#ventanaCarrito").load(PATH_PHP+"renderMiniaturaCarrito.php?nombre="+encodeURIComponent(arrayDatos.nombreArticulo)+"&img="+arrayDatos.nombreImagen+"&precio="+precio, function(dat){
							console.log(dat);
						});
						
						
					}, 'json');
				})
			}
		});
		
	
});