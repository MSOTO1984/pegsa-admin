<?php

class ActualizarClave {

    function Vista() {
        $form = new formulario();

        $params['ruta'] = "js/usuario/usuario";
        $form->linkJs($params);

        $form->inicioDiv("row");
        $form->inicioDiv("col-md-12");
        $form->inicioDiv("box box-primary");
        $form->inicioDiv("box-body");

        $form->inicioDiv("row");

        $form->inicioDiv("col-lg-6");
        $form->text(array("label" => "Clave", "id" => "claveUsuario", "onchange" => "return validarClave();", "type" => "password"));
        $form->finDiv();

        $form->inicioDiv("col-lg-6");
        $form->text(array("label" => "Repetir clave", "id" => "claveUsuario2", "onchange" => "return validarClave();", "type" => "password"));
        $form->finDiv();

        $form->finDiv();

        $form->inicioDiv("button-list");
        $form->center();
        $form->botonAcciones(array(
            "link" => false,
            "type" => "button",
            "boton" => "btn-primary",
            "id" => "state",
            "icon" => "fa fa-plus",
            "label" => "Actualizar Clave",
            "onclick" => "return accionCambioClave()"
        ));
        $form->finCenter();
        $form->finDiv();

        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
        $form->finDiv();
    }
}

$actualizarClave = new ActualizarClave();
$actualizarClave->Vista();
