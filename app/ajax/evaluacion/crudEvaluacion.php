<?php

class CrudEvaluacion {

    function operacionesEvaluacion($state, $codEvaluacion, $nomEvaluacion, $descripcion, $codTipoEvaluacion, $notaMaxima, $codEstado, $usuarioCreacion) {
        if ($state == "Registrar" || $state == "Registrarme") {
            $sql = "INSERT INTO tab_evaluaciones
                    (
                        nomEvaluacion, descripcion, 
                        codTipoEvaluacion, notaMaxima, codEstado,
                        usuarioCreacion, fechaCreacion
                    )
                    VALUES
                    (
                        '" . $nomEvaluacion . "', '" . $descripcion . "',
                        " . $codTipoEvaluacion . ", " . $notaMaxima . ",
                        " . $codEstado . ", '" . $usuarioCreacion . "', NOW()
                    );";
        } else {
            $sql = "UPDATE  tab_evaluaciones 
                       SET  nomEvaluacion = '" . $nomEvaluacion . "',
                            descripcion = '" . $descripcion . "', 
                            codTipoEvaluacion = " . $codTipoEvaluacion . ",
                            notaMaxima = " . $notaMaxima . ",                    
                            codEstado = " . $codEstado . ",
                            usuarioEdicion = '" . $usuarioCreacion . "',
                            fechaEdicion = NOW()
                    WHERE   codEvaluacion = '" . $codEvaluacion . "'";
        }
        return Conexion::ejecutar($sql);
    }

    function operacionesIncluirEvaluacion($codCapacitacion, $codEvaluacion, $ordenEvaluacion, $fechaLimite, $esObligatoria, $evaluacion, $codEstado, $usuarioCreacion) {

        $sql = "INSERT INTO tab_capacitacion_evaluacion
                (
                    codCapacitacion, codEvaluacion, esObligatoria, 
                    fechaLimite, ordenEvaluacion, codEstado, 
                    usuarioCreacion, fechaCreacion
                )
                VALUES
                (
                    " . $codCapacitacion . ", " . $codEvaluacion . ",
                    " . $esObligatoria . ", '" . $fechaLimite . "',
                    " . $ordenEvaluacion . ", " . $codEstado . ",
                    '" . $usuarioCreacion . "', NOW()
                );";

        $result = Conexion::ejecutar($sql);
        if (isset($result) && (int) $evaluacion > 0) {
            $sql2 = "UPDATE tab_capacitaciones 
                        SET evaluacion = " . $evaluacion . ", 
                            usuarioEdicion = '" . $usuarioCreacion . "', 
                            fechaEdicion = NOW() 
                      WHERE codCapacitacion = " . $codCapacitacion;
            Conexion::ejecutar($sql2);
        }
        return $result;
    }

    function getCapacitacion($codCapacitacion) {
        $sql = "SELECT evaluacion
                FROM   tab_capacitaciones                      
                WHERE  codCapacitacion = " . $codCapacitacion;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0]['evaluacion'];
        }
        return 200;
    }

    function datosPregunta($codPregunta) {
        $sql = "SELECT codTipoPregunta, puntaje 
                  FROM tab_preguntas 
                 WHERE codPregunta =" . $codPregunta;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0];
        }

        return null;
    }

    function puntajeObtenido($codRespuesta, $puntajeMax) {
        $sql = "SELECT esCorrecta FROM tab_respuestas WHERE codRespuesta = " . $codRespuesta;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            if ($result[0]['esCorrecta'] == 1) {
                return $puntajeMax;
            }
        }
        return 0;
    }

    function obtenerRespuestasCorrectas($codPregunta, $correcta) {
        $sql = "SELECT codRespuesta FROM tab_respuestas WHERE codPregunta = " . $codPregunta;
        if ($correcta) {
            $sql .= " AND esCorrecta = 1";
        }
        return Conexion::obtener($sql);
    }

    function registrarRespuestasEmpleado($codEvaluacion, $codPregunta, $codRespuesta, $textoAbierto, $codEmpleado, $puntajeObtenido) {
        $sql = "INSERT INTO tab_respuestas_empleado (codEvaluacion, codPregunta, codRespuesta, textoAbierto, codEmpleado, puntajeObtenido, fechaRespuesta) 
                VALUES (" . $codEvaluacion . ", " . $codPregunta . "," . $codRespuesta . ",'" . $textoAbierto . "','" . $codEmpleado . "', " . $puntajeObtenido . ", now())";
        return Conexion::ejecutar($sql);
    }

    function cantidadPreguntasEvaluacion($codEvaluacion) {

        $sql = "SELECT cantidadPreguntas 
                  FROM tab_evaluaciones 
                 WHERE codEvaluacion =" . $codEvaluacion;

        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0]['cantidadPreguntas'];
        }
        return 0;
    }

    function registrarEmpleadoEvaluacion($codCapacitacion, $codEmpleado, $codEvaluacion, $puntajeTotal, $correctas, $erradas, $codEstado, $usuarioCreacion) {
        $sql = "INSERT INTO tab_empleado_evaluacion (codCapacitacion, codEmpleado, codEvaluacion, puntaje, correctas, erradas, codEstado, usuarioCreacion, fechaCreacion) 
                VALUES (" . $codCapacitacion . ", '" . $codEmpleado . "', " . $codEvaluacion . ", " . $puntajeTotal . ", " . $correctas . ", " . $erradas . ", " . $codEstado . ", '" . $usuarioCreacion . "', now())";
        return Conexion::ejecutar($sql);
    }
}
