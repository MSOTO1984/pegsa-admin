<?php

session_start();

require( '../../../lib/helper.php' );
require( '../../../lib/params.php' );
require( '../../../lib/conexion.php' );

require( 'crudCapacitacion.php' );

Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $crudCapacitacion = new CrudCapacitacion();

    $usuarioCreacion = isset($_SESSION[MISESSION]['codUsuario']) ? $_SESSION[MISESSION]['codUsuario'] : null;
    $state = $_POST['state'];

    if ($state == "Registrar" || $state == "Actualizar") {
        if (isset($_POST['nomCapacitacion'])) {

            $codCapacitacion = isset($_POST['codCapacitacion']) ? $_POST['codCapacitacion'] : '';
            $nomCapacitacion = $_POST['nomCapacitacion'];
            $fecha = $_POST['fecha'];
            $tiempo = $_POST['tiempo'];
            $observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';
            $codTipoCapacitacion = $_POST['codTipoCapacitacion'];
            $codUsuario = $_POST['codUsuario'];
            $codCiudad = $_POST['codCiudad'];
            $codEstado = isset($_POST['codEstado']) ? $_POST['codEstado'] : 3;

            if ($crudCapacitacion->operacionesCapacitacion($state, $codCapacitacion, $nomCapacitacion, $fecha, $tiempo, $observacion, $codTipoCapacitacion, $codUsuario, $codCiudad, $codEstado, $usuarioCreacion)) {
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
