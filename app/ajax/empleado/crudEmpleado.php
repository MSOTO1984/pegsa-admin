<?php

class CrudEmpleado {

    function operacionesEmpleado($state, $codEmpleado, $nomEmpleado, $direccion, $codCiudad, $emailEmpleado, $celEmpleado, $codGenero, $codEstado, $usuarioCreacion) {
        if ($state == "Registrar" || $state == "Registrarme") {
            $sql = "INSERT INTO tab_empleados
                    (
                        codEmpleado, nomEmpleado, 
                        direccion, codCiudad,                        
                        emailEmpleado, celEmpleado,
                        codGenero, codEstado,
                        usuarioCreacion, fechaCreacion
                    )
                    VALUES
                    (
                        '" . $codEmpleado . "', '" . $nomEmpleado . "',
                        '" . $direccion . "', " . $codCiudad . ",
                        '" . $emailEmpleado . "', " . $celEmpleado . ",
                        '" . $codGenero . "', '" . $codEstado . "',
                        '" . $usuarioCreacion . "', NOW()
                    );";
        } else {
            $sql = "UPDATE  tab_empleados 
                       SET  nomEmpleado = '" . $nomEmpleado . "',
                            emailEmpleado = '" . $emailEmpleado . "',
                            direccion = '" . $direccion . "',
                            codCiudad = '" . $codCiudad . "',
                            celEmpleado = " . $celEmpleado . ",
                            codGenero = '" . $codGenero . "',
                            codEstado = '" . $codEstado . "',
                            usuarioEdicion = '" . $usuarioCreacion . "',
                            fechaEdicion = NOW()
                    WHERE   codEmpleado = '" . $codEmpleado . "'";
        }
        return Conexion::ejecutar($sql);
    }

    function consultarEmpleado($emailEmpleado) {
        $sql = "SELECT  a.codEmpleado,
                        a.codGenero,
                        a.nomEmpleado,
                        a.codPerfil,
                        a.claveEmpleado,
                        a.isChangePass,
                        b.nomPerfil
                FROM    tab_empleados a LEFT JOIN tab_perfiles b on a.codPerfil = b.codPerfil
                WHERE   a.emailEmpleado = '" . $emailEmpleado . "' 
                AND     a.codEstado = 1";
        $matriz = Conexion::obtener($sql);
        if (isset($matriz)) {
            return $matriz[0];
        }
        return null;
    }
}
