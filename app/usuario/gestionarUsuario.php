<?php

class GestionarUsuario {

    var $order = 2;
    var $tabla = "tab_usuarios";
    var $nombre = "Usuario";
    var $nombres = "Usuarios";
    var $mensaje = "El Usuario";
    var $llave = "codUsuario";
    var $campos = array("codUsuario", "nomUsuario", "emailUsuario", "nomPerfil", "nomEstado", "usuarioCreacion", "fechaCreacion");
    var $columnas = array("Opciones", "Identificaci&oacute;n", "Nombre", "Email", "Perfil", "Estado", "Usuario creaci&oacute;n", "Fecha creaci&oacute;n");

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

        $_REQUEST['claveUsuario'] = "";
        $this->vista("Actualizar");
    }

    function vista($accion) {

        $readonly = ($accion == "Actualizar") ? "1" : "0";

        if (!isset($_REQUEST['codEstado'])) {
            $_REQUEST['codEstado'] = 1;
        }

        if (!isset($_REQUEST['codDepto'])) {
            $_REQUEST['codDepto'] = '';
        }

        $fn = new Funciones();
        $form = new formulario();

        $params['ruta'] = "js/main";
        $form->linkJs($params);

        $params2['ruta'] = "js/usuario/usuario";
        $form->linkJs($params2);

        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");
        $form->inicioDiv("box box-primary");
        $form->inicioDiv("box-body");

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "N&uacute;mero de documento", "id" => $this->llave, "required" => "1", "readonly" => $readonly));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Nombre", "id" => "nomUsuario", "required" => "1"));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Email", "type" => "email", "id" => "emailUsuario", "required" => "1"));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Celular", "type" => "text", "id" => "celUsuario", "required" => "1"));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Perfil", "id" => "codPerfil"), $fn->getLista("codPerfil", "nomPerfil", "tab_perfiles", array("orden" => 1)));
        $form->finDiv();

        $form->inicioDiv("col-lg-2");
        $form->lista(array("label" => "Genero", "id" => "codGenero"), $fn->getLista("codGenero", "nomGenero", "tab_generos", array("orden" => 1)));
        $form->finDiv();

        $form->inicioDiv("col-lg-2");
        $form->lista(array("label" => "Estado", "id" => "codEstado"), $fn->getLista("codEstado", "nomEstado", "tab_estados", array("tipoEstado" => true, "codTipoEstado" => 1)));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-4");
        $form->text(array("label" => "Direcci&oacute;n", "type" => "text", "id" => "direccion", "required" => "1"));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Departamento", "id" => "codDepto", "required" => "1", "onchange" => "getListaCiudades()"), $this->getListaDeptos(1));
        $form->finDiv();

        $form->inicioDiv("col-lg-4");
        $form->lista(array("label" => "Ciudad", "id" => "codCiudad", "required" => "1"), $this->getListaCiudades($_REQUEST['codDepto']));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        $form->Hidden(array("name" => "codPage", "value" => $_REQUEST['cod']));
        $form->botonAcciones(array(
            "link" => false,
            "type" => "button",
            "boton" => "btn-primary",
            "id" => "state",
            "icon" => "fa fa-plus",
            "label" => $accion,
            "onclick" => "return validarUsuario()"
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

        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
    }

    function getListado($cod) {
        $sql = "SELECT  a.*,
                        b.codDepto,
                        c.nomPerfil, 
                        d.nomEstado
                FROM    " . $this->tabla . " a 
                            LEFT JOIN tab_ciudades b on a.codCiudad = b.codCiudad                        
                            LEFT JOIN tab_perfiles c ON a.codPerfil = c.codPerfil
                            LEFT JOIN tab_estados d ON a.codEstado = d.codEstado";
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

$gestionarUsuario = new GestionarUsuario();
$gestionarUsuario->Menu();
