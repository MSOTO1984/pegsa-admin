function salvarFirmaColaborador(codEmpleado, codCapacitacion, codEvaluacion, imageB64) {
    try {
        $(document).ready(function () {
            const formData = new FormData();
            formData.append('codEmpleado', codEmpleado);
            formData.append('codCapacitacion', codCapacitacion);
            formData.append('codEvaluacion', codEvaluacion);
            formData.append('imageB64', imageB64);

            $.ajax({
                url: "app/ajax/asistencia/guardarFirmaColaborador.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'asistencia.php?codCapacitacion=' + codCapacitacion + '&codEmpleado=' + codEmpleado;
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

function verificarConsultaAsistencias() {

    const campos = {
        codUsuario: $("#codUsuario").val(),
        fechaIni: $("#fechaIni").val(),
        fechaFin: $("#fechaFin").val(),
        codTipoCapacitacion: $("#codTipoCapacitacion").val(),
        codCapacitacion: $("#codCapacitacion").val(),
        codEmpleado: $("#codEmpleado").val(),
        codEstado: $("#codEstado").val()
    };

    let count = 0;
    const incrementarContador = (campo) => {
        if (campo.trim() !== "") {
            count++;
        }
    };

    incrementarContador(campos.codUsuario);
    incrementarContador(campos.fechaIni);
    incrementarContador(campos.fechaFin);
    incrementarContador(campos.codTipoCapacitacion);
    incrementarContador(campos.codCapacitacion);
    incrementarContador(campos.codEmpleado);
    incrementarContador(campos.codEstado);

    if (count === 0) {
        alert("Debe seleccionar como mínimo un filtro para realizar una consulta en asistencias.");
        return false;
    }

    const validarFechas = (fechaIni, fechaFin, mensajeInicioRequerido, mensajeFinRequerido, mensajeRangoInvalido) => {
        if (fechaIni === "" && fechaFin !== "") {
            alert(mensajeInicioRequerido);
            return false;
        }
        if (fechaFin === "" && fechaIni !== "") {
            alert(mensajeFinRequerido);
            return false;
        }
        if (fechaIni > fechaFin) {
            alert(mensajeRangoInvalido);
            return false;
        }
        return true;
    };

    if (!validarFechas(
            campos.fechaIni,
            campos.fechaFin,
            "El campo Fecha Inicial es requerido cuando se selecciona una Fecha Final.",
            "El campo Fecha Final es requerido cuando se selecciona una Fecha Inicial.",
            "La Fecha Final no puede ser anterior a la Fecha Inicial."
            )) {
        return false;
    }

    return true;
}