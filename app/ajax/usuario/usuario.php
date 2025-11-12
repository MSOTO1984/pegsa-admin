<?php

session_start();

require( '../../../lib/helper.php' );
require( '../../../lib/params.php' );
require( '../../../lib/conexion.php' );
include_once '../../../lib/mailer/class.phpmailer.php';
require( 'crudUsuario.php' );

Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $crudUsuario = new CrudUsuario();

    $usuarioCreacion = isset($_SESSION[MISESSION]['codUsuario']) ? $_SESSION[MISESSION]['codUsuario'] : null;
    $usuario = $crudUsuario->getUsuarioAdministrador();
    $usuarioAdmin = !isset($usuarioCreacion) ? $usuario['codUsuario'] : $usuarioCreacion;

    $app = MIAPP;
    $state = $_POST['state'];

    if ($state == "Registrar" || $state == "Registrarme" || $state == "Actualizar") {
        if (isset($_POST['codUsuario']) && isset($_POST['nomUsuario'])) {

            $codUsuario = $_POST['codUsuario'];
            $nomUsuario = $_POST['nomUsuario'];
            $emailUsuario = $_POST['emailUsuario'];
            $celUsuario = $_POST['celUsuario'];
            $direccion = $_POST['direccion'];
            $codCiudad = $_POST['codCiudad'];
            $codPerfil = isset($_POST['codPerfil']) ? $_POST['codPerfil'] : 1;
            $codGenero = isset($_POST['codGenero']) ? $_POST['codGenero'] : 1;
            $codEstado = isset($_POST['codEstado']) ? $_POST['codEstado'] : 1;

            $claveUsuario = $crudUsuario->generarContrasenia();
            if ($crudUsuario->operacionesUsuario($state, $claveUsuario, $codUsuario, $nomUsuario, $direccion, $codCiudad, $emailUsuario, $celUsuario, $codPerfil, $codGenero, $codEstado, $usuarioAdmin)) {
                $crudUsuario->enviarCorreoElectronico($app, $claveUsuario, $nomUsuario, $emailUsuario);
                $crudUsuario->enviarCorreoElectronicoAdmin($app, $codUsuario, $nomUsuario, $emailUsuario);
            }

            $claveFinalUsuario = (!ENVIOCORREO && $state == "Registrarme") ? $claveUsuario : '';

            $respuesta = new stdClass();
            $respuesta->status = "OK";
            $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! " . $claveFinalUsuario;
            echo json_encode($respuesta);
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    } else if ($state == "Actualizar Clave") {
        if (isset($_POST['claveUsuario'])) {
            $_SESSION[MISESSION]['isChangePass'] = 1;
            $crudUsuario->actualizarContrasenia("", $usuarioCreacion, "", "", $_POST['claveUsuario'], 1, $usuarioCreacion);

            $respuesta = new stdClass();
            $respuesta->status = "OK";
            $respuesta->mensaje = "Acción ( " . $state . " ) realizada con exito! ";
            echo json_encode($respuesta);
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    } else if ($state == "Recuperar") {
        if (isset($_POST['emailUsuario'])) {

            $status = "WRONG";
            $mensaje = "Error recuperando contraseña, por favor comuniquese con su proveedor de servicios.";

            $emailUsuario = $_POST['emailUsuario'];
            $usuario = $crudUsuario->consultarUsuario($emailUsuario);
            if (isset($usuario)) {
                $claveUsuario = $crudUsuario->generarContrasenia();
                if ($crudUsuario->actualizarContrasenia($app, $usuario['codUsuario'], $usuario['nomUsuario'], $emailUsuario, $claveUsuario, 0, $usuarioAdmin)) {
                    $claveFinalUsuario = !ENVIOCORREO ? $claveUsuario : '';
                    $status = "OK";
                    $mensaje = "Se ha enviado a " . $_REQUEST['emailUsuario'] . " un correo electrónico con indicaciones para la recuperación contraseña. " . $claveFinalUsuario;
                }
            }

            $respuesta = new stdClass();
            $respuesta->status = $status;
            $respuesta->mensaje = $mensaje;
            echo json_encode($respuesta);
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    } else if ($state == "Ingresar") {
        if (isset($_POST['emailUsuario']) && isset($_POST['claveUsuario'])) {

            $status = "WRONG";
            $mensaje = "Error en ingreso a la aplicación";

            $emailUsuario = $_POST['emailUsuario'];
            $usuario = $crudUsuario->consultarUsuario($emailUsuario);

            if ($usuario) {
                if (strrev(sha1($_POST['claveUsuario'])) == $usuario['claveUsuario']) {
                    $_SESSION[MISESSION]['emailUsuario'] = $_REQUEST['emailUsuario'];
                    $_SESSION[MISESSION]['codUsuario'] = $usuario['codUsuario'];
                    $_SESSION[MISESSION]['codGenero'] = $usuario['codGenero'];
                    $_SESSION[MISESSION]['nomUsuario'] = $usuario['nomUsuario'];
                    $_SESSION[MISESSION]['codPerfil'] = $usuario['codPerfil'];
                    $_SESSION[MISESSION]['nomPerfil'] = $usuario['nomPerfil'];
                    $_SESSION[MISESSION]['isChangePass'] = $usuario['isChangePass'];
                    $status = "OK";
                    $mensaje = "";
                }
            }

            $respuesta = new stdClass();
            $respuesta->status = $status;
            $respuesta->mensaje = $mensaje;
            echo json_encode($respuesta);
        } else {
            $respuesta = array('error' => 'Faltan datos');
            echo json_encode($respuesta);
        }
    }
} else {
    $respuesta = array('error' => 'Solicitud no permitida');
    echo json_encode($respuesta);
}
