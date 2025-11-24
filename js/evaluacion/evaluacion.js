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
    if (!validarPreguntas()) {
        return false;
    }

    respuestasDiligenciadas();
    return false;
}

function validarPreguntas() {

    let valido = true;
    let primeraPreguntaErronea = null;

    document.querySelectorAll(".has-error").forEach(el => el.classList.remove("has-error"));
    document.querySelectorAll(".text-red").forEach(el => el.classList.remove("text-red"));
    document.querySelectorAll(".form-group").forEach(grupo => {

        let error = false;

        const inputTexto = grupo.querySelector("input[type='text']");
        if (inputTexto && inputTexto.value.trim() === "") {
            error = true;
        }

        const radios = grupo.querySelectorAll("input[type='radio']");
        if (radios.length > 0) {
            let seleccionado = false;
            radios.forEach(r => {
                if (r.checked)
                    seleccionado = true;
            });
            if (!seleccionado)
                error = true;
        }

        const checks = grupo.querySelectorAll("input[type='checkbox']");
        if (checks.length > 0) {
            let alguno = false;
            checks.forEach(c => {
                if (c.checked)
                    alguno = true;
            });
            if (!alguno)
                error = true;
        }

        if (error) {
            grupo.classList.add("has-error");
            grupo.querySelectorAll("b, label, span, p").forEach(el => {
                el.classList.add("text-red");
            });

            if (!primeraPreguntaErronea) {
                primeraPreguntaErronea = grupo;
            }

            valido = false;
        }
    });

    if (!valido) {
        alert("Debe completar todas las preguntas.");
        primeraPreguntaErronea.scrollIntoView({behavior: "smooth", block: "center"});
        return false;
    }

    return true;
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
    const formData = new FormData();
    formData.append('state', document.getElementById('state').value);
    formData.append('codEvaluacion', document.getElementById('codEvaluacion').value);
    formData.append('codCapacitacion', document.getElementById('codCapacitacion').value);
    formData.append('codEmpleado', document.getElementById('codEmpleado').value);
    document.querySelectorAll("input").forEach(input => {
        if (input.type === "radio" && input.checked) {
            formData.append(input.name, input.value);
        } else if (input.type === "checkbox" && input.checked) {
            formData.append(input.name, input.value);
        } else if (input.type === "text" && input.value.trim() !== "") {
            formData.append(input.name, input.value.trim());
        }
    });
    return formData;
}


function validarEvaluacionCrear() {

    var nomEvaluacion = document.getElementById('nomEvaluacion').value;
    var notaMaxima = document.getElementById('notaMaxima').value;
    var codTipoEvaluacion = document.getElementById('codTipoEvaluacion').value;
    var codEstado = document.getElementById('codEstado').value;
    var descripcion = document.getElementById('descripcion').value;
    var cod = document.getElementById('codPage').value;

    if (nomEvaluacion === "") {
        alert('El campo Evaluacion es requerido.');
        return false;
    }

    if (notaMaxima === "") {
        alert('El campo Nota Maxima es requerido.');
        return false;
    }

    if (codTipoEvaluacion === "") {
        alert('Recuerde seleccionar un tipo de evaluacion.');
        return false;
    }

    if (codEstado === "") {
        alert('Recuerde seleccionar un Estado');
        return false;
    }

    if (descripcion === "") {
        alert('El campo descripcion es requerido.');
        return false;
    }

    accionEvaluacionCrear(cod);
}

function accionEvaluacionCrear(cod) {
    try {
        $(document).ready(function () {
            const formData = crearFormEvaluacionCrear();
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

function crearFormEvaluacionCrear() {
    var state = document.getElementById('state').value;
    var nomEvaluacion = document.getElementById('nomEvaluacion').value;
    var notaMaxima = document.getElementById('notaMaxima').value;
    var codTipoEvaluacion = document.getElementById('codTipoEvaluacion').value;
    var codEstado = document.getElementById('codEstado').value;
    var descripcion = document.getElementById('descripcion').value;

    const formData = new FormData();
    formData.append('state', state);
    formData.append('nomEvaluacion', nomEvaluacion);
    formData.append('notaMaxima', notaMaxima);
    formData.append('codTipoEvaluacion', codTipoEvaluacion);
    formData.append('codEstado', codEstado);
    formData.append('descripcion', descripcion);
    if (state === 'Actualizar') {
        formData.append('codEvaluacion', document.getElementById('codEvaluacion').value);
    }
    return formData;
}


function generarPreguntas() {
    var cantidadPreguntasP = parseInt(document.getElementById('cantidadPreguntasP').value);
    var cantidad = cantidadPreguntasP + 1;
    if (cantidad > 20) {
        alert('No se pueden incluir mas de 20 preguntas por evaluacion.');
        return false;
    }

    document.getElementById('cantidadPreguntasP').value = cantidad;

}