$(function() {
    $(".formularioEliminarPedido").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        $.ajax({
            type: 'POST',
            url: post_url,
            data: post_data,
            dataType: "json",
            success: function(registro) {
                alert(registro.texto);
                if (registro.estado == 1)
                {
                    window.location.reload();
                }
            }
        });
        return false;
    });
});

$(function() {
    $(".eliminarPedido").click(function() {
        if (confirm("¿Está seguro que desea borrar este pedido?"))
        {
            $(this).parent(".formularioEliminarPedido").submit();
        }
    });
});

$(function() {
    $(".tipoPersona").change(function() {
        $(".tipoPersona option:selected").each(function() {
            var tipo = $(this).val();
            var dato = $(".buscar").val();
            var estado = $(".tipoPedido option:selected").val();
            window.location.replace(PATH_CONTROLLER + "controladorAdmin.php?seccion=pedidos&tipoPersona=" + tipo + "&estadoPedido=" + estado + "&buscar=" + dato);
        });
    });
});

$(function() {
    $(".tipoPedido").change(function() {
        $(".tipoPedido option:selected").each(function() {
            var estado = $(this).val();
            var dato = $(".buscar").val();
            var tipo = $(".tipoPersona option:selected").val();
            window.location.replace(PATH_CONTROLLER + "controladorAdmin.php?seccion=pedidos&tipoPersona=" + tipo + "&estadoPedido=" + estado + "&buscar=" + dato);
        });
    });
});

$(function() {
    $(".formularioModificarPedido").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        $.ajax({
            type: 'POST',
            url: post_url,
            data: post_data,
            dataType: "json",
            success: function(registro) {
                if (registro.estado == 1)
                {
                    window.location.reload();
                }
            }
        });
        return false;
    });
});

$(function() {
    $(".seleccionEstadoNuevo").change(function() {
        $(".formularioModificarPedido").submit();
    });
});


$(function() {
    $(".formularioEliminarCarrito").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        $.ajax({
            type: 'POST',
            url: post_url,
            data: post_data,
            dataType: "json",
            success: function(registro) {
                if (registro.estado == 1)
                {
                    window.location.reload();
                }
            }
        });
        return false;
    });
});

$(function() {
    $(".eliminarCarrito").click(function() {
        $(this).parent(".formularioEliminarPedido").submit();
    });
});
$(function() {
    $(".inputCantidad").change(function(e) {
        e.preventDefault();
        var value = $(this).val();
        var idTalle = $(this).attr("data");
        console.log($.post(PATH_CONTROLLER + "controladorAdminModel.php", {section: "modificarCarrito", idTalle: idTalle, cant: value}, function(data) {
            window.location.reload();
        }));
    });
});

function validarRegistroPedido()
{
    var nombre = $("input[name='nombre']");
    var apellido = $("input[name='apellido']");
    var email = $("input[name='email']");
    var fecNac = $("input[name='fechaNac']");
    var calle = $("input[name='calle']");
    var numero = $("input[name='numero']");
    var provincia = $(".selectProvincia option:selected");
    var localidad = $(".selectLocalidad option:selected");
    var codPostal = $("input[name='codPostal']");
    var telefono = $("input[name='telefono']");

    var ok = false;

    if (!isNaN(nombre.val()) || (nombre.val() == ""))
    {
        alert("El nombre está vacío o contiene números.");
        nombre.focus();
    }
    else if (!isNaN(apellido.val()) || (apellido.val() == ""))
    {
        alert("El apellido está vacío o contiene números.");
        apellido.focus();
    }
    else if (!isNaN(email.val()) || (email.val() == ""))
    {
        alert("El email ingresado no corresponde a un email válido.");
        email.focus();
    }
    else if (fecNac.val() == "")
    {
        alert("El formato de la fecha ingresada es incorrecto.");
        fecNac.focus();
    }
    else if (calle.val() == "")
    {
        alert("La calle está vacía.");
        calle.focus();
    }
    else if (isNaN(numero.val()) || (numero.val() == ""))
    {
        alert("El número está vacío o contiene letras.");
        numero.focus();
    }
    else if (isNaN(provincia.val()) || (provincia.val() == ""))
    {
        alert("Hubo un error en la provincia.");
        provincia.focus();
    }
    else if (isNaN(localidad.val()) || (localidad.val() == ""))
    {
        alert("Hubo un error en la localidad.");
        localidad.focus();
    }
    else if (isNaN(codPostal.val()) || (codPostal.val() == ""))
    {
        alert("Hubo un error en el código postal.");
        codPostal.focus();
    }
    else if ((telefono.val() == ""))
    {
        alert("Hubo un error en el teléfono.");
        telefono.focus();
    }
    else
    {
        ok = true;
    }

    return ok;
}


$(function() {
    $(".formRegistroPedido").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        if (validarRegistroPedido())
        {
            console.log($.ajax({
                type: 'POST',
                url: post_url,
                data: post_data,
                dataType: "json",
                success: function(registro) {
                    alert(registro.texto);
                    if (registro.estado != -1)
                    {
                        window.location.replace(PATH_HOME);
                    }
                }
            }));
        }
        return false;
    });
});



 