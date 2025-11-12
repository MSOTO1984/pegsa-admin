<?php

class Perfiles {

    var $order = 1;
    var $tab = "tab_perfiles";
    var $nombre = "Perfil";
    var $nombres = "Perfiles";
    var $mensaje = "El Perfil";
    var $llave = "codPerfil";
    var $campos = array("codPerfil", "nomPerfil", "usuarioCreacion", "fechaCreacion");
    var $columnas = array("Opciones", "C&oacute;digo", "Nombre", "Usuario Creaci&oacute;n", "Fecha Creaci&oacute;n");

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
        $list = $this->getListado(null);
        if (count($list) > 0) {
            foreach ($list as $row) {
                $url = 'index.php?cod=' . $_REQUEST['cod'] . '&state=Editar&' . $this->llave . '=' . $row[$this->llave];
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

            $permisos = $this->getPermisos($_REQUEST[$this->llave]);
            if ($permisos) {
                $_REQUEST['permisos'] = $permisos;
            }
        }
        $this->vista("Actualizar");
    }

    function vista($accion) {

        $fn = new Funciones();
        $form = new formulario();

        $codPerfil = "";
        if (!isset($_REQUEST[$this->llave])) {
            $codPerfil = $fn->getCodigo($this->llave, $this->tab);
        } else {
            $codPerfil = $_REQUEST[$this->llave];
        }
        
        $params['ruta'] = "js/perfil/perfil";
        $form->linkJs($params);

        $padres = $this->getOpciones(1);
        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");
        $form->inicioDiv("nav-tabs-custom");

        echo '  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_informacion_general" data-toggle="tab">Informaci&oacute;n General</a></li>';
        $listSize = count($padres);
        if ($listSize > 0) {
            foreach ($padres as $pa) {
                $pamenu = $pa['nomOption'];
                echo '<li><a href="#tab_' . $pamenu . '" data-toggle="tab">' . $pamenu . '</a></li>';
            }
        }
        echo'   </ul>';

        $form->inicioDiv("tab-content");

        $form->inicioDivId("tab-pane active", "tab_informacion_general");
        $form->inicioDiv("row");
        $form->inicioDiv("col-lg-12");
        $form->text(array("label" => "Nombre Perfil", "id" => "nomPerfil", "required" => "1"));
        $form->finDiv();     
        $form->finDiv();
        $form->finDiv();

        $options = "";
        if ($listSize > 0) {
            foreach ($padres as $pa) {
                $form->inicioDivId("tab-pane", "tab_" . $pa['nomOption']);
                $form->inicioDiv("form-group");
                $hijos = $this->getOpciones($pa['codOption']);
                foreach ($hijos as $hi) {
                    $checked = "";
                    $codOption = $hi['codOption'];
                    if (isset($_REQUEST['permisos'])) {
                        foreach ($_REQUEST['permisos'] as $pe) {
                            if ($pe['codOption'] == $codOption) {
                                $checked = "1";
                            }
                        }
                    }
                    $_REQUEST['codOption' . $codOption] = $codOption;
                    $form->checkbox(
                            array(
                                "id" => "codOption" . $codOption,
                                "value" => $codOption,
                                "label" => "" . $hi['nomOption'],
                                "checked" => $checked)
                    );

                    $options .= $codOption . ",";
                }
                $form->finDiv();
                $form->finDiv();
            }
        }

        $form->finDiv();

        $form->finDiv();
        $form->finDiv();
        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        $form->Hidden(array("name" => "codPage", "value" => $_REQUEST['cod']));
        $form->Hidden(array("name" => $this->llave, "value" => $codPerfil));
        $form->Hidden(array("name" => "options", "value" => $options));
        $form->botonAcciones(array(
            "link" => false,
            "type" => "button",
            "boton" => "btn-primary",
            "id" => "state",
            "icon" => "fa fa-plus",
            "label" => $accion,
            "onclick" => "return validarPerfil()"
        ));
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

    function getPermisos($codPerfil) {
        $sql = "SELECT  a.*
                FROM    tab_permisos a
                WHERE   a." . $this->llave . " = '" . $codPerfil . "' ";
        return Conexion::obtener($sql);
    }

    function getOpciones($codParent) {
        $sql = "SELECT  a.*
                FROM    tab_options a
                WHERE   a.codParent = '" . $codParent . "' 
                  AND   a.codEstado = '1' 
                ORDER BY " . $this->order . " ASC ";
        return Conexion::obtener($sql);
    }

    function getListado($cod) {

        $sql = "SELECT  a." . $this->llave . ",
                        a.nomPerfil,
                        a.usuarioCreacion,
                        a.fechaCreacion
                FROM    " . $this->tab . " a
                WHERE   1 = 1 ";

        if ($cod) {
            $sql .= "   AND a." . $this->llave . " = '" . $cod . "' ";
        }

        $sql .= "ORDER BY " . $this->order . " ASC ";

        return Conexion::obtener($sql);
    }
}

$perfiles = new Perfiles();
$perfiles->Menu();
