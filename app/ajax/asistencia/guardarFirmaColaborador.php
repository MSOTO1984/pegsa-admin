<?php

session_start();

require( '../../../lib/helper.php' );
require( '../../../lib/params.php' );
require( '../../../lib/conexion.php' );

Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['codCapacitacion']) && isset($_POST['codEmpleado']) && isset($_POST['imageB64'])) {

        $codEmpleado = $_POST['codEmpleado'];
        $codCapacitacion = $_POST['codCapacitacion'];

        $imageName = $_POST['codEmpleado'] . "_" . $_POST['codCapacitacion'] . ".png";
        $imageB64 = str_replace("data:image/png;base64,", "", $_POST['imageB64']);

        if (!file_exists(PATHFIRMAS)) {
            mkdir(PATHFIRMAS, 0777);
        }

        file_put_contents(PATHFIRMAS . $imageName, base64_decode($imageB64));

        $params = array($codCapacitacion, $codEmpleado);

        $respuesta = new stdClass();
        if (Conexion::ejecutarSP('registrarAsistencia', $params)) {
            $respuesta->status = "OK";
            $respuesta->mensaje = "Asistencia Firmada con exito";
        } else {
            $respuesta->status = "ERROR";
            $respuesta->mensaje = "ERROR";
        }

        echo json_encode($respuesta);
    } else {
        $respuesta = array('error' => 'Faltan datos');
        echo json_encode($respuesta);
    }
} else {
    $respuesta = array('error' => 'Solicitud no permitida');
    echo json_encode($respuesta);
}