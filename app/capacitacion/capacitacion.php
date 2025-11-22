<?php

class Capacitacion {

    var $order = 2;
    var $tabla = "tab_capacitaciones";
    var $nombre = "Capacitacion";
    var $nombres = "Capacitaciones";
    var $mensaje = "El Capacitacion";
    var $llave = "codCapacitacion";
    var $campos = array("nomCapacitacion", "fecha", "tiempo", "asistencia", "evaluacion", "nomUsuario", "nomTipoCapacitacion", "nomCiudad", "nomEstado", "codTipoCapacitacion");
    var $columnas = array("Opciones", "Tema", "Fecha", "Tiempo", "Firmas", "Evaluaci&oacute;n", "Capacitador", "Tipo", "Ciudad", "Estado", "Porcentaje de Avance");

    function Menu() {

        $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : "EMPTY";

        switch ($state) {
            case "Vista":
                $this->vista("Registrar");
                break;

            case "Editar":
                $this->editar("");
                break;

            default:
                $this->listado();
                break;
        }
    }

    function listado() {

        $form = new formulario();

        $form->iniForm("");
        $form->botonAcciones(array(
            "link" => true,
            "state" => "Vista",
            "type" => "button",
            "boton" => "btn-primary btn-block",
            "id" => "agregarNuevo",
            "icon" => "fa fa-plus",
            "label" => "Agregar " . $this->nombre
        ));
        $form->finForm();

        $form->espacio();

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
                $url = 'index.php?cod=' . $cod . '&state=Editar&' . $this->llave . '=' . $row[$this->llave];
                $html .= '  <tr>';
                $html .= '      <td align="center">';
                $html .= '          <a href="' . $url . '"><i class="fa fa-edit" style="cursor: pointer;" title="EDITAR"></i></a>';
                $html .= '      </td>';
                $html .= $this->resolverCampos($row);
                $html .= '  </tr>';
            }
        }
        return $html;
    }

    function resolverCampos($row) {
        $empleados = max(1, (int) $this->getCantidadEmpleados());
        $asistencia = max(0, (int) $row['asistencia']);
        $porc = round(($asistencia / $empleados) * 100, 2);
        $porcentaje = min(max($porc, 0), 100);
        $colorMap = [
            3 => ['bs' => 'primary', 'bg' => 'blue'],
            4 => ['bs' => 'success', 'bg' => 'green'],
            5 => ['bs' => 'danger', 'bg' => 'red'],
        ];

        $codEstado = (int) $row['codEstado'];
        $colors = $colorMap[$codEstado] ?? ['bs' => 'default', 'bg' => 'gray'];

        $html = '';
        foreach ($this->campos as $cam) {
            $html .= '<td>';
            switch ($cam) {
                case 'nomEstado':
                    $html .= '<span class="label label-' . $colors['bs'] . '">' . $row[$cam] . '</span>';
                    break;
                case 'codTipoCapacitacion':
                    $html .= '  <div class="progress xs progress-striped active">
                                    <div class="progress-bar progress-bar-' . $colors['bs'] . '" style="width:' . $porcentaje . '%"></div>
                                </div><span class="badge bg-' . $colors['bg'] . '">' . $porcentaje . '%</span>';
                    break;
                default:
                    $html .= $row[$cam];
            }
            $html .= '</td>';
        }
        return $html;
    }

    function editar() {

        $info = $this->getListado($_REQUEST[$this->llave]);
        if ($info) {
            $info = $info[0];
            $_REQUEST = array_merge($_REQUEST, $info);
        }

        $this->vista("Actualizar");
    }

    function vista($accion) {

        $codigo = "";
        if (isset($_REQUEST['codCapacitacion'])) {
            $codigo = $_REQUEST['codCapacitacion'];
        }


        if (!isset($_REQUEST['asistencia'])) {
            $_REQUEST['asistencia'] = 0;
        }

        if (!isset($_REQUEST['tiempo'])) {
            $_REQUEST['tiempo'] = 0;
        }

        if (!isset($_REQUEST['observacion'])) {
            $_REQUEST['observacion'] = '';
        }

        if (!isset($_REQUEST['codDepto'])) {
            $_REQUEST['codDepto'] = '';
        }

        if (!isset($_REQUEST['codEstado'])) {
            $_REQUEST['codEstado'] = 3;
        }

        $disabled = ($_REQUEST['codEstado'] == 4 || $_REQUEST['codEstado'] == 5) ? 1 : 0;

        $fn = new Funciones();
        $form = new formulario();

        $params['ruta'] = "js/main";
        $form->linkJs($params);

        $params2['ruta'] = "js/capacitacion/capacitacion";
        $form->linkJs($params2);

        $params2['ruta'] = "js/evaluacion/evaluacion";
        $form->linkJs($params2);

        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");

        echo '              <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Capacitaci&oacute;n #' . $codigo . '</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Evaluaciones</a></li>                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">';
        $form->iniForm("");
        $this->tabCapacitacion($fn, $form, $disabled, $accion);
        $form->finForm();
        echo '                  </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_2">';
        $form->iniForm("");
        $this->tabEvaluaciones($fn, $form, $disabled, $accion);
        $form->finForm();
        echo'                       </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->';

        $form->finDiv();
        $form->finDiv();
    }

    function tabCapacitacion($fn, $form, $disabled, $accion) {
        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Tema", "id" => "nomCapacitacion", "required" => "1", "disabled" => $disabled));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->datePicker(array("label" => "Fecha", "id" => "fecha", "required" => "1", "disabled" => $disabled));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Tiempo", "type" => "text", "id" => "tiempo", "required" => "1", "disabled" => $disabled));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-2");
        $form->text(array("label" => "Asistencia", "type" => "text", "id" => "asistencia", "readonly" => 1));
        $form->finDiv();

        $form->inicioDiv("col-lg-2");
        $form->text(array("label" => "Evaluacion", "type" => "text", "id" => "evaluacion", "readonly" => 1));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Tipo", "id" => "codTipoCapacitacion", "required" => "1", "disabled" => $disabled), $fn->getLista("codTipoCapacitacion", "nomTipoCapacitacion", "tab_tipo_capacitacion", null));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Capacitador", "id" => "codUsuario", "required" => "1", "disabled" => $disabled), $fn->getLista("codUsuario", "nomUsuario", "tab_usuarios", array("perfil" => true, "codPerfil" => 2)));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Departamento ", "id" => "codDepto", "required" => "1", "disabled" => $disabled, "onchange" => "getListaCiudades()"), $this->getListaDeptos(1));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Ciudad", "id" => "codCiudad", "required" => "1", "disabled" => $disabled), $this->getListaCiudades($_REQUEST['codDepto']));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Estado", "id" => "codEstado", "required" => "1", "disabled" => $disabled), $fn->getLista("codEstado", "nomEstado", "tab_estados", array("tipoEstado" => true, "codTipoEstado" => 2)));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-12");
        $form->textArea(array("label" => "Observaci&oacute;n", "type" => "text", "id" => "observacion", "rows" => 4, "disabled" => $disabled));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        if ($disabled == 0) {
            $form->Hidden(array("name" => "codPage", "value" => $_REQUEST['cod']));
            if ($accion == "Actualizar") {
                $form->Hidden(array("name" => "codCapacitacion", "value" => $_REQUEST['codCapacitacion']));
            }
            $form->botonAcciones(array(
                "link" => false,
                "type" => "button",
                "boton" => "btn-primary",
                "id" => "state",
                "icon" => "fa fa-plus",
                "label" => $accion,
                "onclick" => "return validarCapacitacion()"
            ));
        }
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

        $this->generarExcelCapacitacion($form);
    }

    function tabEvaluaciones($fn, $form, $disabled) {

        if (!isset($_REQUEST['codEstadoCE'])) {
            $_REQUEST['codEstadoCE'] = 1;
        }

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-2");
        $form->lista(array("label" => "Tipo Evaluaci&oacute;n", "id" => "codTipoEvaluacion", "required" => "1", "disabled" => $disabled), $fn->getLista("codTipoEvaluacion", "nomTipoEvaluacion", "tab_tipo_evaluacion", null));
        $form->finDiv();

        $form->inicioDiv("col-lg-6");
        $form->lista(array("label" => "Evaluaci&oacute;n", "id" => "codEvaluacion", "required" => "1", "disabled" => $disabled), $fn->getLista("codEvaluacion", "nomEvaluacion", "tab_evaluaciones", array("codEstado" => 1)));
        $form->finDiv();

        $form->inicioDiv("col-lg-2");
        $form->datePicker(array("label" => "Fecha Limite", "id" => "fechaLimite", "required" => "1", "disabled" => $disabled));
        $form->finDiv();

        $form->inicioDiv("col-lg-2");
        echo '  <div class="form-group">
                    <div class="radio">
                        <label class="">
                            <div class="iradio_minimal checked" aria-checked="true" aria-disabled="false" style="position: relative;"><input type="radio" name="esObligatoria" id="esObligatoriaSi" value="1" checked="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                            Es obligatoria
                        </label>
                    </div>
                    <div class="radio">
                        <label class="">
                            <div class="iradio_minimal" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="radio" name="esObligatoria" id="esObligatoriaNo" value="0" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                            No es obligatoria
                        </label>
                    </div>                   
                </div>';
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-2");
        $form->text(array("label" => "Orden", "type" => "text", "id" => "ordenEvaluacion", "disabled" => $disabled));
        $form->finDiv();

        $form->inicioDiv("col-lg-2");
        $form->lista(array("label" => "Estado", "id" => "codEstadoCE", "required" => "1", "disabled" => $disabled), $fn->getLista("codEstado", "nomEstado", "tab_estados", array("tipoEstado" => true, "codTipoEstado" => 1)));
        $form->finDiv();

        $form->finDiv();

        $form->espacio();

        $codCapacitacion = $_REQUEST['codCapacitacion'];
        $evaluaciones = $this->evaluacionesAsignadas($codCapacitacion);

        echo '      <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Tipo Evaluaci&oacute;n</th>
                                    <th>Evaluaci&oacute;n</th>
                                    <th>Fecha Limite</th>
                                    <th>Obligatorio</th>
                                    <th>Orden</th>
                                    <th>Estado</th>
                                </tr>';
        if (isset($evaluaciones) && count($evaluaciones) > 0) {
            foreach ($evaluaciones as $row) {
                echo '          <tr>
                                    <td>' . $row['nomTipoEvaluacion'] . '</td>
                                    <td>' . $row['nomEvaluacion'] . '</td>
                                    <td>' . $row['fechaLimite'] . '</td>
                                    <td>' . $row['obligatoria'] . '</td>
                                    <td><span class="label label-primary">' . $row['ordenEvaluacion'] . '</span></td>
                                    <td>' . $row['nomEstado'] . '</td>
                                </tr>';
            }
        }
        echo '                  <tr>
                                    <th>Tipo Evaluaci&oacute;n</th>
                                    <th>Evaluaci&oacute;n</th>
                                    <th>Fecha Limite</th>
                                    <th>Obligatorio</th>
                                    <th>Orden</th>
                                    <th>Estado</th>
                                </tr>                  
                            </tbody>
                        </table>
                    </div>';

        $form->inicioDiv("button-list");
        $form->center();
        if ($disabled == 0) {
            $form->Hidden(array("name" => "codPage", "value" => $_REQUEST['cod']));
            $form->Hidden(array("name" => "codCapacitacionCE", "value" => $codCapacitacion));
            $form->botonAcciones(array(
                "link" => false,
                "type" => "button",
                "boton" => "btn-primary",
                "id" => "state2",
                "icon" => "fa fa-plus",
                "label" => "Incluir",
                "onclick" => "return validarEvaluacion()"
            ));
        }
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
    }

    function generarExcelCapacitacion($form) {

        echo "<hr/>";
        $form->iniForm(array("id" => "Formulario2", "name" => "Formulario2", "action" => "app/capacitacion/generador/excel_capacitacion.php"));

        echo'&nbsp;&nbsp;&nbsp;';
        $form->botonAcciones(array(
            "link" => false,
            "type" => "submit",
            "boton" => "btn btn-success",
            "id" => "generar",
            "icon" => "fa fa-file-excel-o",
            "label" => "Generar Reporte Excel"
        ));

        $form->Hidden(array("name" => "codCapacitacionEx", "value" => $_REQUEST['codCapacitacion']));

        $form->finForm();
    }

    function getListado($cod) {
        $sql = "SELECT  a.*,
                        CASE 
                            WHEN a.evaluacion = 1 THEN 'SI'
                            ELSE 'NO'
                        END AS evaluacion,  
                        b.nomUsuario,
                        c.nomTipoCapacitacion,
                        d.codDepto,
                        d.nomCiudad,
                        e.nomEstado
                FROM    " . $this->tabla . " a 
                            LEFT JOIN tab_usuarios b on a.codUsuario = b.codUsuario
                            LEFT JOIN tab_tipo_capacitacion c on a.codTipoCapacitacion = c.codTipoCapacitacion
                            LEFT JOIN tab_ciudades d on a.codCiudad = d.codCiudad
                            LEFT JOIN tab_estados e ON a.codEstado = e.codEstado";
        if ($cod) {
            $sql .= " WHERE a." . $this->llave . " = '" . $cod . "' ";
        }
        $sql .= " ORDER BY a.fechaCreacion ASC ";
        return Conexion::obtener($sql);
    }

    function getListaDeptos($codPais) {
        if (isset($codPais)) {
            $sql = "SELECT  d.codDepto, UPPER(d.nomDepto) 
                    FROM    tab_deptos d                                
                    WHERE   d.codPais = $codPais";
            return Conexion::obtener($sql);
        }
        return null;
    }

    function getListaCiudades($codDepto) {
        if (isset($codDepto) && $codDepto !== '') {
            $sql = "SELECT  c.codCiudad, UPPER(c.nomCiudad)
                    FROM    tab_ciudades c                 
                    WHERE   c.codDepto = " . $codDepto;
            return Conexion::obtener($sql);
        }
        return null;
    }

    function getCantidadEmpleados() {
        $sql = "SELECT  count(*) cantidad FROM    tab_empleados";
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0]['cantidad'];
        }
        return 0;
    }

    function evaluacionesAsignadas($codCapacitacion) {
        $sql = "SELECT  a.fechaLimite,
                        a.ordenEvaluacion,
                        CASE 
                            WHEN a.esObligatoria = 1 THEN 'SI'
                            ELSE 'NO'
                        END AS obligatoria,            
                        b.nomEvaluacion,
                        b.cantidadPreguntas,
                        c.nomTipoEvaluacion,
                        d.nomEstado
                  FROM  tab_capacitacion_evaluacion a
                                LEFT JOIN tab_evaluaciones b on a.codEvaluacion = b.codEvaluacion
                                LEFT JOIN tab_tipo_evaluacion c on b.codTipoEvaluacion = c.codTipoEvaluacion
                                LEFT JOIN tab_estados d ON a.codEstado = d.codEstado
                 WHERE  a.codCapacitacion = " . $codCapacitacion;
        return Conexion::obtener($sql);
    }
}

$capacitacion = new Capacitacion();
$capacitacion->Menu();
