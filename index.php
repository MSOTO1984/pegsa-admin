<?php

date_default_timezone_set('America/Bogota');
session_start();

include_once 'lib/params.php';
include_once 'lib/helper.php';
include_once 'lib/conexion.php';
include_once 'lib/formulario.php';
include_once 'lib/funciones.php';
include_once 'lib/mailer/class.phpmailer.php';

Conexion::conectar();

if (empty($_SESSION[MISESSION])) {
    include_once 'app/login/login.php';
} else {
    include_once 'lib/canvas.php';
}