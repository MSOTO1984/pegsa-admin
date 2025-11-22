function validarEvaluacion() {
    var codTipoEvaluacion = document.getElementById('codTipoEvaluacion').value;
    var codEvaluacion = document.getElementById('codEvaluacion').value;
    var ordenEvaluacion = document.getElementById('ordenEvaluacion').value;
    var fechaLimite = document.getElementById('fechaLimite').value;
    var codEstado = document.getElementById('codEstadoCE').value;
    var cod = document.getElementById('codPage').value;

    if (codTipoEvaluacion === "") {
        alert('Recuerde seleccionar un tipo de evaluacion.');
        return false;
    }

    if (codEvaluacion === "") {
        alert('Recuerde seleccionar una evaluacion.');
        return false;
    }

    if (ordenEvaluacion === "") {
        alert('El campo Orden es requerido.');
        return false;
    }

    if (fechaLimite === "") {
        alert('El campo Fecha Limite es requerido.');
        return false;
    }

    if (codEstado === "") {
        alert('Recuerde seleccionar un Estado');
        return false;
    }

    accionEvaluacion(cod);
}

function accionEvaluacion(cod) {
    try {
        $(document).ready(function () {
            const formData = crearFormEvaluacion();
            $.ajax({
                url: "app/ajax/evaluacion/evaluacion.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'index.php?cod=' + cod + '&state=Editar&codCapacitacion=' + data.codCapacitacion;
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

function crearFormEvaluacion() {
    var state = document.getElementById('state2').value;
    var codCapacitacion = document.getElementById('codCapacitacionCE').value;
    var codEvaluacion = document.getElementById('codEvaluacion').value;
    var ordenEvaluacion = document.getElementById('ordenEvaluacion').value;
    var fechaLimite = document.getElementById('fechaLimite').value;
    var esObligatoria = document.querySelector('input[name="esObligatoria"]:checked').value;
    var codEstado = document.getElementById('codEstadoCE').value;

    const formData = new FormData();
    formData.append('state', state);
    formData.append('codCapacitacion', codCapacitacion);
    formData.append('codEvaluacion', codEvaluacion);
    formData.append('ordenEvaluacion', ordenEvaluacion);
    formData.append('fechaLimite', fechaLimite);
    formData.append('esObligatoria', esObligatoria);
    formData.append('codEstado', codEstado);
    return formData;
}

function verificarRespuestasDiligenciadas() {
    respuestasDiligenciadas();
}

function respuestasDiligenciadas() {
    try {
        $(document).ready(function () {
            const formData = crearFormRespuestas();
            $.ajax({
                url: "app/ajax/evaluacion/evaluacion.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'evaluacion.php?codCapacitacion=' + data.codCapacitacion + '&codEmpleado=' + data.codEmpleado;
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

function crearFormRespuestas() {
    var state = document.getElementById('state').value;
    var codEvaluacion = document.getElementById('codEvaluacion').value;
    var codCapacitacion = document.getElementById('codCapacitacion').value;
    var codEmpleado = document.getElementById('codEmpleado').value;
    const formData = new FormData();
    formData.append('state', state);
    formData.append('codEvaluacion', codEvaluacion);
    formData.append('codCapacitacion', codCapacitacion);
    formData.append('codEmpleado', codEmpleado);
    return formData;
}
