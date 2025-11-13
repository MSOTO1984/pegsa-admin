function validarCapacitacion() {
    var nomCapacitacion = document.getElementById('nomCapacitacion').value;
    var fecha = document.getElementById('fecha').value;
    var tiempo = document.getElementById('tiempo').value;
    var codTipoCapacitacion = document.getElementById('codTipoCapacitacion').value;
    var codUsuario = document.getElementById('codUsuario').value;
    var codDepto = document.getElementById('codDepto').value;
    var codCiudad = document.getElementById('codCiudad').value;
    var codEstado = document.getElementById('codEstado').value;
    var cod = document.getElementById('codPage').value;

    if (nomCapacitacion === "") {
        alert('El campo Nombre es requerido.');
        return false;
    }

    if (fecha === "") {
        alert('El campo Fecha es requerido.');
        return false;
    }

    if (tiempo === "") {
        alert('El campo tiempo es requerido.');
        return false;
    }

    if (codTipoCapacitacion === "") {
        alert('Recuerde seleccionar un Tipo de Capacitacion');
        return false;
    }

    if (codUsuario === "") {
        alert('Recuerde seleccionar un Capacitador');
        return false;
    }

    if (codDepto === "") {
        alert('El campo Departamento es requerido.');
        return false;
    }

    if (codCiudad === "") {
        alert('El campo Ciudad es requerido.');
        return false;
    }

    if (codEstado === "") {
        alert('Recuerde seleccionar un estado');
        return false;
    }

    accionesCapacitacion(cod);
}

function accionesCapacitacion(cod) {
    try {
        $(document).ready(function () {
            const formData = crearFormData();
            $.ajax({
                url: "app/ajax/capacitacion/capacitacion.php",
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

function crearFormData() {
    var state = document.getElementById('state').value;
    var nomCapacitacion = document.getElementById('nomCapacitacion').value;
    var fecha = document.getElementById('fecha').value;
    var tiempo = document.getElementById('tiempo').value;
    var observacion = document.getElementById('observacion').value;
    var codTipoCapacitacion = document.querySelector('#codTipoCapacitacion').value;
    var codUsuario = document.querySelector('#codUsuario').value;
    var codCiudad = document.getElementById('codCiudad').value;
    var codEstado = document.querySelector('#codEstado').value;

    const formData = new FormData();
    formData.append('state', state);

    formData.append('nomCapacitacion', nomCapacitacion);
    formData.append('fecha', fecha);
    formData.append('tiempo', tiempo);
    formData.append('observacion', observacion);
    formData.append('codTipoCapacitacion', codTipoCapacitacion);
    formData.append('codUsuario', codUsuario);
    formData.append('codCiudad', codCiudad);
    formData.append('codEstado', codEstado);
    if (state === 'Actualizar') {
        formData.append('codCapacitacion', document.getElementById('codCapacitacion').value);
    }
    return formData;
}