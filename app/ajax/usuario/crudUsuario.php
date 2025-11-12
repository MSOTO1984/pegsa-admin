<?php

class CrudUsuario {

    function operacionesUsuario($state, $claveUsuario, $codUsuario, $nomUsuario, $direccion, $codCiudad, $emailUsuario, $celUsuario, $codPerfil, $codGenero, $codEstado, $usuarioCreacion) {
        if ($state == "Registrar" || $state == "Registrarme") {
            $sql = "INSERT INTO tab_usuarios
                    (
                        codUsuario, nomUsuario, 
                        direccion, codCiudad,                        
                        emailUsuario, celUsuario,
                        codPerfil, codGenero, 
                        codEstado, claveUsuario,
                        usuarioCreacion, fechaCreacion
                    )
                    VALUES
                    (
                        '" . $codUsuario . "', '" . $nomUsuario . "',
                        '" . $direccion . "', " . $codCiudad . ",
                        '" . $emailUsuario . "', " . $celUsuario . ",
                        '" . $codPerfil . "', '" . $codGenero . "', 
                        '" . $codEstado . "', '" . strrev(sha1($claveUsuario)) . "',
                        '" . $usuarioCreacion . "', NOW()
                    );";
        } else {
            $sql = "UPDATE  tab_usuarios 
                       SET  nomUsuario = '" . $nomUsuario . "',
                            emailUsuario = '" . $emailUsuario . "',
                            direccion = '" . $direccion . "',
                            codCiudad = '" . $codCiudad . "',
                            celUsuario = " . $celUsuario . ",
                            codPerfil = '" . $codPerfil . "',
                            codGenero = '" . $codGenero . "',
                            codEstado = '" . $codEstado . "',
                            usuarioEdicion = '" . $usuarioCreacion . "',
                            fechaEdicion = NOW()
                    WHERE   codUsuario = '" . $codUsuario . "'";
        }
        return Conexion::ejecutar($sql);
    }

    function generarContrasenia() {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < 8; $i++) {
            $key .= substr($pattern, mt_rand(0, $max), 1);
        }
        return $key;
    }

    function enviarCorreoElectronico($app, $claveUsuario, $nomUsuario, $emailUsuario) {

        $body = "Se&ntilde;or(a) " . $nomUsuario . ":<br/><br/>"
                . "Bienvenido a la plataforma " . $app . ".<br/>"
                . "Las siguientes son sus credenciales de acceso a nuestra plataforma, por favor recuerde cambiar la clave en su primer ingreso.<br/><br/>"
                . "Usuario =  " . $emailUsuario . "<br/>"
                . "Contrase&ntilde;a =  " . $claveUsuario . "<br/><br/>"
                . "Atentamente : " . $app;

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAILHOST;
        $mail->Username = FROM;
        $mail->Password = PASSNOTIFICACIONES;
        $mail->Port = MAILPORT;
        $mail->From = FROM;
        $mail->FromName = $app;
        $mail->AddAddress($emailUsuario);
        $mail->IsHTML(true);
        $mail->Subject = "Registro sistema " . $app;
        $mail->Body = $body;

        if (ENVIOCORREO) {
            return $mail->Send();
        } else {
            return true;
        }
    }

    function enviarCorreoElectronicoAdmin($app, $codUsuario, $nomUsuario, $emailUsuario) {
        $email = $this->getUsuarioAdministrador();
        $body = "Se&ntilde;or(a) Administrador :<br/><br/>"
                . "Hemos detectado un nuevo registro en la plataforma " . $app . ".<br/>"
                . "Los siguientes son los datos registrados, por favor recuerde cambiar o verificar el rol del usuario.<br/><br/>"
                . "Documento:  " . $codUsuario . "<br/><br/>"
                . "Nombre: " . $nomUsuario . "<br/><br/>"
                . "Correo Electrónico: " . $emailUsuario . "<br/><br/>"
                . "Usuario:  " . $emailUsuario . "<br/><br/>"
                . "Atentamente " . $app;

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAILHOST;
        $mail->Username = FROM;
        $mail->Password = PASSNOTIFICACIONES;
        $mail->Port = MAILPORT;
        $mail->From = FROM;
        $mail->FromName = $app;
        $mail->AddAddress($email['emailUsuario']);
        $mail->IsHTML(true);
        $mail->Subject = "Registro sistema " . $app;
        $mail->Body = $body;

        if (ENVIOCORREO) {
            return $mail->Send();
        } else {
            return true;
        }
    }

    function getUsuarioAdministrador() {
        $sql = "SELECT  codUsuario, emailUsuario
                FROM    tab_usuarios                 
                WHERE   codPerfil = 1
                  AND   codEstado = 1";
        $result = Conexion::obtener($sql);
        return $result[0];
    }

    function consultarUsuario($emailUsuario) {
        $sql = "SELECT  a.codUsuario,
                        a.codGenero,
                        a.nomUsuario,
                        a.codPerfil,
                        a.claveUsuario,
                        a.isChangePass,
                        b.nomPerfil
                FROM    tab_usuarios a LEFT JOIN tab_perfiles b on a.codPerfil = b.codPerfil
                WHERE   a.emailUsuario = '" . $emailUsuario . "' 
                AND     a.codEstado = 1";
        $matriz = Conexion::obtener($sql);
        if (isset($matriz)) {
            return $matriz[0];
        }
        return null;
    }

    function actualizarContrasenia($app, $codUsuario, $nomUsuario, $emailUsuario, $claveUsuario, $isChangePass, $usuarioAdmin) {
        $sql = "UPDATE  tab_usuarios
                   SET  claveUsuario = '" . strrev(sha1($claveUsuario)) . "',
                        isChangePass = " . $isChangePass . ",
                        usuarioEdicion = '" . $usuarioAdmin . "', 
                        fechaEdicion = NOW() 
                WHERE   codUsuario = " . $codUsuario;
        if (Conexion::ejecutar($sql)) {
            if ($isChangePass == 0) {
                $this->enviarCorreoElectronico($app, $claveUsuario, $nomUsuario, $emailUsuario);
            }
            return true;
        }
        return false;
    }
}
