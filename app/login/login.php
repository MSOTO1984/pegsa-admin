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
            <div class="header <?php echo BACKGROUND2; ?>">Ingreso de Usuario</div>

            <div class="body <?php echo BACKGROUND3; ?>">
                <div class="form-group">
                    <input type="email" name="emailUsuario" id="emailUsuario" class="form-control" placeholder="Correo Electr&oacute;nico"/>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña"/>
                </div>
            </div>
            <div class="footer">                
                <button type="button" class="btn <?php echo BACKGROUND2; ?> btn-block" id="state" name="state" value="Ingresar" onclick="return validarIngreso()">      
                    Ingresar  
                </button>
                <p><a href="recuperar.php">Recuperar contrase&ntilde;a</a></p>
                <a href="registro.php" class="text-center">Registrarse</a>
            </div>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/usuario/usuario.js"></script>

    </body>
</html>