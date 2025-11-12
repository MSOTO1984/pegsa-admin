<?php

class CrudPerfil {

    function insertarPermisos($cadena, $codPerfil, $usuarioCreacion) {
        $arrayDeSubcadenas = explode(',', $cadena);
        foreach ($arrayDeSubcadenas as $subcadena) {
            $sql = "INSERT INTO tab_permisos
                    (
                        codPerfil, codOption,
                        usuarioCreacion, fechaCreacion
                    )
                    VALUES
                    (
                        '" . $codPerfil . "', " . $subcadena . ",
                        '" . $usuarioCreacion . "', NOW()
                    );";
            Conexion::ejecutar($sql);
        }
    }

    function operacionesPerfil($state, $codPerfil, $nomPerfil, $usuarioCreacion) {
        if ($state == "Registrar") {
            $sql = "INSERT INTO tab_perfiles(codPerfil, nomPerfil, usuarioCreacion, fechaCreacion)
                    VALUES
                    (
                        " . $codPerfil . ", '" . $nomPerfil . "','" . $usuarioCreacion . "', NOW()
                    );";
        } else {
            $sql2 = "DELETE FROM tab_permisos WHERE codPerfil = '" . $codPerfil . "' ;";
            Conexion::ejecutar($sql2);
            $sql = "UPDATE  tab_perfiles 
                    SET     nomPerfil = '" . $nomPerfil . "', 
                            usuarioEdicion = '" . $usuarioCreacion . "', 
                            fechaEdicion = NOW() 
                    WHERE   codPerfil = '" . $codPerfil . "'";
        }
        return Conexion::ejecutar($sql);
    }
}
