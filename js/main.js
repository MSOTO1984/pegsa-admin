function getListaCiudades() {
    var codDepto = $("#codDepto");
    ajaxConsultas("codCiudad", "ajax_lista_ciudades", "app/ajax/ajax_consultas.php", "codDepto", codDepto.val());
}

function ajaxConsultas(campo, selector, url, llave = "", vLlave = "", option = null) {
    try {
        var params = "selector=" + selector;
        if (llave && vLlave) {
            params += "&" + llave + "=" + vLlave;
        }

        $(document).ready(function () {
            $.ajax({
                url: url + "?" + params,
                async: true,
                type: "GET",
                success: function (datos) {
                    llenarLista(datos, campo, option);
                },
                complete: function (objeto, exito) {
                    console.log(objeto);
                    console.log(exito);
                },
                contentType: "application/json; charset=ISO-8859-1",
                dataType: "json",
                error: function (objeto, error, otroobj) {
                    console.log(objeto);
                    console.log(error);
                    console.log(otroobj);
                }
            });
        });
    } catch (e) {
        console.log(e.messag);
}
}

function llenarLista(datos, idField, option) {
    try {
        var sel = $("#" + idField);
        sel.empty();
        sel.append('<option value="">Selecciona una opción</option>');
        for (var i = 0; i < datos.length; i++) {
            var selected = '';
            if (option === datos[i].value) {
                selected = ' selected="selected"';
            }
            sel.append('<option value="' + datos[i].value + '"' + selected + '>' + datos[i].label + '</option>');
        }
    } catch (e) {
        alert("Error " + e.message);
    }
}