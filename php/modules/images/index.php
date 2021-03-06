<script src="{{ paths.PATH_PHP_MODULES_IMAGES }}jqFuncs.js"></script>
<section>
    <h2>Cargar y editar artículo</h2>
    <!-- div detalle del artículo -->
    <div class="contentDatosArticulo">
        <form id ="formArticulo" action="" method="">
            <h3>Pantalones <span class="negro">Artículo:</span> <input type="text" name="codigo" class="editable sinBorde nombreArticulo" value="00412503gaba4"> </h3>
            <div class="muestraArt">
                <a href="#"><img class="portada" src="{{ paths.PATH_IMAGES }}permanentes/sinfoto.jpg" alt="Clan Issime"></a>
                <a class="nuevo" href="#"><span>nuevo</span></a>
                <a class="oferta" href="#"><span>nuevo</span></a>
            </div>
            <input type="file" accept="image/*" id="imgDestacada" name="imgDestacada" style="display:none">
            <div class="datosArticulo">

                <table>
                    <tr>
                        <td>
                            <p>Nombre: <input name="nombre" class="editable sinBorde" value="Capri crimo"/></p>
                            <p>Material: <input name="material" class="editable sinBorde" value="gabardina"/></p>
                            <p>Precio mayorista: <input name="precioMayorista" class="editable sinBorde" value="$199"/></p>
                            <p>Precio minorista: <input name="precioMinorista" class="editable sinBorde" value="$379"/></p>
                        </td>
                        <td>
                            talles
                        </td>
                        <td>
                            <p><input type="radio" class="tipoDeTalles" name="talles" value="1" id="t01" checked="checked" />
                                <label for="t01">01 al 04</label></p>

                            <p><input type="radio" class="tipoDeTalles" name="talles" value="2" id="t40"/>
                                <label for="t40">40 al 48</label></p>

                            <p><input type="radio" class="tipoDeTalles" name="talles" value="3" id="t22"/>
                                <label for="t22">22 al 26</label></p>

                            <p><input type="radio" class="tipoDeTalles" name="talles" value="4" id="cd" />
                                <label for="cd">calzado dama</label></p>

                            <p><input type="radio" class="tipoDeTalles" name="talles" value="5" id="rn"/>
                                <label for="rn">ropa niños</label></p>

                            <p><input type="radio" class="tipoDeTalles" name="talles" value="6" id="cn" />
                                <label for="cn">calzado niños</label></p>
                        </td>
                        <td>
                            estado
                        </td>
                        <td>
                            <p><input type="radio" name="estado" id="nuevo" value="N" checked="checked" />
                                <label for="nuevo">nuevo</label></p>

                            <p><input type="radio" name="estado" id="oferta" value="O" />
                                <label for="oferta">oferta</label></p>

                            <p><input type="radio" name="estado" id="normal" value="R" />
                                <label for="normal">normal</label></p>
                        </td>
                    </tr>
                </table>
        </form>
    </div>
    <div class="clear"></div>
</div><!-- cierra div detalle del artículo -->

<!-- abre detalles de colores -->
<div id="uploader" class="contentAgregarColores">
    <h4>Colores de este artículo</h4>
    <a id="agregarColor" href="#">Agregar color</a>
    <div class="contentColores">
        <!--<div class="cadaColor dropzone" data="'0'"  > 
                <ul class="list listaImgCadaColor">
                        <li><a class="agregarFoto" href="#">Agregar foto</a>	<input type="file" accept="image/*" class="imgColor" name="imgColor" style="display:none"> </li>
                        
                </ul>
                <div class="checkers">
                        <label for="t01" class="labelTalle">01</label>
                        <input class="check" type="checkbox" name="01" id="t01">
                        <label for="t01" class="labelTalle active">02</label>
                        <input class="check" type="checkbox" name="02" id="t01">
                        <label for="t01" class="labelTalle">03</label>
                        <input class="check" type="checkbox" name="03" id="t01">
                        <label for="t01" class="labelTalle">04</label>
                        <input class="check" type="checkbox" name="04" id="t01">
                </div>
                <p class="nombreColor">Nombre color: <span class="editable">estampado rojo</span></p>
        </div>-->
        <div class="clear"></div>
        <input id="publicar" class="btnCeleste marginRight15" value="Publicar"/>	
    </div>
<div class="clear"></div>

</div>
<!-- cierra detalles de colores -->


<div id="loading"><span>Por favor espere mientras las imágenes se cargan.</span><br><img src="{{ paths.PATH_PHP_MODULES_IMAGES }}loading.gif" /></div>
</section>

