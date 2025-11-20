<?php

class Asistencia {

    var $order = 2;
    var $tabla = "tab_asistencias";
    var $nombre = "Asistencia";
    var $nombres = "Asistencias";
    var $mensaje = "El Asistencia";
    var $llave = "codAsistencia";
    var $campos = array("nomCapacitacion", "nomEmpleado", "nomUsuario", "fechaCreacion");
    var $columnas = array("Opciones", "Tema", "Empleado", "Capacitador", "Fecha Firmado");

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

        $form->inicioDiv("row");
        $form->inicioDiv("col-xs-12");
        $form->inicioDiv("box");
        $form->boxHeader("Listado de " . $this->nombres);
        echo $this->tablaDeContenido();
        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
    }

    function tablaDeContenido() {
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
        $html .= $this->datosTablaDeContenido();
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

    function datosTablaDeContenido() {

        $html = "";

        $cod = "";
        if (isset($_REQUEST['cod'])) {
            $cod = $_REQUEST['cod'];
        }

        $list = $this->getListado(null);
        if (isset($list) && count($list) > 0) {
            foreach ($list as $row) {
                $url = 'index.php?cod=' . $cod . '&state=Ver&' . $this->llave . '=' . $row[$this->llave];
                $html .= '  <tr>';
                $html .= '      <td align="center">';
                $html .= '          <a href="' . $url . '"><i class="fa fa-search" style="cursor: pointer;" title="Ver"></i></a>';
                $html .= '      </td>';
                foreach ($this->campos as $cam) {
                    $html .= '  <td>' . $row[$cam] . '</td>';
                }
                $html .= '  </tr>';
            }
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
                        d.nomTipoCapacitacion,
                        e.nomUsuario,
                        f.nomCiudad,
                        g.nomDepto
                  FROM  tab_asistencias a
                            LEFT JOIN tab_empleados b on a.codEmpleado = b.codEmpleado
                            LEFT JOIN tab_capacitaciones c on a.codCapacitacion = c.codCapacitacion
                            LEFT JOIN tab_tipo_capacitacion d on c.codTipoCapacitacion = d.codTipoCapacitacion          
                            LEFT JOIN tab_usuarios e on c.codUsuario = e.codUsuario
                            LEFT JOIN tab_ciudades f on c.codCiudad = f.codCiudad
                            LEFT JOIN tab_deptos g on f.codDepto = g.codDepto";
        if ($cod) {
            $sql .= " WHERE a." . $this->llave . " = '" . $cod . "' ";
        }
        $sql .= " ORDER BY a.fechaCreacion ASC ";
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
