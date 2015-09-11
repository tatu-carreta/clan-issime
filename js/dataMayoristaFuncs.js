$(function() {
    $(".selectProvincia").change(function() {
        $(".selectProvincia option:selected").each(function() {
            idProv = $(this).val();
            $.post(PATH_PHP_ADMIN + "listaLocalidades.php", {idProvincia: idProv}, function(data) {
                $(".selectLocalidad").html(data);
                $(".selectLocalidad").attr("disabled", false);
            });
        });
    });
});

$(function() {
    $(".selectProvinciaFiscal").change(function() {
        $(".selectProvinciaFiscal option:selected").each(function() {
            idProv = $(this).val();
            $.post(PATH_PHP_ADMIN + "listaLocalidades.php", {idProvincia: idProv}, function(data) {
                $(".selectLocalidadFiscal").html(data);
                $(".selectLocalidadFiscal").attr("disabled", false);
            });
        });
    });
});