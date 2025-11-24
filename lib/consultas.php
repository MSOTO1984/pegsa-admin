<?php

class Consultas {

    public function validarFirmaEmpleado($codCapacitacion, $codEmpleado) {
        $sql = "SELECT count(*) cantidad
                FROM   tab_asistencias                
                WHERE  codEmpleado = '" . $codEmpleado . "'
                  AND  codCapacitacion = " . $codCapacitacion;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0]['cantidad'];
        }
        return 0;
    }

    public function getCapacitacion($codCapacitacion) {
        $sql = "SELECT a.*, b.nomTipoCapacitacion, c.nomUsuario, d.nomCiudad, e.nomDepto
                FROM   tab_capacitaciones a 
                            LEFT JOIN tab_tipo_capacitacion b on a.codTipoCapacitacion = b.codTipoCapacitacion
                            LEFT JOIN tab_usuarios c on a.codUsuario = c.codUsuario
                            LEFT JOIN tab_ciudades d on a.codCiudad = d.codCiudad
                            LEFT JOIN tab_deptos e on d.codDepto = e.codDepto                        
                WHERE  a.codEstado = 3
                  AND  a.codCapacitacion = " . $codCapacitacion;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }

    public function getListaEmpleados($codEmpleado) {
        $sql = "SELECT  a.codEmpleado, UPPER(a.nomEmpleado)
                FROM    tab_empleados a                 
                WHERE   a.codEstado = 1 
                  AND   codEmpleado = " . $codEmpleado;
        return Conexion::obtener($sql);
    }

    public function verificarEvaluacionDiligenciadas($codCapacitacion, $codEmpleado) {
        $sql = "SELECT codEvaluacion
                FROM   tab_empleado_evaluacion 
                WHERE  codCapacitacion = " . $codCapacitacion . " 
                  AND  codEmpleado = '" . $codEmpleado . "'";
        return Conexion::obtener($sql);
    }

    public function evaluacionesCargadas($codCapacitacion) {
        $sql = "SELECT codEvaluacion, esobligatoria, fechaLimite, ordenEvaluacion
                FROM   tab_capacitacion_evaluacion 
                WHERE  codCapacitacion = " . $codCapacitacion . " 
                  AND  codEstado = 1";
        return Conexion::obtener($sql);
    }

    public function obtenerEvaluacion($codEvaluacion) {
        $sql = "SELECT a.nomEvaluacion, 
                       a.descripcion, 
                       a.cantidadPreguntas, 
                       a.notaMaxima, 
                       a.descripcion,
                       b.nomTipoEvaluacion
                  FROM tab_evaluaciones a
                            LEFT JOIN tab_tipo_evaluacion b on a.codTipoEvaluacion = b.codTipoEvaluacion
                 WHERE a.codEstado = 1 AND a.codEvaluacion = " . $codEvaluacion;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }

    public function obtenerPreguntasEvaluacion($codEvaluacion) {
        $sql = "SELECT  a.codPregunta,
                        a.ordenPregunta,
                        a.enunciado, 
                        a.codTipoPregunta, 
                        a.puntaje
                  FROM  tab_preguntas a 
                            LEFT JOIN tab_tipo_pregunta b on a.codTipoPregunta = b.codTipoPregunta
                 WHERE  a.codEstado = 1 AND a.codEvaluacion = " . $codEvaluacion . " 
              ORDER BY  a.ordenPregunta ASC";
        return Conexion::obtener($sql);
    }

    public function obtenerRespuestasPregunta($codPregunta) {
        $sql = "SELECT  codRespuesta,
                        textoRespuesta, 
                        ordenRespuesta
                  FROM  tab_respuestas
                 WHERE  codPregunta = " . $codPregunta . " 
              ORDER BY  ordenRespuesta ASC";
        return Conexion::obtener($sql);
    }

    function cantidadRespuestasCorrectas($codPregunta) {
        $sql = "SELECT count(*) cantidad FROM tab_respuestas WHERE codPregunta = " . $codPregunta . " AND esCorrecta = 1";
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0]['cantidad'];
        }
        return null;
    }
}
