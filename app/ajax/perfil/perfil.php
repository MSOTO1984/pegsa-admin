<?php

session_start();

require( '../../../lib/helper.php' );
require( '../../../lib/params.php' );
require( '../../../lib/conexion.php' );
require( 'crudPerfil.php' );

Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nomPerfil'])) {

        $state = $_POST['state'];
        $codPerfil = $_POST['codPerfil'];
        $usuarioCreacion = $_SESSION[MISESSION]['codUsuario'];

        $crudPerfil = new CrudPerfil();
        if ($crudPerfil->operacionesPerfil($state, $codPerfil, strtoupper($_POST['nomPerfil']), $usuarioCreacion)) {
            $crudPerfil->insertarPermisos($_POST['cadena'], $codPerfil, $usuarioCreacion);
        }

        $respuesta = new stdClass();
        $respuesta->status = "OK";
        $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
        echo json_encode($respuesta);
    } else {
        $respuesta = array('error' => 'Faltan datos');
        echo json_encode($respuesta);
    }
} else {
    $respuesta = array('error' => 'Solicitud no permitida');
    echo json_encode($respuesta);
}
