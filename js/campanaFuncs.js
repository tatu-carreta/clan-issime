$(document).ready( function(){

	$(document).on("click", ".advertencia", function(e){
		e.preventDefault();
		var idCampana = $(this).attr("data");
		if (confirm("Si crea una nueva campaña, los artículos de las campañas anteriores se archivarán y se limpiará el catalogo. ¿Desea continuar?"))
		{
			$.ajax({
						type: 'POST',
						url: PATH_PHP_ADMIN + 'eliminarCampana.php',
						success: function(html){
							if(html){
							$("#formCampana").submit();
							}
						}
					});
			
		}
	});

});