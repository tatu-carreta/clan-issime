<?php
	require ("config.php"); 
	require ("funciones.php"); 

	
	
	?><div class="triang"></div>
    <a class="btnCerrarGris cerrarEmergente"><span>Cerrar</span></a>
    <img class="imgArtPedido" src="<?php echo PATH_IMAGES_CATALOGO.$_GET['img']?>" alt="">
    <p>Agregaste a carrito<br>
    <span><?php echo $_GET['nombre']?></span><br>
    <span><strong>$<?php echo $_GET['precio']?></strong></span></p>
    <a href="<?php if($localhost) {echo  PATH_CONTROLLER."controladorAdmin.php?seccion=carrito";}else{ if(isset($_SESSION['iderUser']) && ($_SESSION['iderUser'] != "")){ echo PATH_HOME."micarrito/carrito-paso-1/".$_SESSION['iderUser'];}else{echo PATH_HOME."micarrito/carrito-paso-1";}} ?>" class="btnGris" >Ver Carrito</a>
	<?php
?>