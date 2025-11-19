function salvarFirmaColaborador(codEmpleado, codCapacitacion, codEvaluacion, imageB64){
     try {
        $(document).ready(function () {
            const formData = new FormData();
            formData.append('codEmpleado', codEmpleado);
            formData.append('codCapacitacion', codCapacitacion);
            formData.append('codEvaluacion', codEvaluacion);
            formData.append('imageB64', imageB64);

            $.ajax({
                url: "app/asistencia/guardarFirmaColaborador.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.status === 'OK') {
                        alert(data.mensaje);
                        window.location.href = 'asistencia.php';
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

