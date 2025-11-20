<?php

class Capacitacion {

    var $order = 2;
    var $tabla = "tab_capacitaciones";
    var $nombre = "Capacitacion";
    var $nombres = "Capacitaciones";
    var $mensaje = "El Capacitacion";
    var $llave = "codCapacitacion";
    var $campos = array("nomCapacitacion", "fecha", "tiempo", "asistencia", "nomUsuario", "nomTipoCapacitacion", "nomCiudad", "nomEstado");
    var $columnas = array("Opciones", "Tema", "Fecha", "Tiempo", "Asistencia", "Capacitador", "Tipo", "Ciudad", "Estado");

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
                foreach ($this->campos as $cam) {
                    $html .= '  <td>' . $row[$cam] . '</td>';
                }
                $html .= '  </tr>';
            }
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
            $codigo = ' #' . $_REQUEST['codCapacitacion'];
        }
        $titulo = ($accion == "Actualizar") ? "ACTUALIZAR CAPACITACI&Oacute;N" . $codigo : "CREAR CAPACITACI&Oacute;N";

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

        $disabled = ($_REQUEST['codEstado'] == 3) ? 1 : 0;

        $fn = new Funciones();
        $form = new formulario();

        $params['ruta'] = "js/main";
        $form->linkJs($params);

        $params2['ruta'] = "js/capacitacion/capacitacion";
        $form->linkJs($params2);

        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");
        $form->inicioDiv("box box-primary");

        $form->inicioDiv("box-header");
        echo '<h3 class="box-title">' . $titulo . '</h3>';
        $form->finDiv();

        $form->inicioDiv("box-body");

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

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Asistencia", "type" => "text", "id" => "asistencia", "readonly" => 1));
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

        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
    }

    function getListado($cod) {
        $sql = "SELECT  a.*,
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
                    WHERE   c.codDepto = $codDepto";
            return Conexion::obtener($sql);
        }
        return null;
    }
}

$capacitacion = new Capacitacion();
$capacitacion->Menu();
