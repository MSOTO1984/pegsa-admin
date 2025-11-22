<?php
date_default_timezone_set('America/Bogota');
include_once 'lib/params.php';
include_once 'lib/conexion.php';
include_once 'lib/helper.php';
include_once 'lib/funciones.php';
include_once 'lib/formulario.php';
include_once 'lib/consultas.php';

Conexion::conectar();

$capacitacion = null;
$codEmpleado = null;
if (isset($_GET['codCapacitacion'])) {

    $codEmpleado = $_GET['codEmpleado'];
    $codCapacitacion = $_GET['codCapacitacion'];

    $Cn = new Consultas();

    $firmado = (boolean) $Cn->validarFirmaEmpleado($codCapacitacion, $codEmpleado);
    $capacitacion = $Cn->getCapacitacion($codCapacitacion);

    $evaluacion = (int) $capacitacion['evaluacion'];
    $evaluado = $Cn->verificarEvaluacionDiligenciadas($codCapacitacion, $codEmpleado);
    if ($evaluacion > 0 && isset($evaluado) && count($evaluado) == $evaluacion && !$firmado) {
        echo "  <script>
                    alert('Evaluacion Dilinguenciada con exito.');
                    window.location.href = 'asistencia.php?codCapacitacion=$codCapacitacion&codEmpleado=$codEmpleado';
                </script>";
        exit;
    }

    $evaluaciones = $Cn->evaluacionesCargadas($codCapacitacion);

    $evaluadoNormalizada = is_array($evaluado) ? $evaluado : [];
    $evaluacionesNormalizada = is_array($evaluaciones) ? $evaluaciones : [];

    $listaEvaluadas = array_column($evaluadoNormalizada, 'codEvaluacion');
    $listaEvaluaciones = array_column($evaluacionesNormalizada, 'codEvaluacion');

    $pendientes = array_diff($listaEvaluaciones, $listaEvaluadas);
    if (empty($pendientes)) {
        echo " <script> alert('Evaluacion Dilinguenciada con exito.'); ";
        echo " window.location.href = 'asistencia.php?codCapacitacion=$codCapacitacion&codEmpleado=$codEmpleado'; </script>";
        exit;
    }

    $codEvaluacionSiguiente = array_values($pendientes)[0];
    $siguienteEvaluacion = $Cn->obtenerEvaluacion($codEvaluacionSiguiente);
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
                if (!isset($capacitacion) && !isset($codEmpleado)) {
                    include_once 'lib/404.php';
                } else {
                    ?>
                    <div class="pad margin no-print">
                        <div class="alert alert-info" style="margin-bottom: 0!important;">
                            <i class="fa fa-info"></i>
                            <b>Nota:</b>&nbsp; Recuerde diligenciar la evaluaci&oacute;n antes de continuar el proceso.
                        </div>
                    </div>
                    <section class="content invoice">

                        <div class="row">
                            <div class="col-xs-12">
                                <h2 class="page-header">
                                    <i class="fa fa-university"></i>&nbsp; <?= $capacitacion['nomCapacitacion'] ?> | Evaluaci&oacute;n
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
                            <div class="col-xs-12">
                                <h2 class="page-header">
                                    <i class="fa fa-trophy"></i>&nbsp; <?= $siguienteEvaluacion['nomEvaluacion'] ?>
                                </h2>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-sm-10 invoice-col">
                                <strong>Descripci&oacute;n</strong>
                                <address>
                                    <?= $siguienteEvaluacion['descripcion'] ?>                            
                                </address>
                            </div>
                            <div class="col-sm-2 invoice-col">
                                <strong>Tipo Evaluaci&oacute;n</strong>
                                <address>
                                    <?= $siguienteEvaluacion['nomTipoEvaluacion'] ?>                            
                                </address>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-body">
                                    <?php
                                    $preguntas = $Cn->obtenerPreguntasEvaluacion($codEvaluacionSiguiente);
                                    if (isset($preguntas) && count($preguntas) > 0) {
                                        foreach ($preguntas as $row) {
                                            echo'  <div class="form-group">';
                                            $name = 'preguntas_' . $row['codPregunta'];
                                            if ($row['codTipoPregunta'] == 4 || $row['codTipoPregunta'] == 5) {
                                                echo '     <label for="' . $name . '">' . $row['ordenPregunta'] . '. ' . $row['enunciado'] . '</label>';
                                                echo '     <input type="text" name="' . $name . '" id="' . $name . '" class="form-control" placeholder="Respuesta ...">';
                                            } else {
                                                echo '     <b>' . $row['ordenPregunta'] . '. ' . $row['enunciado'] . '</b>';
                                                $respuestas = $Cn->obtenerRespuestasPregunta($row['codPregunta']);
                                                if (isset($respuestas) && count($preguntas) > 0) {
                                                    foreach ($respuestas as $row2) {
                                                        $count = 1;
                                                        $id = 'pregunta_' . $row2['codRespuesta'] . '_' . $count;
                                                        if ($row['codTipoPregunta'] == 1 || $row['codTipoPregunta'] == 3) {
                                                            echo ' <div class="radio">
                                                                        <label for="' . $id . '">
                                                                            <div class="iradio_minimal" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                                                <input type="radio" name="' . $name . '" id="' . $id . '" value="' . $row2['codRespuesta'] . '" style="position: absolute; opacity: 0;">
                                                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                                            </div>&nbsp;&nbsp;' . $row2['textoRespuesta'] . '
                                                                        </label>
                                                                    </div>';
                                                        } else if ($row['codTipoPregunta'] == 2) {
                                                            echo ' <div class="checkbox">
                                                                        <label for="' . $id . '">
                                                                            <div class="icheckbox_minimal" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                                                <input type="checkbox" name="' . $id . '" id="' . $id . '" value="' . $row2['codRespuesta'] . '" style="position: absolute; opacity: 0;">
                                                                                <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                                            </div>&nbsp;&nbsp;' . $row2['textoRespuesta'] . '
                                                                        </label>
                                                                    </div>';
                                                        }
                                                        $count++;
                                                    }
                                                }
                                            }
                                            echo ' </div>';
                                        }
                                    }
                                    ?>
                                    <div class="box-footer">
                                        <input type='hidden' id='codEvaluacion' name ='codEvaluacion' value='<?= $codEvaluacionSiguiente ?>'/>
                                        <input type='hidden' id='codCapacitacion' name ='codCapacitacion' value='<?= $codCapacitacion ?>'/>
                                        <input type='hidden' id='codEmpleado' name ='codEmpleado' value='<?= $codEmpleado ?>'/>
                                        <button type="submit" class="btn btn-primary" id="state" name="state" value="respuestas" onclick="return verificarRespuestasDiligenciadas()">
                                            <i class="fa fa-plus"></i> &nbsp;Enviar Evaluaci&oacute;n
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
                }
                ?>
            </aside>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="js/evaluacion/evaluacion.js" type="text/javascript"></script>
    </body>
</html>
<?php

