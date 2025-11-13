<?php

class CrudCapacitacion {

    function operacionesCapacitacion($state, $codCapacitacion, $nomCapacitacion, $fecha, $tiempo, $observacion, $codTipoCapacitacion, $codUsuario, $codCiudad, $codEstado, $usuarioCreacion) {
        if ($state == "Registrar" || $state == "Registrarme") {
            $sql = "INSERT INTO tab_capacitaciones
                    (
                        nomCapacitacion, fecha, tiempo, 
                        observacion, codTipoCapacitacion, 
                        codUsuario, codCiudad, codEstado,
                        usuarioCreacion, fechaCreacion
                    )
                    VALUES
                    (
                        '" . $nomCapacitacion . "', '" . $fecha . "',
                        " . $tiempo . ", '" . $observacion . "',
                        " . $codTipoCapacitacion . ", '" . $codUsuario . "',
                        " . $codCiudad . ", " . $codEstado . ",
                        '" . $usuarioCreacion . "', NOW()
                    );";
        } else {
            $sql = "UPDATE  tab_capacitaciones 
                       SET  nomCapacitacion = '" . $nomCapacitacion . "',
                            fecha = '" . $fecha . "', 
                            tiempo = " . $tiempo . ",
                            observacion = '" . $observacion . "',
                            codTipoCapacitacion = " . $codTipoCapacitacion . ", 
                            codUsuario = '" . $codUsuario . "',
                            codCiudad = " . $codCiudad . ",
                            codEstado = " . $codEstado . ",
                            usuarioEdicion = '" . $usuarioCreacion . "',
                            fechaEdicion = NOW()
                    WHERE   codCapacitacion = '" . $codCapacitacion . "'";
        }
        return Conexion::ejecutar($sql);
    }
}
