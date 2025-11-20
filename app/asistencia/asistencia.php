<?php

class Asistencia {

    var $order = 2;
    var $tabla = "tab_asistencias";
    var $nombre = "Asistencia";
    var $nombres = "Asistencias";
    var $mensaje = "El Asistencia";
    var $llave = "codAsistencia";
    var $campos = array("nomEmpleado", "nomCapacitacion", "fecha", "nomUsuario", "fechaCreacion");
    var $columnas = array("Opciones", "Colaborador", "Capacitaci&oacute;n", "Fecha Capacitaci&oacute;n", "Capacitador", "Fecha Firmado");

    function Menu() {

        $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : "EMPTY";

        switch ($state) {

            case "Ver":
                $this->vista();
                break;

            default:
                $this->listado();
                break;
        }
    }

    function listado() {

        $form = new formulario();
        $fn = new Funciones();

        $params['ruta'] = "js/main";
        $form->linkJs($params);

        $params2['ruta'] = "js/asistencia/asistencia";
        $form->linkJs($params2);

        $form->inicioDiv("row");
        $form->inicioDiv("col-xs-12");
        $form->inicioDiv("box");
        $form->boxHeader("Listado de " . $this->nombres);

        $form->inicioDiv("box-body");

        $form->iniForm("");

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Capacitador", "id" => "codUsuario"), $fn->getLista("codUsuario", "nomUsuario", "tab_usuarios", array("perfil" => true, "codPerfil" => 2)));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->datePicker(array("label" => "Fecha Inicio", "id" => "fechaIni"));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->datePicker(array("label" => "Fecha Fin", "id" => "fechaFin"));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Tipo", "id" => "codTipoCapacitacion"), $fn->getLista("codTipoCapacitacion", "nomTipoCapacitacion", "tab_tipo_capacitacion", null));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Capacitaci&oacute;n", "id" => "codCapacitacion"), $fn->getLista("codCapacitacion", "nomCapacitacion", "tab_capacitaciones", null));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Colaborador", "id" => "codEmpleado"), $fn->getLista("codEmpleado", "nomEmpleado", "tab_empleados", null));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Estado", "id" => "codEstado"), $fn->getLista("codEstado", "nomEstado", "tab_estados", array("tipoEstado" => true, "codTipoEstado" => 2)));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        $form->Hidden(array("name" => "codPage", "value" => $_REQUEST['cod']));
        $form->botonAcciones(array(
            "link" => false,
            "type" => "submit",
            "boton" => "btn btn-primary",
            "id" => "state",
            "icon" => "fa fa-search",
            "label" => "Consultar Asistencias",
            "onclick" => "return verificarConsultaAsistencias()"
        ));
        $form->finCenter();
        $form->finDiv();

        $form->finForm();
        $form->finDiv();

        $form->espacio();
        echo $this->generarTablaAsistencias($form);
        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
    }

    function generarTablaAsistencias($form) {

        $list = $this->getListado(null);
        if (isset($list) && count($list) > 0) {

            echo "<hr/>";
            $form->iniForm(array("id" => "Formulario2", "name" => "Formulario2", "action" => "app/asistencia/generador/excel_asistencias.php"));

            echo'&nbsp;&nbsp;&nbsp;';
            $form->botonAcciones(array(
                "link" => false,
                "type" => "submit",
                "boton" => "btn btn-success",
                "id" => "generar",
                "icon" => "fa fa-file-excel-o",
                "label" => "Generar Reporte Excel"
            ));

            if (!isset($_REQUEST['codUsuario'])) {
                $_REQUEST['codUsuario'] = '';
            }

            if (!isset($_REQUEST['fechaIni'])) {
                $_REQUEST['fechaIni'] = '';
            }

            if (!isset($_REQUEST['fechaFin'])) {
                $_REQUEST['fechaFin'] = '';
            }

            if (!isset($_REQUEST['codTipoCapacitacion'])) {
                $_REQUEST['codTipoCapacitacion'] = '';
            }

            if (!isset($_REQUEST['codCapacitacion'])) {
                $_REQUEST['codCapacitacion'] = '';
            }

            if (!isset($_REQUEST['codEmpleado'])) {
                $_REQUEST['codEmpleado'] = '';
            }

            if (!isset($_REQUEST['codEstado'])) {
                $_REQUEST['codEstado'] = '';
            }

            $form->Hidden(array("name" => "codUsuarioEx", "value" => $_REQUEST["codUsuario"]));
            $form->Hidden(array("name" => "fechaIniEx", "value" => $_REQUEST['fechaIni']));
            $form->Hidden(array("name" => "fechaFinEx", "value" => $_REQUEST['fechaFin']));
            $form->Hidden(array("name" => "codTipoCapacitacionEx", "value" => $_REQUEST['codTipoCapacitacion']));
            $form->Hidden(array("name" => "codCapacitacionEx", "value" => $_REQUEST['codCapacitacion']));
            $form->Hidden(array("name" => "codEmpleadoEx", "value" => $_REQUEST['codEmpleado']));
            $form->Hidden(array("name" => "codEstadoEx", "value" => $_REQUEST['codEstado']));

            $form->finForm();
        }
        $form->espacio();
        echo $this->tablaDeContenido($list);
    }

    function tablaDeContenido($list) {
        $ths = "";

        foreach ($this->columnas as $col) {
            $ths .= '      <th>' . $col . '</th>';
        }
        $html = '<div class="box-body table-responsive">';
        $html .= '  <table id="principal-datatable" class="table table-bordered table-striped">';
        $html .= '      <thead>';
        $html .= '          <tr>';
        $html .= $ths;
        $html .= '          </tr>';
        $html .= '      </thead>';
        $html .= '      <tbody>';
        $html .= $this->datosTablaDeContenido($list);
        $html .= '      </tbody>';
        $html .= '      <tfoot>';
        $html .= '          <tr>';
        $html .= $ths;
        $html .= '          </tr>';
        $html .= '      </tfoot>';
        $html .= '  </table>';
        $html .= '</div>';
        return $html;
    }

    function datosTablaDeContenido($list) {

        $html = "";

        $cod = "";
        if (isset($_REQUEST['cod'])) {
            $cod = $_REQUEST['cod'];
        }

        if (isset($list) && count($list) > 0) {
            foreach ($list as $row) {
                $url = 'index.php?cod=' . $cod . '&state=Ver&' . $this->llave . '=' . $row[$this->llave];
                $html .= '  <tr>';
                $html .= '      <td align="center">';
                $html .= '          <a href="' . $url . '"><i class="fa fa-search" style="cursor: pointer;" title="Ver"></i></a>';
                $html .= '      </td>';
                $html .= $this->resolverCampos($row);
                $html .= '  </tr>';
            }
        }
        return $html;
    }

    function resolverCampos($row) {
        $color = match ($row['codEstado']) {
            3 => 'primary',
            4 => 'success',
            5 => 'danger'
        };
        $html = '';
        foreach ($this->campos as $cam) {
            $html .= '<td>';
            switch ($cam) {
                case 'nomCapacitacion':
                    $html .= $row[$cam] . '  <span class="label label-' . $color . '">' . $row['nomEstado'] . '</span>';
                    break;
                default:
                    $html .= $row[$cam];
            }
            $html .= '</td>';
        }
        return $html;
    }

    function vista() {

        $info = $this->getListado($_REQUEST[$this->llave]);
        if ($info) {
            $info = $info[0];
            $_REQUEST = array_merge($_REQUEST, $info);
        }

        $form = new formulario();
        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");
        $form->inicioDiv("box box-primary");

        $form->inicioDiv("box-body");

        echo '  <div class="row">
                    <div class="col-xs-12">
                        <h2 class="page-header">
                            <i class="fa fa-bullhorn"></i>&nbsp;&nbsp;Asistencia ' . $_REQUEST['nomEmpleado'] . '
                            <small class="pull-right"><strong>Fecha Realizaci&oacute;n: </strong>' . $_REQUEST['fecha'] . '</small>
                        </h2>
                    </div>
                </div>

                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <strong>Tema Capacitaci&oacute;n</strong>
                        <address>' . $_REQUEST['nomCapacitacion'] . '</address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <strong>Tipo de Capacitaci&oacute;n</strong>
                        <address>' . $_REQUEST['nomTipoCapacitacion'] . '</address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <strong>Capacitador</strong>
                        <address>' . $_REQUEST['nomUsuario'] . '</address>
                    </div>
                </div>

                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <strong>Duraci&oacute;n</strong>
                        <address>' . $_REQUEST['tiempo'] . ' Minutos</address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <strong>Departamento/Ciudad</strong>
                        <address>' . $_REQUEST['nomDepto'] . '/' . $_REQUEST['nomCiudad'] . '</address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <strong>Fecha Firmado</strong>
                        <address>' . $_REQUEST['fechaCreacion'] . '</address>
                    </div>
                </div>
                
                <div class="row invoice-info">                    
                    <div class="col-sm-12 invoice-col">
                        <strong>Observaci&oacute;n</strong>
                        <address>' . $_REQUEST['observacion'] . '</address>
                    </div>
                </div>

                <div class="row">';

        $form->inicioDiv("col-xs-12");
        $form->lista(array("label" => "Colaborador ", "id" => "codEmpleado", "disabled" => "1"), $this->getListaEmpleados($_REQUEST['codEmpleado']));
        $form->finDiv();

        echo'   </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="text-center">                                   
                                <div class="firmaEmpleado">
                                    <img src="' . PATH_REL_FIRMAS . $_REQUEST['codEmpleado'] . "_" . $_REQUEST['codCapacitacion'] . '.png" />
                                </div>
                            </div>
                        </div>
                    </div>',
        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        $form->botonAcciones(array(
            "link" => true,
            "type" => "button",
            "boton" => "btn-default",
            "id" => "back",
            "icon" => "fa fa-fast-backward",
            "label" => "Regresar a " . $this->nombres
        ));
        $form->finCenter();
        $form->finDiv();

        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
    }

    function getListado($cod) {
        $sql = "SELECT  a.*, 
                        b.nomEmpleado, 
                        c.nomCapacitacion, 
                        c.fecha,
                        c.tiempo,
                        c.observacion,
                        c.codEstado,
                        d.nomTipoCapacitacion,
                        e.nomUsuario,
                        f.nomCiudad,
                        g.nomDepto,
                        h.nomEstado
                  FROM  tab_asistencias a
                            LEFT JOIN tab_empleados b on a.codEmpleado = b.codEmpleado
                            LEFT JOIN tab_capacitaciones c on a.codCapacitacion = c.codCapacitacion
                            LEFT JOIN tab_tipo_capacitacion d on c.codTipoCapacitacion = d.codTipoCapacitacion          
                            LEFT JOIN tab_usuarios e on c.codUsuario = e.codUsuario
                            LEFT JOIN tab_ciudades f on c.codCiudad = f.codCiudad
                            LEFT JOIN tab_deptos g on f.codDepto = g.codDepto
                            LEFT JOIN tab_estados h on c.codEstado = h.codEstado
                 WHERE  1 = 1";

        if ($cod) {
            $sql .= " AND a." . $this->llave . " = '" . $cod . "' ";
        }

        if (isset($_REQUEST['codUsuario']) && $_REQUEST['codUsuario'] != '') {
            $sql .= " AND c.codUsuario = '" . $_REQUEST['codUsuario'] . "'";
        }

        if (isset($_REQUEST['fechaIni']) && $_REQUEST['fechaIni'] != '' && isset($_REQUEST['fechaFin']) && $_REQUEST['fechaFin'] != '') {
            $sql .= " AND c.fecha between '" . $_REQUEST['fechaIni'] . "' AND '" . $_REQUEST['fechaFin'] . "'";
        }

        if (isset($_REQUEST['codTipoCapacitacion']) && $_REQUEST['codTipoCapacitacion'] != '') {
            $sql .= " AND c.codTipoCapacitacion = '" . $_REQUEST['codTipoCapacitacion'] . "'";
        }

        if (isset($_REQUEST['codCapacitacion']) && $_REQUEST['codCapacitacion'] != '') {
            $sql .= " AND a.codCapacitacion = '" . $_REQUEST['codCapacitacion'] . "'";
        }

        if (isset($_REQUEST['codEmpleado']) && $_REQUEST['codEmpleado'] != '') {
            $sql .= " AND a.codEmpleado = '" . $_REQUEST['codEmpleado'] . "'";
        }

        if (isset($_REQUEST['codEstado']) && $_REQUEST['codEstado'] != '') {
            $sql .= " AND c.codEstado = '" . $_REQUEST['codEstado'] . "' ";
        }

        $sql .= " ORDER BY c.codCapacitacion DESC ";
        return Conexion::obtener($sql);
    }

    function getListaEmpleados($codEmpleado) {
        $sql = "SELECT  a.codEmpleado, UPPER(a.nomEmpleado)
                  FROM  tab_empleados a                 
                 WHERE  a.codEstado = 1 
                   AND  codEmpleado = " . $codEmpleado;
        return Conexion::obtener($sql);
    }
}

$asistencia = new Asistencia();
$asistencia->Menu();
