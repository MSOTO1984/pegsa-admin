<?php
$fn = new Funciones();

$miCodigo = "";
if (!$_SESSION[MISESSION]['isChangePass']) {
    $miCodigo = 202;
} else {
    $miCodigo = isset($_REQUEST['cod']) ? base64_decode($_REQUEST['cod']) : "EMPTY";
}

$menuCanvas = $fn->getMenu($miCodigo);
if (!empty($menuCanvas)) {
    $menuCanvas = $menuCanvas[0];
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
        <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />    
        <link href="css/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />      
    </head>
    <body class="skin-blue">
        <?php include_once 'lib/header.php'; ?>       
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php include_once 'lib/menu.php'; ?>
            <aside class="right-side">
                <section class="content-header">
                    <?php
                    $oPadre = "Inicio";
                    $option = "Bienvenido";
                    if ($miCodigo !== 'EMPTY') {
                        $oPadre = $menuCanvas['padre'];
                        $option = $menuCanvas['nomOption'];
                    }
                    ?>
                    <h1>
                        <?= $oPadre ?>
                        <small><?= $option ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> <?= $oPadre ?></a></li>
                        <li class="active"><?= $option ?></li>
                    </ol>
                </section>
                <section class="content">
                    <?php
                    if ($miCodigo == 'EMPTY') {
                        include_once 'app/inicio/inicio.php';
                    } else {
                        include_once 'app/' . $menuCanvas['rutOption'] . '.php';
                    }
                    ?>
                </section>
            </aside>
        </div>

        <!-- jQuery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <!-- Bootstrap -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>

        <!-- DataTables -->
        <script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

        <!-- Bootstrap Datepicker -->
        <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="js/plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
        <!--script src="js/AdminLTE/demo.js" type="text/javascript"></script-->

        <script type="text/javascript">
            $(function () {
                $("#principal-datatable").dataTable();
                $('#secundary-datatable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
                $('.datepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd/mm/yyyy',
                    language: 'es'
                });
            });
        </script>
    </body>
</html>
