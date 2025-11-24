<?php

session_start();

require( '../../../lib/helper.php' );
require( '../../../lib/params.php' );
require( '../../../lib/conexion.php' );

require( 'crudEvaluacion.php' );

Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $crudEvaluacion = new CrudEvaluacion();

    $usuarioCreacion = isset($_SESSION[MISESSION]['codUsuario']) ? $_SESSION[MISESSION]['codUsuario'] : null;
    $state = $_POST['state'];

    if ($state == "Registrar" || $state == "Actualizar") {

        if (isset($_POST['nomEvaluacion'])) {

            $codEvaluacion = isset($_POST['codEvaluacion']) ? $_POST['codEvaluacion'] : '';
            $nomEvaluacion = $_POST['nomEvaluacion'];
            $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
            $codTipoEvaluacion = $_POST['codTipoEvaluacion'];
            $notaMaxima = $_POST['notaMaxima'];
            $codEstado = isset($_POST['codEstado']) ? $_POST['codEstado'] : 1;

            if ($crudEvaluacion->operacionesEvaluacion($state, $codEvaluacion, $nomEvaluacion, $descripcion, $codTipoEvaluacion, $notaMaxima, $codEstado, $usuarioCreacion)) {
                $respuesta = new stdClass();
                $respuesta->status = "OK";
                $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
                echo json_encode($respuesta);
            }
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    } else if ($state == "Incluir") {

        if (isset($_POST['codCapacitacion'])) {

            $codCapacitacion = $_POST['codCapacitacion'];
            $codEvaluacion = $_POST['codEvaluacion'];
            $ordenEvaluacion = $_POST['ordenEvaluacion'];
            $fechaLimite = $_POST['fechaLimite'];
            $esObligatoria = $_POST['esObligatoria'];
            $codEstado = $_POST['codEstado'];
            $evaluacion = $crudEvaluacion->getCapacitacion($codCapacitacion);

            if ($crudEvaluacion->operacionesIncluirEvaluacion($codCapacitacion, $codEvaluacion, $ordenEvaluacion, $fechaLimite, $esObligatoria, $evaluacion, $codEstado, $usuarioCreacion)) {
                $respuesta = new stdClass();
                $respuesta->codCapacitacion = $codCapacitacion;
                $respuesta->status = "OK";
                $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
                echo json_encode($respuesta);
            } else {
                $respuesta = new stdClass();
                $respuesta->evaluacion = $evaluacion;
                $respuesta->codCapacitacion = $codCapacitacion;
                $respuesta->status = "WRONG";
                $respuesta->mensaje = "Error inesperado! ";
                echo json_encode($respuesta);
            }
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    } else if ($state == "respuestas") {

        $erradas = 0;
        $correctas = 0;
        $puntajeTotal = 0;
        $preguntasCalificadas = 0;

        $codEmpleado = $_POST['codEmpleado'];
        $codEvaluacion = $_POST['codEvaluacion'];

        $cantidadPreguntas = $crudEvaluacion->cantidadPreguntasEvaluacion($codEvaluacion);

        foreach ($_POST['preguntas'] as $codPregunta => $data) {

            $datosPregunta = $crudEvaluacion->datosPregunta($codPregunta);
            $puntajeMax = $datosPregunta['puntaje'];

            $respuestas = $data['respuesta'];
            if (is_array($respuestas)) {

                $correctasBD = $crudEvaluacion->obtenerRespuestasCorrectas($codPregunta, true);
                $idsCorrectas = array_map('intval', array_column($correctasBD, 'codRespuesta'));

                $todasOpcionesBD = $crudEvaluacion->obtenerRespuestasCorrectas($codPregunta, false);
                $idsTodasOpciones = array_map('intval', array_column($todasOpcionesBD, 'codRespuesta'));

                $respuestasUser = array_map('intval', $respuestas);

                $numCorrectas = count($idsCorrectas);
                $valorPorOpcion = ($numCorrectas > 0) ? ($puntajeMax / $numCorrectas) : 0;

                $interseccion = array_intersect($idsCorrectas, $respuestasUser);
                $countCorrectSelected = count($interseccion);

                $puntajePregunta = $countCorrectSelected * $valorPorOpcion;

                $marcoTodasOpciones = (count($respuestasUser) === count($idsTodasOpciones));

                if ($marcoTodasOpciones) {
                    $puntajePregunta = 0;
                }

                foreach ($respuestasUser as $codRespuesta) {
                    $esCorrecta = in_array($codRespuesta, $idsCorrectas, true);
                    $puntosOpcion = $esCorrecta ? $valorPorOpcion : 0;
                    $crudEvaluacion->registrarRespuestasEmpleado(
                            $codEvaluacion,
                            $codPregunta,
                            $codRespuesta,
                            null,
                            $codEmpleado,
                            $puntosOpcion
                    );
                }

                $respondioTodasCorrectas = empty(array_diff($idsCorrectas, $respuestasUser));
                $marcoIncorrectas = !empty(array_diff($respuestasUser, $idsCorrectas));
                if ($respondioTodasCorrectas && !$marcoIncorrectas) {
                    $correctas++;
                } else {
                    $erradas++;
                }

                $puntajeTotal += max(0, $puntajePregunta);
                $preguntasCalificadas++;
            } else if (is_numeric($respuestas)) {
                $puntos = $crudEvaluacion->puntajeObtenido($respuestas, $puntajeMax);
                $crudEvaluacion->registrarRespuestasEmpleado(
                        $codEvaluacion,
                        $codPregunta,
                        $respuestas,
                        null,
                        $codEmpleado,
                        $puntos
                );

                $puntajeTotal += $puntos;
                if ($puntos > 0) {
                    $correctas++;
                } else {
                    $erradas++;
                }
                $preguntasCalificadas++;
            } else if (is_string($respuestas) && trim($respuestas) !== "") {
                $crudEvaluacion->registrarRespuestasEmpleado(
                        $codEvaluacion,
                        $codPregunta,
                        'null',
                        trim($respuestas),
                        $codEmpleado,
                        0
                );
            }
        }

        $codCapacitacion = $_POST['codCapacitacion'];
        $codEstado = ($preguntasCalificadas == $cantidadPreguntas) ? 7 : 6;

        if ($crudEvaluacion->registrarEmpleadoEvaluacion($codCapacitacion, $codEmpleado, $codEvaluacion, $puntajeTotal, $correctas, $erradas, $codEstado, $codEmpleado)) {
            $respuesta = new stdClass();
            $respuesta->status = "OK";
            $respuesta->codEmpleado = $codEmpleado;
            $respuesta->codCapacitacion = $codCapacitacion;
            $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
            echo json_encode($respuesta);
        }
    }
} else {
    $respuesta = array('error' => 'Solicitud no permitida');
    echo json_encode($respuesta);
}
    