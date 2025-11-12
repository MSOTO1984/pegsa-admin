function validarUsuario() {

    var codUsuario = document.getElementById('codUsuario').value;
    var nomUsuario = document.getElementById('nomUsuario').value;
    var emailUsuario = document.getElementById('emailUsuario').value;
    var celUsuario = document.getElementById('celUsuario').value;
    var codPerfil = document.getElementById('codPerfil').value;
    var codGenero = document.getElementById('codGenero').value;
    var codEstado = document.getElementById('codEstado').value;
    var direccion = document.getElementById('direccion').value;
    var codDepto = document.getElementById('codDepto').value;
    var codCiudad = document.getElementById('codCiudad').value;
    var cod = document.getElementById('codPage').value;

    if (codUsuario === "") {
        alert('El campo Número de documento es requerido.');
        return false;
    }

    if (nomUsuario === "") {
        alert('El campo Nombre es requerido.');
        return false;
    }

    if (emailUsuario === "") {
        alert('El campo Email es requerido.');
        return false;
    }

    if (celUsuario === "") {
        alert('El campo Celular es requerido.');
        return false;
    }

    if (codPerfil === "") {
        alert('Recuerde seleccionar un codigo de perfil');
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

    accionesUsuario(cod);
}

function accionesUsuario(cod) {
    try {
        $(document).ready(function () {
            const formData = crearFormData();
            $.ajax({
                url: "app/ajax/usuario/usuario.php",
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
    var codUsuario = document.getElementById('codUsuario').value;
    var nomUsuario = document.getElementById('nomUsuario').value;
    var emailUsuario = document.getElementById('emailUsuario').value;
    var celUsuario = document.getElementById('celUsuario').value;
    var codPerfil = document.querySelector('#codPerfil').value;
    var codGenero = document.querySelector('#codGenero').value;
    var codEstado = document.querySelector('#codEstado').value;
    var direccion = document.getElementById('direccion').value;
    var codCiudad = document.getElementById('codCiudad').value;

    const formData = new FormData();
    formData.append('state', state);
    formData.append('codUsuario', codUsuario);
    formData.append('nomUsuario', nomUsuario);
    formData.append('emailUsuario', emailUsuario);
    formData.append('celUsuario', celUsuario);
    formData.append('codPerfil', codPerfil);
    formData.append('codEstado', codEstado);
    formData.append('codGenero', codGenero);
    formData.append('direccion', direccion);
    formData.append('codCiudad', codCiudad);
    return formData;
}

function accionCambioClave() {
    if (validarClave()) {
        accionesCambioContrasena();
    }
}

function validarClave() {
    var claveUsuario = document.getElementById('claveUsuario').value;
    var claveUsuario2 = document.getElementById('claveUsuario2').value;
    if (claveUsuario !== '' && claveUsuario2 !== '') {
        if (claveUsuario !== claveUsuario2) {
            alert('Error en coincidencia de contraseñas');
            return false;
        } else {
            return true;
        }
    }
    return false;
}

function accionesCambioContrasena() {
    try {
        $(document).ready(function () {
            const formData = crearFormData2();
            $.ajax({
                url: "app/ajax/usuario/usuario.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'index.php';
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

function crearFormData2() {
    var state = document.getElementById('state').value;
    var claveUsuario = document.getElementById('claveUsuario').value;
    const formData = new FormData();
    formData.append('state', state);
    formData.append('claveUsuario', claveUsuario);
    return formData;
}

function validarRegistro() {

    var codUsuario = document.getElementById('codUsuario').value;
    var nomUsuario = document.getElementById('nomUsuario').value;
    var emailUsuario = document.getElementById('emailUsuario').value;

    if (codUsuario === "") {
        alert('El campo Número de documento es requerido.');
        return false;
    }

    if (nomUsuario === "") {
        alert('El campo Nombre es requerido.');
        return false;
    }

    if (emailUsuario === "") {
        alert('El campo Email es requerido.');
        return false;
    }

    accionesRegistroUsuario();
}

function accionesRegistroUsuario() {
    try {
        $(document).ready(function () {
            const formData = crearFormData3();
            $.ajax({
                url: "app/ajax/usuario/usuario.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'index.php';
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

function crearFormData3() {
    var state = document.getElementById('state').value;
    var codUsuario = document.getElementById('codUsuario').value;
    var nomUsuario = document.getElementById('nomUsuario').value;
    var emailUsuario = document.getElementById('emailUsuario').value;
    const formData = new FormData();
    formData.append('state', state);
    formData.append('codUsuario', codUsuario);
    formData.append('nomUsuario', nomUsuario);
    formData.append('emailUsuario', emailUsuario);
    return formData;
}

function validarRecuperacion() {
    var emailUsuario = document.getElementById('emailUsuario').value;
    if (emailUsuario === "") {
        alert('El campo Email es requerido.');
        return false;
    }
    accionesRecuperacion();
}

function accionesRecuperacion() {
    try {
        $(document).ready(function () {
            const formData = crearFormData4();
            $.ajax({
                url: "app/ajax/usuario/usuario.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'index.php';
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

function crearFormData4() {
    var state = document.getElementById('state').value;
    var emailUsuario = document.getElementById('emailUsuario').value;
    const formData = new FormData();
    formData.append('state', state);
    formData.append('emailUsuario', emailUsuario);
    return formData;
}

function validarIngreso() {

    var emailUsuario = document.getElementById('emailUsuario').value;
    var password = document.getElementById('password').value;

    if (emailUsuario === "") {
        alert('El campo Email es requerido.');
        return false;
    }

    if (password === "") {
        alert('El campo Contraseña es requerido.');
        return false;
    }

    accionesIngresoUsuario(emailUsuario, password);
}

function accionesIngresoUsuario(emailUsuario, password) {
    try {
        $(document).ready(function () {
            const formData = crearFormData5(emailUsuario, password);
            $.ajax({
                url: "app/ajax/usuario/usuario.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        window.location.href = 'index.php';
                    } else if (data.status === 'WRONG') {
                        alert(data.mensaje);
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

function crearFormData5(emailUsuario, password) {
    var state = document.getElementById('state').value;
    const formData = new FormData();
    formData.append('state', state);
    formData.append('emailUsuario', emailUsuario);
    formData.append('claveUsuario', password);
    return formData;
}