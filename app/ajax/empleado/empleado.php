<?php

session_start();

require( '../../../lib/helper.php' );
require( '../../../lib/params.php' );
require( '../../../lib/conexion.php' );
include_once '../../../lib/mailer/class.phpmailer.php';

require( 'crudEmpleado.php' );

Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $crudEmpleado = new CrudEmpleado();

    $usuarioCreacion = isset($_SESSION[MISESSION]['codUsuario']) ? $_SESSION[MISESSION]['codUsuario'] : null;
    $state = $_POST['state'];

    if ($state == "Registrar" || $state == "Registrarme" || $state == "Actualizar") {
        if (isset($_POST['codEmpleado']) && isset($_POST['nomEmpleado'])) {

            $codEmpleado = $_POST['codEmpleado'];
            $nomEmpleado = $_POST['nomEmpleado'];
            $emailEmpleado = $_POST['emailEmpleado'];
            $celEmpleado = $_POST['celEmpleado'];
            $direccion = $_POST['direccion'];
            $codCiudad = $_POST['codCiudad'];
            $codGenero = isset($_POST['codGenero']) ? $_POST['codGenero'] : 1;
            $codEstado = isset($_POST['codEstado']) ? $_POST['codEstado'] : 1;

            if ($crudEmpleado->operacionesEmpleado($state, $codEmpleado, $nomEmpleado, $direccion, $codCiudad, $emailEmpleado, $celEmpleado, $codGenero, $codEstado, $usuarioCreacion)) {
                $respuesta = new stdClass();
                $respuesta->status = "OK";
                $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
                echo json_encode($respuesta);
            }
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    }
} else {
    $respuesta = array('error' => 'Solicitud no permitida');
    echo json_encode($respuesta);
}
