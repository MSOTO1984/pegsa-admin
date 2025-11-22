<?php

class CrudEvaluacion {

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

    function registrarEmpleadoEvaluacion($codCapacitacion, $codEmpleado, $codEvaluacion, $usuarioCreacion) {
        $sql = "INSERT INTO tab_empleado_evaluacion (codCapacitacion, codEmpleado, codEvaluacion, fecha, puntaje, correctas, erradas, codEstado, usuarioCreacion, fechaCreacion) 
                VALUES (" . $codCapacitacion . ", '" . $codEmpleado . "', " . $codEvaluacion . ", '21/11/2025', 5.00, 10, 0, 1, '" . $usuarioCreacion . "', now())";
        return Conexion::ejecutar($sql);
    }
}
