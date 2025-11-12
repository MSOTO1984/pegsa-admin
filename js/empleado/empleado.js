function validarEmpleado() {

    var codEmpleado = document.getElementById('codEmpleado').value;
    var nomEmpleado = document.getElementById('nomEmpleado').value;
    var emailEmpleado = document.getElementById('emailEmpleado').value;
    var celEmpleado = document.getElementById('celEmpleado').value;
    var codGenero = document.getElementById('codGenero').value;
    var codEstado = document.getElementById('codEstado').value;
    var direccion = document.getElementById('direccion').value;
    var codDepto = document.getElementById('codDepto').value;
    var codCiudad = document.getElementById('codCiudad').value;
    var cod = document.getElementById('codPage').value;

    if (codEmpleado === "") {
        alert('El campo Número de documento es requerido.');
        return false;
    }

    if (nomEmpleado === "") {
        alert('El campo Nombre es requerido.');
        return false;
    }

    if (emailEmpleado === "") {
        alert('El campo Email es requerido.');
        return false;
    }

    if (celEmpleado === "") {
        alert('El campo Celular es requerido.');
        return false;
    }

    if (codGenero === "") {
        alert('Recuerde seleccionar un genero');
        return false;
    }

    if (codEstado === "") {
        alert('Recuerde seleccionar un estado');
        return false;
    }

    if (direccion === "") {
        alert('El campo Dirección es requerido.');
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

    accionesEmpleado(cod);
}

function accionesEmpleado(cod) {
    try {
        $(document).ready(function () {
            const formData = crearFormData();
            $.ajax({
                url: "app/ajax/empleado/empleado.php",
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
    var codEmpleado = document.getElementById('codEmpleado').value;
    var nomEmpleado = document.getElementById('nomEmpleado').value;
    var emailEmpleado = document.getElementById('emailEmpleado').value;
    var celEmpleado = document.getElementById('celEmpleado').value;
    var codGenero = document.querySelector('#codGenero').value;
    var codEstado = document.querySelector('#codEstado').value;
    var direccion = document.getElementById('direccion').value;
    var codCiudad = document.getElementById('codCiudad').value;

    const formData = new FormData();
    formData.append('state', state);
    formData.append('codEmpleado', codEmpleado);
    formData.append('nomEmpleado', nomEmpleado);
    formData.append('emailEmpleado', emailEmpleado);
    formData.append('celEmpleado', celEmpleado);
    formData.append('codEstado', codEstado);
    formData.append('codGenero', codGenero);
    formData.append('direccion', direccion);
    formData.append('codCiudad', codCiudad);
    return formData;
}