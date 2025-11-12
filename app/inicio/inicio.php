<?php

class Inicio {

    function vista() {
        $form = new formulario();
        $form->inicioDiv("row");
        $form->inicioDiv("col-lg-12");
        echo'<img src="img/img_bienvenido2.jpg" alt="Bienvenido">';
        $form->finDiv();
        $form->finDiv();
    }
}

$inicio = new Inicio();
$inicio->vista();
