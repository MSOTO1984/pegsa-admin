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

    if ($state == "Incluir" && isset($_POST['codCapacitacion'])) {

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
    } else if ($state == "respuestas") {

        $codEmpleado = $_POST['codEmpleado'];
        $codCapacitacion = $_POST['codCapacitacion'];
        $codEvaluacion = $_POST['codEvaluacion'];

        if ($crudEvaluacion->registrarEmpleadoEvaluacion($codCapacitacion, $codEmpleado, $codEvaluacion, $usuarioCreacion)) {
            $respuesta = new stdClass();
            $respuesta->status = "OK";
            $respuesta->codEmpleado = $codEmpleado;
            $respuesta->codCapacitacion = $codCapacitacion;
            $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
            echo json_encode($respuesta);
        }
    } else {
        $respuesta = array('error' => 'Faltan datos');
        echo json_encode($respuesta);
    }
} else {
    $respuesta = array('error' => 'Solicitud no permitida');
    echo json_encode($respuesta);
}
