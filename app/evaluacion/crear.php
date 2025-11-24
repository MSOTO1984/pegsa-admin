<?php

class CrearEvaluacion {

    var $order = 2;
    var $tabla = "tab_evaluaciones";
    var $nombre = "Evaluacion";
    var $nombres = "Evaluaciones";
    var $mensaje = "La Evaluacion";
    var $llave = "codEvaluacion";
    var $campos = array("nomEvaluacion", "cantidadPreguntas", "notaMaxima", "nomTipoEvaluacion", "nomEstado");
    var $columnas = array("Opciones", "Evaluaci&oacute;n", "Cantidad Preguntas", "Nota Maxima", "Tipo Evaluaci&oacute;n", "Estado");

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
        if (isset($_REQUEST['codEvaluacion'])) {
            $codigo = $_REQUEST['codEvaluacion'];
        }

        if (!isset($_REQUEST['cantidadPreguntas'])) {
            $_REQUEST['cantidadPreguntas'] = 0;
        }

        if (!isset($_REQUEST['notaMaxima'])) {
            $_REQUEST['notaMaxima'] = 0;
        }

        if (!isset($_REQUEST['codTipoEvaluacion'])) {
            $_REQUEST['codTipoEvaluacion'] = 3;
        }

        if (!isset($_REQUEST['descripcion'])) {
            $_REQUEST['descripcion'] = '';
        }

        if (!isset($_REQUEST['codEstado'])) {
            $_REQUEST['codEstado'] = 1;
        }

        $disabled = ($_REQUEST['codEstado'] == 4 || $_REQUEST['codEstado'] == 5) ? 1 : 0;

        $fn = new Funciones();
        $form = new formulario();

        $params['ruta'] = "js/main";
        $form->linkJs($params);

        $params2['ruta'] = "js/evaluacion/evaluacion";
        $form->linkJs($params2);

        $params2['ruta'] = "js/evaluacion/evaluacion";
        $form->linkJs($params2);

        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");

        echo '              <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Evaluaci&oacute;n #' . $codigo . '</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Preguntas</a></li>                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">';
        $form->iniForm("");
        $this->tabEvaluacion($fn, $form, $disabled, $accion);
        $form->finForm();
        echo '                  </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_2">';
        $form->iniForm("");
        $this->tabPreguntas($fn, $form, $disabled, $accion);
        $form->finForm();
        echo'                       </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->';

        $form->finDiv();
        $form->finDiv();
    }

    function tabEvaluacion($fn, $form, $disabled, $accion) {
        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Evaluaci&oacute;n", "id" => "nomEvaluacion", "required" => "1", "disabled" => $disabled));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Cantidad Preguntas", "type" => "text", "id" => "cantidadPreguntas", "readonly" => 1));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Nota Maxima", "type" => "text", "id" => "notaMaxima", "required" => "1", "disabled" => $disabled));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Tipo", "id" => "codTipoEvaluacion", "required" => "1", "disabled" => $disabled), $fn->getLista("codTipoEvaluacion", "nomTipoEvaluacion", "tab_tipo_evaluacion", null));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Estado", "id" => "codEstado", "required" => "1", "disabled" => $disabled), $fn->getLista("codEstado", "nomEstado", "tab_estados", array("tipoEstado" => true, "codTipoEstado" => 1)));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-12");
        $form->textArea(array("label" => "Descripci&oacute;n", "type" => "text", "id" => "descripcion", "rows" => 4, "disabled" => $disabled));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        if ($disabled == 0) {
            $form->Hidden(array("name" => "codPage", "value" => $_REQUEST['cod']));
            if ($accion == "Actualizar") {
                $form->Hidden(array("name" => "codEvaluacion", "value" => $_REQUEST['codEvaluacion']));
            }
            $form->botonAcciones(array(
                "link" => false,
                "type" => "button",
                "boton" => "btn-primary",
                "id" => "state",
                "icon" => "fa fa-plus",
                "label" => $accion,
                "onclick" => "return validarEvaluacionCrear()"
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

    function tabPreguntas($fn, $form, $disabled) {

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

        $codEvaluacion = $_REQUEST['codEvaluacion'];
        $evaluaciones = $this->evaluacionesAsignadas($codEvaluacion);

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
            $form->Hidden(array("name" => "codEvaluacionCE", "value" => $codEvaluacion));
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

    function getListado($cod) {
        $sql = "SELECT  a.*,                      
                        b.nomTipoEvaluacion,                       
                        c.nomEstado
                FROM    " . $this->tabla . " a                            
                            LEFT JOIN tab_tipo_evaluacion b on a.codTipoEvaluacion = b.codTipoEvaluacion                            
                            LEFT JOIN tab_estados c ON a.codEstado = c.codEstado";
        if ($cod) {
            $sql .= " WHERE a." . $this->llave . " = '" . $cod . "' ";
        }
        $sql .= " ORDER BY a.fechaCreacion DESC ";
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

    function evaluacionesAsignadas($codEvaluacion) {
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
                  FROM  tab_evaluacion_evaluacion a
                                LEFT JOIN tab_evaluaciones b on a.codEvaluacion = b.codEvaluacion
                                LEFT JOIN tab_tipo_evaluacion c on b.codTipoEvaluacion = c.codTipoEvaluacion
                                LEFT JOIN tab_estados d ON a.codEstado = d.codEstado
                 WHERE  a.codEvaluacion = " . $codEvaluacion;
        return Conexion::obtener($sql);
    }
}

$crearEvaluacion = new CrearEvaluacion();
$crearEvaluacion->Menu();
