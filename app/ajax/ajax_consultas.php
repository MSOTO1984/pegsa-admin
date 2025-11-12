<?php

session_start();

require( '../../lib/helper.php' );
require( '../../lib/params.php' );
require( '../../lib/conexion.php' );
Conexion::conectar();

if ($_GET['selector'] == "ajax_lista_generica") {
    $sql = "SELECT  a." . $_GET['codigo'] . " value, UPPER(a." . $_GET['nombre'] . ") label
            FROM    " . $_GET['tabla'] . " a
            ORDER BY a." . $_GET['nombre'] . " ASC;";
    $select = Conexion::obtener($sql);
}

if ($_GET['selector'] == "ajax_lista_ciudades") {
    $sql = "SELECT  c.codCiudad value, UPPER(c.nomCiudad) label
            FROM    tab_ciudades c                 
            WHERE   c.codDepto = " . $_GET['codDepto'] . "
            ORDER BY c.nomCiudad ASC";
    $select = Conexion::obtener($sql);
}

if (!$select) {
    $select = array(array("result" => 201));
}

echo json_encode($select);

