

$(function() {
    $(".loginHeader").submit(function(e) {
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
                if (registro.error)
                {
                    alert(registro.texto);
                }
                else
                {
                    if (registro.contin != "")
                    {
                        window.location.replace(registro.contin);
                    }
                    else
                    {
                        window.location.reload();
                    }
                }
            }
        }));
        return false;
    });
});

$(document).ready(function() {
    $("#ventanaLogin").hide();
    $("#ventanaCarrito").hide();
});

$(function() {
    $(".btnLoginHeader").click(function() {
        $("#ventanaLogin").show();
    });
});

$(function() {
    $(".cerrarEmergente").click(function() {
        $(this).parent("div").hide();
    });
});

