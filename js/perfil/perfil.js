function validarPerfil() {

    var cod = document.getElementById('codPage').value;
    var nomPerfil = document.getElementById('nomPerfil').value;
    var cadenaFinal = crearCadenaFinal();

    if (nomPerfil === "") {
        alert('El campo Nombre es requerido.');
        return false;
    }

    if (cadenaFinal === "") {
        alert('Debe seleccionar como minimo un permiso para el perfil');
        return false;
    }
    accionesPerfil(cod, nomPerfil, cadenaFinal);
}

function accionesPerfil(cod, nomPerfil, cadenaFinal) {
    try {
        $(document).ready(function () {
            const formData = crearFormData(nomPerfil, cadenaFinal);
            $.ajax({
                url: "app/ajax/perfil/perfil.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'index.php?cod=' + cod;
                    }
                },
                error: function (objeto, error, otroobj) {
                    console.log(objeto);
                    console.log(error);
                    console.log(otroobj);
                }
            });
        });
    } catch (e) {
        console.log(e.message);
    }
}

function crearFormData(nomPerfil, cadenaFinal) {
    var state = document.getElementById('state').value;
    var codPerfil = document.getElementById('codPerfil').value;
    const formData = new FormData();
    formData.append('state', state);
    formData.append('codPerfil', codPerfil);
    formData.append('nomPerfil', nomPerfil);
    formData.append('cadena', cadenaFinal);
    return formData;
}

function crearCadenaFinal() {
    var cadenaFinal = '';
    var options = document.getElementById('options').value;
    var cadena = options.slice(0, -1);
    var arrayDeSubcadenas = cadena.split(',');
    arrayDeSubcadenas.forEach(function (elemento) {
        var codOption = document.getElementById('codOption' + elemento).checked;
        var valor = document.getElementById('codOption' + elemento).value;
        console.log('codOption' + elemento + ' : ' + codOption + ": " + valor);
        if (codOption) {
            cadenaFinal += valor + ',';
        }
    });
    return cadenaFinal.slice(0, -1);
}