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
}
