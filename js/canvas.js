const canvas = document.getElementById("canvas");
if (canvas) {

    let xAnterior = 0;
    let yAnterior = 0;
    let xActual = 0;
    let yActual = 0;
    let haComenzadoDibujo = false;
    let contador = 0;

    const $canvas = document.querySelector("#canvas");
    const $btnFirmar = document.querySelector("#btnFirmar");
    const $btnLimpiar = document.querySelector("#btnLimpiar");

    const contexto = $canvas.getContext("2d");
    const GROSOR = 2;
    const COLOR_PINCEL = "black";
    const COLOR_FONDO = "#D9EDF7";

    const obtenerXReal = (clientX) => clientX - $canvas.getBoundingClientRect().left;
    const obtenerYReal = (clientY) => clientY - $canvas.getBoundingClientRect().top;

    const limpiarCanvas = () => {
        contador = 0;
        $canvas.width = 312;
        $canvas.height = 250;
        contexto.fillStyle = COLOR_FONDO;
        contexto.fillRect(0, 0, $canvas.width, $canvas.height);
    };

    limpiarCanvas();
    $btnLimpiar.onclick = limpiarCanvas;

    $btnFirmar.onclick = () => {

        var codEmpleado = null;
        var imageB64 = '';
        var codCapacitacion = document.getElementById('codCapacitacion').value;

        var codEmpleado = document.getElementById('codEmpleado').value;
        if (codEmpleado === '') {
            alert('Debe seleccionar un nombre para firmar');
            return;
        }

        if (contador === 0) {
            alert('Debe firmar sobre el recuadro');
            return;
        } else if (contador > 0) {
            imageB64 = $canvas.toDataURL();
            salvarFirmaColaborador(codEmpleado, codCapacitacion, 1, imageB64);
        }
    };

    const onClicOToqueIniciado = evento => {
        xAnterior = xActual;
        yAnterior = yActual;
        xActual = obtenerXReal(evento.clientX);
        yActual = obtenerYReal(evento.clientY);
        contexto.beginPath();
        contexto.fillStyle = COLOR_PINCEL;
        contexto.fillRect(xActual, yActual, GROSOR, GROSOR);
        contexto.closePath();
        haComenzadoDibujo = true;
    };

    const onMouseODedoMovido = evento => {
        evento.preventDefault();
        if (!haComenzadoDibujo) {
            return;
        }
        let target = evento;
        if (evento.type.includes("touch")) {
            target = evento.touches[0];
        }
        xAnterior = xActual;
        yAnterior = yActual;
        xActual = obtenerXReal(target.clientX);
        yActual = obtenerYReal(target.clientY);
        contexto.beginPath();
        contexto.moveTo(xAnterior, yAnterior);
        contexto.lineTo(xActual, yActual);
        contexto.strokeStyle = COLOR_PINCEL;
        contexto.lineWidth = GROSOR;
        contexto.stroke();
        contexto.closePath();
    };

    const onMouseODedoLevantado = () => {
        haComenzadoDibujo = false;
        contador++;
    };

    ["mousedown", "touchstart"].forEach(nombreDeEvento => {
        $canvas.addEventListener(nombreDeEvento, onClicOToqueIniciado);
    });

    ["mousemove", "touchmove"].forEach(nombreDeEvento => {
        $canvas.addEventListener(nombreDeEvento, onMouseODedoMovido);
    });

    ["mouseup", "touchend"].forEach(nombreDeEvento => {
        $canvas.addEventListener(nombreDeEvento, onMouseODedoLevantado);
    });
}