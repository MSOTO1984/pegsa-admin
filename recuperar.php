<?php
date_default_timezone_set('America/Bogota');
include_once 'lib/params.php';
?>
<!DOCTYPE html>
<html class="<?php echo BACKGROUND; ?>">
    <head>
        <meta charset="UTF-8">
        <title>.:: <?php echo MIAPP; ?> ::.</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />       
    </head>
    <body class="<?php echo BACKGROUND; ?>">
        <div class="form-box" id="login-box">
            <div class="header <?php echo BACKGROUND2; ?>">Recuperacion de Contraseña</div>            
            <div class="body <?php echo BACKGROUND3; ?>">  
                <p>Ingrese su dirección de correo electrónico y le enviaremos a este instrucciones para restablecer su contraseña.</p>
                <div class="form-group">
                    <input type="email" name="emailUsuario" id="emailUsuario" class="form-control"  required placeholder="Correo Electr&oacute;nico"/>
                </div>               
            </div>
            <div class="footer">
                <button type="button" class="btn <?php echo BACKGROUND2; ?> btn-block" id="state" name="state" value="Recuperar" onclick="return validarRecuperacion()">      
                    Recuperar  
                </button>
                <a href="index.php" class="text-center">Regresar a ingreso de Usuario</a>
            </div>

            <!--div class="margin text-center">
                <span>Register using social networks</span>
                <br/>
                <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>
                <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>
                <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>
            </div-->
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/usuario/usuario.js" type="text/javascript"></script>
    </body>
</html>