
$(function() {
    $(".tipoUsuario").change(function() {
        $(".tipoUsuario option:selected").each(function() {
            tipo = $(this).val();
            if (tipo == "may")
            {
                $.post(PATH_PHP + "controladorDataMayorista.php", function(data) {
                    $("#datoMayorista").load().html(data);
                });
            }
            else
            {
                $("#datoMayorista").empty();
            }
        });
    });
});

function validarRegistroUsuario()
{
    var nombre = $("input[name='nombre']");
    var apellido = $("input[name='apellido']");
    var email = $("input[name='email']");
    var clave = $("input[name='clave']");
    var confirmClave = $("input[name='confirmClave']");
    var fecNac = $("input[name='fechaNac']");
    var tipoUsuario = $(".tipoUsuario option:selected");

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
    else if (!isNaN(clave.val()) || (clave.val() == ""))
    {
        alert("Hubo un error en la contraseña.");
        clave.focus();
    }
    else if (!isNaN(confirmClave.val()) || (confirmClave.val() == "") || (confirmClave.val() != clave.val()))
    {
        alert("Hubo un error en la confirmación de la contraseña.");
        confirmClave.focus();
    }
    else if (!isNaN(tipoUsuario.val()) || (tipoUsuario.val() == ""))
    {
        alert("Hubo un error en la selección del tipo.");
        tipoUsuario.focus();
    }
    else
    {
        if (tipoUsuario.val() == "may")
        {
            var calle = $("input[name='calle']");
            var numero = $("input[name='numero']");
            var provincia = $(".selectProvincia option:selected");
            var localidad = $(".selectLocalidad option:selected");
            var codPostal = $("input[name='codPostal']");
            var telefono = $("input[name='telefono']");

            var cuit = $("input[name='cuit']");
            var condIva = $(".condIva option:selected");
            var razonSocial = $("input[name='razonSocial']");
            var nombreFantasia = $("input[name='nombreFantasia']");

            var calleFiscal = $("input[name='calleFiscal']");
            var numeroFiscal = $("input[name='numeroFiscal']");
            var provinciaFiscal = $(".selectProvinciaFiscal option:selected");
            var localidadFiscal = $(".selectLocalidadFiscal option:selected");
            var codPostalFiscal = $("input[name='codPostalFiscal']");
            var telefonoFiscal = $("input[name='telefonoFiscal']");

            if (calle.val() == "")
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
            else if (isNaN(cuit.val()) || (cuit.val() == ""))
            {
                alert("Hubo un error en el cuit.");
                cuit.focus();
            }
            else if (isNaN(condIva.val()) || (condIva.val() == "")) {
                alert("Hubo un error en la condición iva.");
                condIva.focus();
            }
            else if ((razonSocial.val() == "")) {
                alert("Hubo un error en la razón social.");
                razonSocial.focus();
            }
            else if ((nombreFantasia.val() == "")) {
                alert("Hubo un error en el nombre de fantasía.");
                nombreFantasia.focus();
            }
            else if (calleFiscal.val() == "")
            {
                alert("La calle fiscal está vacía.");
                calleFiscal.focus();
            }
            else if (isNaN(numeroFiscal.val()) || (numeroFiscal.val() == ""))
            {
                alert("El número fiscal está vacío o contiene letras.");
                numeroFiscal.focus();
            }
            else if (isNaN(provinciaFiscal.val()) || (provinciaFiscal.val() == ""))
            {
                alert("Hubo un error en la provincia fiscal.");
                provinciaFiscal.focus();
            }
            else if (isNaN(localidadFiscal.val()) || (localidadFiscal.val() == ""))
            {
                alert("Hubo un error en la localidad fiscal.");
                localidad.focus();
            }
            else if (isNaN(codPostalFiscal.val()) || (codPostalFiscal.val() == ""))
            {
                alert("Hubo un error en el código postal fiscal.");
                codPostalFiscal.focus();
            }
            else if ((telefonoFiscal.val() == ""))
            {
                alert("Hubo un error en el teléfono fiscal.");
                telefonoFiscal.focus();
            }
            else
            {
                ok = true;
            }
        }
        else
        {
            ok = true;
        }

    }

    return ok;
}


$(function() {
    $(".formRegistroUsuario").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();
        var contin = $(".continue").val();
        var regis = $(".reg").val();

        if (validarRegistroUsuario())
        {
            console.log($.ajax({
                type: 'POST',
                url: post_url,
                data: post_data,
                dataType: "json",
                success: function(registro) {
                    alert(registro.texto);
                    if (registro.estado == 1)
                    {

                        if (contin != "")
                        {
                            con = contin;
                        }
                        else
                        {
                            con = "miPerfil";
                        }

                        console.log($.post(PATH_CONTROLLER + "controladorAdminModel.php", {user: registro.usuario, pass: registro.clave, section: "inicio", continue: con, registroLogin: "si", reg: regis}, function(url) {
                            window.location.replace(url);
                        }, "json"));
                    }
                }
            }));
        }

        return false;
    });
});

function validarRegistroUsuarioModificaciones()
{
    var nombre = $("input[name='nombre']");
    var apellido = $("input[name='apellido']");
    var email = $("input[name='email']");
    var tipoUsuario = $("input[name='tipoUsuario']");

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
    else if (!isNaN(tipoUsuario.val()) || (tipoUsuario.val() == ""))
    {
        alert("Hubo un error en la selección del tipo.");
        tipoUsuario.focus();
    }
    else
    {
        if (tipoUsuario.val() == "may")
        {
            var fecNac = $("input[name='fechaNac']");
            var calle = $("input[name='calle']");
            var numero = $("input[name='numero']");
            var provincia = $(".selectProvincia option:selected");
            var localidad = $(".selectLocalidad option:selected");
            var codPostal = $("input[name='codPostal']");
            var telefono = $("input[name='telefono']");

            var cuit = $("input[name='cuit']");
            var condIva = $(".condIva option:selected");
            var razonSocial = $("input[name='razonSocial']");
            var nombreFantasia = $("input[name='nombreFantasia']");

            var calleFiscal = $("input[name='calleFiscal']");
            var numeroFiscal = $("input[name='numeroFiscal']");
            var provinciaFiscal = $(".selectProvinciaFiscal option:selected");
            var localidadFiscal = $(".selectLocalidadFiscal option:selected");
            var codPostalFiscal = $("input[name='codPostalFiscal']");
            var telefonoFiscal = $("input[name='telefonoFiscal']");

            if (calle.val() == "")
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
            else if (isNaN(cuit.val()) || (cuit.val() == ""))
            {
                alert("Hubo un error en el cuit.");
                cuit.focus();
            }
            else if (isNaN(condIva.val()) || (condIva.val() == "")) {
                alert("Hubo un error en la condición iva.");
                condIva.focus();
            }
            else if ((razonSocial.val() == "")) {
                alert("Hubo un error en la razón social.");
                razonSocial.focus();
            }
            else if ((nombreFantasia.val() == "")) {
                alert("Hubo un error en el nombre de fantasía.");
                nombreFantasia.focus();
            }
            else if (calleFiscal.val() == "")
            {
                alert("La calle fiscal está vacía.");
                calleFiscal.focus();
            }
            else if (isNaN(numeroFiscal.val()) || (numeroFiscal.val() == ""))
            {
                alert("El número fiscal está vacío o contiene letras.");
                numeroFiscal.focus();
            }
            else if (isNaN(provinciaFiscal.val()) || (provinciaFiscal.val() == ""))
            {
                alert("Hubo un error en la provincia fiscal.");
                provinciaFiscal.focus();
            }
            else if (isNaN(localidadFiscal.val()) || (localidadFiscal.val() == ""))
            {
                alert("Hubo un error en la localidad fiscal.");
                localidad.focus();
            }
            else if (isNaN(codPostalFiscal.val()) || (codPostalFiscal.val() == ""))
            {
                alert("Hubo un error en el código postal fiscal.");
                codPostalFiscal.focus();
            }
            else if ((telefonoFiscal.val() == ""))
            {
                alert("Hubo un error en el teléfono fiscal.");
                telefonoFiscal.focus();
            }
            else
            {
                ok = true;
            }
        }
        else
        {
            ok = true;
        }

    }

    return ok;
}

$(function() {
    $(".formRegistroModificado").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        if (validarRegistroUsuarioModificaciones())
        {
            $.ajax({
                type: 'POST',
                url: post_url,
                data: post_data,
                dataType: "json",
                success: function(registro) {
                    console.log("registro" + "HOLA");
                    alert(registro.texto);
                    if (registro.estado == 1)
                    {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });
});

/*
 * SECCIÓN DE CLIENTES DESDE LISTADO CLIENTES
 * 
 */

$(function() {
    $(".formularioEliminarCliente").submit(function(e) {
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
    $(".eliminar").click(function() {
        if (confirm("¿Está seguro que desea eliminar este cliente?"))
        {
            $(this).parent(".formularioEliminarCliente").submit();
        }
    });
});

function validarRegistroCliente()
{
    var nombre = $("input[name='nombre']");
    var apellido = $("input[name='apellido']");
    var email = $("input[name='email']");
    var telefono = $("input[name='telefono']");

    var ok = false;

    if (!isNaN(nombre.val()) || (nombre.val() == "nombre"))
    {
        alert("El nombre está vacío o contiene números.");
        nombre.focus();
    }
    else if (!isNaN(apellido.val()) || (apellido.val() == "apellido"))
    {
        alert("El apellido está vacío o contiene números.");
        apellido.focus();
    }
    else if (!isNaN(email.val()) || (email.val() == "email"))
    {
        alert("El email ingresado no corresponde a un email válido.");
        email.focus();
    }
    else if ((telefono.val() == "teléfono"))
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
    $(".formularioAgregarCliente").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        if (validarRegistroCliente())
        {
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
        }
        return false;
    });
});



$(function() {
    $(".formularioSecurityQuestions").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

        console.log($.ajax({
            type: 'POST',
            url: post_url,
            data: post_data,
            dataType: "json",
            success: function(registro) {
                alert(registro.texto);
                if (registro.estado == 1)
                {
                    window.location.replace(registro.continue);
                }
            }
        }));
        return false;
    });
});

$(function() {
    $(".formularioOlvideClave").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var post_url = form.attr("action");
        var post_data = form.serialize();

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
        return false;
    });
});