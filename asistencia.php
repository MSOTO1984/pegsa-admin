<?php
date_default_timezone_set('America/Bogota');
include_once 'lib/params.php';
include_once 'lib/conexion.php';
include_once 'lib/helper.php';
include_once 'lib/funciones.php';
include_once 'lib/formulario.php';

Conexion::conectar();

$capacitacion = null;
if (isset($_GET['codCapacitacion'])) {
    $codCapacitacion = $_GET['codCapacitacion'];
    $capacitacion = getCapacitacion($codCapacitacion);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>.:: <?php echo MIAPP; ?> ::.</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="skin-blue">
        <header class="header">
            <a href="index.php" class="logo">
                .:: <?php echo MIAPP; ?> ::.
            </a>                
            <nav class="navbar navbar-static-top" role="navigation">                   
                <div class="navbar-right"></div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <aside class="right-side">
                <?php
                if (!isset($capacitacion)) {
                    include_once 'lib/404.php';
                } else {
                    ?>
                    <div class="pad margin no-print">
                        <div class="alert alert-info" style="margin-bottom: 0!important;">
                            <i class="fa fa-info"></i>
                            <b>Nota:</b> Recuerde seleccionar su usuario y realizar la respectiva firma para dar como certificada su capacitacion 
                        </div>
                    </div>
                    <section class="content invoice">

                        <div class="row">
                            <div class="col-xs-12">
                                <h2 class="page-header">
                                    <i class="fa fa-binoculars"></i>&nbsp; <?= $capacitacion['nomCapacitacion'] ?>
                                    <small class="pull-right"><strong>Fecha Realizaci&oacute;n:</strong> <?= $capacitacion['fecha'] ?></small>
                                </h2>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <strong>Tipo de Capacitaci&oacute;n</strong>
                                <address>
                                    <?= $capacitacion['nomTipoCapacitacion'] ?>                            
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <strong>Capacitador</strong>
                                <address>
                                    <?= $capacitacion['nomUsuario'] ?>                                 
                                </address>
                            </div><!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <strong>Duraci&oacute;n</strong>
                                <address>
                                    <?= $capacitacion['tiempo'] ?> Minutos                           
                                </address>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-sm-8 invoice-col">
                                <strong>Observaci&oacute;n</strong>
                                <address>
                                    <?= $capacitacion['observacion'] ?>                            
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <strong>Departamento/Ciudad</strong>
                                <address>
                                    <?= $capacitacion['nomDepto'] ?> / <?= $capacitacion['nomCiudad'] ?>                            
                                </address>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <?php
                                $fn = new Funciones();
                                $form = new formulario();
                                $form->lista(array("label" => "Colaborador", "id" => "codEmpleado"), getListaEmpleados());
                                ?>
                            </div>

                            <div class="col-xs-6">
                                <div class="modal-body p-4">
                                    <div class="text-center">                                   
                                        <canvas id="canvas"></canvas>
                                        <div class="button-list">
                                            <input type = 'hidden' id = 'codCapacitacion' name = 'codCapacitacion' value = '<?= $capacitacion['codCapacitacion'] ?>'/>
                                            <button type="button" class="btn btn-block btn-primary" id="btnFirmar"> 
                                                <i class="fa fa-pencil"></i> <span>Firmar Asistencia</span> 
                                            </button>
                                            <button type="button" class="btn btn-block btn-secondary" id="btnLimpiar"> 
                                                <i class="fa fa-eraser"></i> <span>Limpiar</span> 
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php }
                ?>
            </aside>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="js/canvas.js" type="text/javascript"></script>
        <script src="js/asistencia/asistencia.js" type="text/javascript"></script>
    </body>
</html>
<?php

function getCapacitacion($codCapacitacion) {
    $sql = "SELECT a.*, b.nomTipoCapacitacion, c.nomUsuario, d.nomCiudad, e.nomDepto
            FROM   tab_capacitaciones a 
                        LEFT JOIN tab_tipo_capacitacion b on a.codTipoCapacitacion = b.codTipoCapacitacion
                        LEFT JOIN tab_usuarios c on a.codUsuario = c.codUsuario
                        LEFT JOIN tab_ciudades d on a.codCiudad = d.codCiudad
                        LEFT JOIN tab_deptos e on d.codDepto = e.codDepto                        


            WHERE  a.codEstado = 3 
              AND  a.codCapacitacion = " . $codCapacitacion;
    $result = Conexion::obtener($sql);
    if (isset($result)) {
        return $result[0];
    }
    return null;
}

function getListaEmpleados() {
    $sql = "SELECT  a.codEmpleado, UPPER(a.nomEmpleado)
            FROM    tab_empleados a                 
            WHERE   a.codEstado = 1";
    return Conexion::obtener($sql);
}
