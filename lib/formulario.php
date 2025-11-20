<?php

class Formulario {

    function inicioDiv($class) {
        echo '<div class="' . $class . '">';
    }

    function inicioDivId($class, $id) {
        echo '<div class="' . $class . '" id="' . $id . '">';
    }

    function finDiv() {
        echo "</div>";
    }

    function inicioUl($class) {
        echo '<ul class="' . $class . '">';
    }

    function finUl() {
        echo "</ul>";
    }

    function hrefImg($href, $title, $src, $width, $height) {
        if ($title !== '') {
            echo '  <a href="' . $href . '" target="_blank" title="' . $title . '" disabled>
                        <img src="' . $src . '" width="' . $width . '" height="' . $height . '" alt="' . $title . '">
                    </a>&nbsp;&nbsp;&nbsp;';
        }
    }

    function liNav($href, $expanded, $active, $icon, $label) {
        echo '  <li class="nav-item">
                    <a href="#' . $href . '" data-toggle="tab" aria-expanded="' . $expanded . '" class="nav-link ' . $active . '">
                        <i class="' . $icon . ' d-lg-none d-block mr-1"></i>
                        <span class="d-none d-lg-block">' . $label . '</span>
                    </a>
                </li>';
    }

    function center() {
        echo "<center>";
    }

    function finCenter() {
        echo "</center>";
    }

    function boxHeader($titulo) {
        echo '  <div class="box-header">
                    <h3 class="box-title">' . $titulo . '</h3>
                </div>';
    }

    function atributos($array = "") {
        $str = " ";

        if (is_array($array)) {
            for ($i = 0;
                    $i < sizeof($array);
                    $i++) {
                $row = current($array);
                if ($row != null) {
                    $key = key($array);
                    $str .= $key . "='" . $row . "' ";
                }
                next($array);
            }
        }

        return $str;
    }

    function iniForm($params = "") {

        if ($params == "") {
            $params = array();
        }

        if (!isset($params['name'])) {
            $params['name'] = "formulario";
        }

        if (!isset($params['id'])) {
            $params['id'] = "formulario";
        }

        $vars = $this->atributos($params);

        echo "<form method='post' enctype='multipart/form-data' " . $vars . ">";
    }

    function finForm() {
        echo "</form>";
    }

    function espacio() {
        echo "</br>";
    }

    function titleForm($params = "") {

        if ($params == "") {
            $params = array();
        }

        $titulo = "";
        if (isset($params['titulo'])) {
            $titulo = $params['titulo'];
        }

        $icon = "";
        if (isset($params['icon'])) {
            $icon = $params['icon'];
        }

        $html = '</i><h4 class="header-title">' . $titulo . '</h4> <i class="' . $icon . '">';

        echo $html;
    }

    function text($params = "") {

        if ($params == "") {
            $params = array();
        }

        $mb = 3;
        if (isset($params['mb'])) {
            $mb = $params['mb'];
        }

        $label = "";
        $placeholder = "";
        if (isset($params['label'])) {
            $label = $params['label'];
            $placeholder = ' placeholder="' . $label . '" ';
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $value = "";
        if (isset($_REQUEST[$id])) {
            $value = ' value="' . $_REQUEST[$id] . '" ';
        }

        $type = "text";
        if (isset($params['type'])) {
            $type = $params['type'];
            if ($type == "file") {
                $placeholder = "";
                $value = "";
            }
        }

        $onchange = "";
        if (isset($params['onchange'])) {
            $onchange = " onchange='" . $params['onchange'] . "' ";
        }

        $onblur = "";
        if (isset($params['onblur'])) {
            $onblur = " onblur='" . $params['onblur'] . "' ";
        }

        $onkeyup = "";
        if (isset($params['onkeyup'])) {
            $onkeyup = ' onkeyup="' . $params['onkeyup'] . '" ';
        }

        $readonly = "";
        if (isset($params['readonly']) && $params['readonly'] == 1) {
            $readonly = " readonly ";
        }

        $disabled = "";
        if (isset($params['disabled']) && $params['disabled'] == 1) {
            $disabled = " disabled ";
        }

        $required = "";
        if (isset($params['required'])) {
            $label = "* " . $label;
            $required = " required ";
        }

        $class = "form-control";
        if (isset($params['class'])) {
            $class = " class ";
        }

        $width = "";
        if (isset($params['width'])) {
            $width = " style='width:" . $params['width'] . "'";
            $placeholder = "";
        }

        $span = "";
        if (isset($params['span'])) {
            $span = '&nbsp; <span style="color:red" id="' . $id . 'Span"></span>';
        }

        $maxlength = "";
        if (isset($params['maxlength'])) {
            $maxlength = ' maxlength="' . $params['maxlength'] . '"';
        }

        $html = '   <div class="form-group mb-' . $mb . '">';
        $html .= '      <label for="' . $id . '">' . $label . '</label>' . $span;
        $html .= '      <input' . $width . $maxlength . ' class="' . $class . '" type="' . $type . '" id="' . $id . '"  name="' . $id . '" ' . $placeholder . $value . $onchange . $onblur . $onkeyup . $readonly . $disabled . $required . '/>';
        $html .= '  </div>';
        echo $html;
    }

    function textArea($params = "") {

        if ($params == "") {
            $params = array();
        }

        $mb = 3;
        if (isset($params['mb'])) {
            $mb = $params['mb'];
        }

        $id = "";
        $text = "";
        if (isset($params['id'])) {
            $id = $params['id'];
            $text = $_REQUEST[$id];
        }

        $label = "";
        $placeholder = "";
        if (isset($params['label'])) {
            $label = $params['label'];
            $placeholder = ' placeholder="' . $label . '" ';
        }

        $class = "form-control";
        if (isset($params['class'])) {
            $class = " class ";
        }

        $rows = "";
        if (isset($params['rows'])) {
            $rows = $params['rows'];
        }

        $readonly = "";
        if (isset($params['readonly']) && $params['readonly'] == 1) {
            $readonly = " readonly ";
        }

        $disabled = "";
        if (isset($params['disabled']) && $params['disabled'] == 1) {
            $disabled = " disabled ";
        }

        $required = "";
        if (isset($params['required'])) {
            $label = "* " . $label;
            $required = " required ";
        }

        $html = '   <div class="form-group mb-' . $mb . '">';
        $html .= '      <label for="' . $id . '">' . $label . '</label>';
        $html .= '      <textarea class="' . $class . '" id="' . $id . '" name="' . $id . '" rows="' . $rows . '" ' . $placeholder . $readonly . $disabled . $required . '>' . $text . '</textarea>';
        $html .= '  </div>';
        echo $html;
    }

    function datePicker($params = "") {

        if ($params == "") {
            $params = array();
        }

        $mb = 3;
        if (isset($params['mb'])) {
            $mb = $params['mb'];
        }

        $label = "";
        if (isset($params['label'])) {
            $label = $params['label'];
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $value = "";
        if (isset($_REQUEST[$id])) {
            $value = ' value="' . $_REQUEST[$id] . '" ';
        }

        $readonly = "";
        if (isset($params['readonly']) && $params['readonly'] == 1) {
            $readonly = " readonly ";
        }

        $disabled = "";
        if (isset($params['disabled']) && $params['disabled'] == 1) {
            $disabled = " disabled ";
        }

        $required = "";
        if (isset($params['required'])) {
            $label = "* " . $label;
            $required = " required ";
        }

        $html = '   <div class="form-group mb-' . $mb . '">';
        $html .= '      <label for="' . $id . '">' . $label . '</label>';
        $html .= '      <div class="input-group date">';
        $html .= '          <div class="input-group-addon">';
        $html .= '              <i class="fa fa-calendar"></i>';
        $html .= '          </div>';
        $html .= '          <input type="text" class="form-control datepicker" id="' . $id . '"  name="' . $id . '" placeholder="dd/mm/yyyy"' . $value . $readonly . $disabled . $required . '/>';
        $html .= '      </div>';
        $html .= '  </div>';
        echo $html;
    }

    function checkWithText($params = "") {

        if ($params == "") {
            $params = array();
        }

        $label = "";
        if (isset($params['label'])) {
            $label = "&nbsp;&nbsp;" . $params['label'];
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $idCheck = "";
        if (isset($params['idCheck'])) {
            $idCheck = $params['idCheck'];
        }

        $value = "";
        if (isset($_REQUEST[$id])) {
            $value = ' value="' . $_REQUEST[$id] . '" ';
        }

        $checked = "";
        $disabled = ' disabled="true"';
        if (isset($_REQUEST[$idCheck])) {
            if ($_REQUEST[$idCheck]) {
                $checked = ' checked="true"';
                $disabled = "";
            }
        }

        $onclick = "";
        if (isset($params['onclick'])) {
            $onclick = " onclick='" . $params['onclick'] . "'";
        }

        // $html = '   <div class="input-group">';
        $html = '          <input type="checkbox" id="' . $idCheck . '" name="' . $idCheck . '"' . $onclick . $checked . '/>';
        //$html .= '      <label for="' . $id . '">' . $label . '</label>';
        //$html .= '      <input type="text" class="form-control" id="' . $id . '" name="' . $id . '" ' . $value . $disabled . '/>';
        //$html .= '  </div>';
        echo $html;
    }

    public function areaTexto($params = "") {

        if ($params == "") {
            $params = array();
        }

        $mb = 3;
        if (isset($params['mb'])) {
            $mb = $params['mb'];
        }

        $label = "";
        if (isset($params['label'])) {
            $label = $params['label'];
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $rows = 3;
        if (isset($params['rows'])) {
            $rows = $params['rows'];
        }

        $readonly = "";
        if (isset($params['readonly'])) {
            if ($params['readonly'] == "1") {
                $readonly = " readonly ";
            }
        }

        $disabled = "";
        if (isset($params['disabled'])) {
            if ($params['disabled'] == "1") {
                $disabled = " disabled ";
            }
        }

        $required = "";
        if (isset($params['required'])) {
            $label = "* " . $label;
            $required = " required ";
        }

        $value = "";
        if (isset($_REQUEST[$id])) {
            $value = $_REQUEST[$id];
        }

        $html = '   <div class="form-group mb-' . $mb . '">';
        $html .= '      <label for="' . $id . '">' . $label . '</label>';
        $html .= '      <textarea class="form-control" id="' . $id . '" name="' . $id . '" ' . '  rows="' . $rows . '"' . $required . $disabled . $readonly . '>';
        $html .= $value;
        $html .= '      </textarea>';
        $html .= '  </div>';

        echo $html;
    }

    function lista($params = "", $lista = "") {

        if ($params == "") {
            $params = array();
        }

        $mb = 3;
        if (isset($params['mb'])) {
            $mb = $params['mb'];
        }

        if (isset($params['required'])) {
            $params['label'] = "* " . $params['label'];
            $params['required'] = " required ";
        }

        $html = '   <div class="form-group mb-' . $mb . '">';
        $html .= '      <label for="' . $params['id'] . '">' . $params['label'] . '</label>';
        $html .= form_select($params, $lista);
        $html .= '  </div>';

        echo $html;
    }

    function mensaje($params = "") {

        if ($params == "") {
            $params = array();
        }

        $tipo = "";
        if (isset($params['tipo'])) {
            $tipo = $params['tipo'];
        }

        $label = "";
        if (isset($params['label'])) {
            $label = $params['label'];
        }

        $label2 = "";
        if (isset($params['label2'])) {
            $label2 = $params['label2'];
        }

        $html = '   <div class="alert alert-' . $tipo . ' alert-dismissable">';
        $html .= '      <i class="fa fa-check"></i>';
        $html .= '      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
        $html .= '      <b>' . $label . '</b> -> ';
        $html .= $label2;
        $html .= '  </div>';

        echo $html;
    }

    function linkJs($params = "") {

        if ($params == "") {
            $params = array();
        }

        $ruta = "";
        if (isset($params['ruta'])) {
            $ruta = $params['ruta'];
        }

        $html = '<script type="text/javascript" src="' . $ruta . '.js"></script>';

        echo $html;
    }

    function botonAcciones($params = "") {

        $html = "";
        $cod = isset($_REQUEST['cod']) ? $_REQUEST['cod'] : "";

        if ($params == "") {
            $params = array();
        }

        $link = "";
        if (isset($params['link'])) {
            $link = $params['link'];
        }

        $state = "";
        if (isset($params['state'])) {
            $state = $params['state'];
        }

        $type = "";
        if (isset($params['type'])) {
            $type = $params['type'];
        }

        $boton = "";
        if (isset($params['boton'])) {
            $boton = $params['boton'];
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $disabled = "";
        if (isset($params['disabled'])) {
            $disabled = " disabled ";
        }

        $icon = "";
        if (isset($params['icon'])) {
            $icon = $params['icon'];
        }

        $label = "";
        if (isset($params['label'])) {
            $label = $params['label'];
        }

        $onclick = "";
        if (isset($params['onclick'])) {
            $onclick = ' onclick="' . $params['onclick'] . '" ';
        }

        if ($link) {
            $url = 'index.php?cod=' . $cod;
            if ($state != "") {
                $url .= '&state=' . $state;
            }
            $html .= '<a href="' . $url . '">';
        }

        $html .= '   <button type="' . $type . '" class="btn ' . $boton . '" id="' . $id . '" name="' . $id . '" value= "' . $label . '" ' . $disabled . $onclick . ' >';
        $html .= '      <i class="' . $icon . '"></i> <span>' . $label . '</span>';
        $html .= '  </button>';

        if ($link) {
            $html .= '</a>';
        }

        echo $html;
    }

    public function checkbox($params = "") {

        if ($params == "") {
            $params = array();
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $value = "";
        if (isset($params['value'])) {
            $value = $params['value'];
        }

        $label = "";
        if (isset($params['label'])) {
            $label = $params['label'];
        }

        $disabled = "";
        if (isset($params['disabled'])) {
            $disabled = " disabled ";
        }

        $required = "";
        if (isset($params['required'])) {
            $label = "* " . $params['label'];
            $required = " required ";
        }

        $checked = "";
        if (isset($params['checked'])) {
            if ($params['checked'] == "1") {
                $checked = " checked ";
            }
        }

        $onchange = "";
        if (isset($params['onchange'])) {
            $onchange = $params['onchange'];
        }
        $html = '   <div>';
        $html .= '      <label for="' . $id . '">';
        $html .= '          <input type="checkbox" id="' . $id . '" name="' . $id . '" value="' . $value . '" ' . $required . $disabled . $checked . $onchange . '/>&nbsp;&nbsp;';
        $html .= $label;
        $html .= '      </label>';
        $html .= '  </div>';
        echo $html;
    }

    public function checkbox2($params = "") {

        /* if ($params == "") {
          $params = array();
          }

          $id = "";
          if (isset($params['id'])) {
          $id = $params['id'];
          }

          $value = "";
          if (isset($params['value'])) {
          $value = $params['value'];
          }

          $label = "";
          if (isset($params['label'])) {
          $label = $params['label'];
          }

          $disabled = "";
          if (isset($params['disabled'])) {
          $disabled = " disabled ";
          }

          $required = "";
          if (isset($params['required'])) {
          $label = "* " . $params['label'];
          $required = " required ";
          }

          $checked = "";
          if (isset($params['checked'])) {
          if ($params['checked'] == "1") {
          $checked = " checked ";
          }
          }

          $onchange = "";
          if (isset($params['onchange'])) {
          $onchange = $params['onchange'];
          } */

        /* $html = '   <div>';
          $html .= '      <label for="' . $id . '">';
          $html .= '          <input type="checkbox" id="' . $id . '" name="' . $id . '" value="' . $value . '" ' . $required . $disabled . $checked . $onchange . '/>&nbsp;&nbsp;';
          $html .= $label;
          $html .= '      </label>';
          $html .= '  </div>'; */

        echo '  <div class="form-group">
                    <div class="checkbox">
                        <label>
                             Checkbox 1
                            <input type="checkbox" id="isColors" type="colores" onclick="validacionesCheckText()" checked/>
                            
                        </label>
                    </div> 
                </div>';

        //echo $html;
    }

    // Sin revisar de aqui para abajo
    function iniFootPanel() {
        $html = '<div class="card-footer">';

        echo $html;
    }

    function finFootPanel() {
        $html = '</div>'; // Cierra foot

        echo $html;
    }

    public function grilla($params = "") {

        if ($params == "") {
            $params = array();
        }

        $html = '<div class="card-body table-responsive p-0">';
        $html .= '<table class="table table-bordered table-hover" id="' . $params['id'] . '">';
        if ($params['columnas']) {
            $html .= '<tr>';
            foreach ($params['columnas'] as $col) {
                $html .= '<th align="center" style="text-align:center;
">' . $col . '</th>';
            }
            $html .= '</tr>';
        }

        if ($params['campos']) {
            $html .= '<tr>';
            foreach ($params['campos'] as $camp) {
                $html .= '<td>' . $camp . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';

        echo $html;
    }

    // Campos para utilizar en una grilla
    function textGrilla($params = "") {

        if ($params == "") {
            $params = array();
        }

        $onchange = "";
        if (isset($params['onchange'])) {
            $onchange = $params['onchange'];
        }

        $label = "";
        if (isset($params['label'])) {
            $label = $params['label'];
        }

        $required = "";
        if (isset($params['required'])) {
            $required = $params['required'];
        }

        $disabled = "";
        if (isset($params['disabled'])) {
            $disabled = $params['disabled'];
        }

        $id = "";
        if (isset($params['id'])) {
            $id = $params['id'];
        }

        $value = "";
        if (isset($_REQUEST[$id])) {
            $value = $_REQUEST[$id];
        }

        $html = '<input type="' . $params['type'] . '" id="' . $id . '" name="' . $id . '" class="form-control form-control-sm" 
            onchange="' . $onchange . '"
            placeholder="' . $label . '" value="' . $value . '" ' . $required . ' ' . $disabled . ' >';

        return $html;
    }

    function listaGrilla($params, $lista) {
        $html = form_select($params, $lista);
        return $html;
    }

    public function Hidden($params = "") {

        if ($params == "") {
            $params = array();
        }

        $name = "";
        if (isset($params['name'])) {
            $name = $params['name'];
        }

        $value = "";
        if (isset($params['value'])) {
            $value = $params['value'];
        }

        echo "<input type = 'hidden' id = '" . $name . "' name = '" . $name . "' value = '" . $value . "'/>";
    }

    public function textField($vars = "") {

        $colspan = "";
        $class = "tdInfo";

        if ($vars == "") {
            $vars = array();
        }

        if (!isset($vars['id'])) {
            $vars['id'] = $vars['name'];
        }

        $pos = "";
        if (isset($vars['pos'])) {
            $pos = $vars['pos'];
        }

        if (isset($vars['tdborder'])) {
            $class = "cellBorder";
            $vars['tdborder'] = "";
        }

        if (isset($vars['validate'])) {

            $validate = $vars['validate'];

            if ($validate == 'n') {
                $vars['onkeypress'] = "return numero( event );
" . $vars['onkeypress'];
                $vars['onchange'] = 'esNumero( this ); ' . $vars['onchange'];
                $vars['style'] .= ' text-align:right; ';
            } else if ($validate == 'de') {
                $vars['onkeypress'] = "return decimal( event );
" . $vars['onkeypress'];
                $vars['onchange'] = 'esDecimal( this ); ' . $vars['onchange'];
                $vars['style'] .= ' text-align:right; ';
            } elseif ($validate == 'm') {
                $vars['onkeyup'] = "FormatoMoneda( this );
" . $vars['onkeyup'];
                $vars['onchange'] = "FormatoMoneda( this );
" . $vars['onchange'];
                $vars['style'] .= ' text-align:right; ';
            } elseif ($validate == 'l') {
                $vars['onkeypress'] = "return letras( event );
" . $vars['onkeypress'];
                $vars['onchange'] = 'esTexto( this ); ' . $vars['onchange'];
            } elseif ($validate == 'd') {
                echo "<script>";
                echo " $(function()
{
$( '#" . $vars['id'] . "' ).datepicker();
});
";
                echo "</script>";
            } elseif ($validate == 'dt') {
                echo "<script>";
                echo "  $(function()
    {
    $('#" . $vars['id'] . "').timepicker();
    }); ";
                echo "</script>";
            } elseif ($validate == 'c') {
                echo "<script>";
                echo " $(function()
    {
    $('#" . $vars['id'] . "').ColorPicker(
    {
    onSubmit: function(hsb, hex, rgb, el)
    {
    $(el).val(hex);
    $(el).ColorPickerHide();
    },
            onBeforeShow: function ()
            {
            $(this).ColorPickerSetColor(this.value);
            }
    })
            .bind('keyup', function()
            {
            $(this).ColorPickerSetColor(this.value);
            });
    }); ";
                echo "</script>";
            }
        }
        $vars['validate'] = "";

        if (isset($vars['>'])) {
            $vars['onchange'] = 'esMayorA( this, ' . $vars['>'] . ' ); ' . $vars['onchange'];
            $vars['>'] = "";
        }

        if (isset($vars['email'])) {
            $vars['onchange'] = 'esMail( this ); ' . $vars['onchange'];
            $vars['email'] = "";
        }

        if (isset($vars['colspan'])) {
            $colspan = " colspan='" . $vars['colspan'] . "' ";
            $vars['colspan'] = "";
        }

        $popup = "";

        if (isset($vars['popup'])) {
            $popup = $vars['popup'];
            $vars['popup'] = "";
        }

        $notd = $vars['notd'];

        $vars = $this->Generate($vars);

        $html = "";

        if (!$notd) {
            $html .= "\n<td nowrap $colspan align='left' class='$class' style='vertical-align:middle;' >";
        }

        $html .= "\n<input class='inputForm'  type='text' $vars > $pos";

        if ($popup) {
            $html .= "\n<img src='images/ven.png' style='vertical-align:top; cursor:pointer' onclick='$popup' >";
        }
        if (!$notd) {
            $html .= "\n</td>";
        }


        return $html;
    }

    public function infor($vars = "") {

        if ($vars == "") {
            $vars = array();
        }

        $id = "";
        if (isset($vars['id'])) {
            $id = $vars['id'];
        }

        $value = "";
        if (isset($_REQUEST[$id])) {
            $value = ' value="' . $_REQUEST[$id] . '" ';
        }

        $colspan = "";
        if (isset($vars['colspan'])) {
            $colspan = " colspan='" . $vars['colspan'] . "' ";
            $vars['colspan'] = "";
        }

        if (isset($vars['validate'])) {

            $validate = $vars['validate'];

            if ($validate == 'm') {

                $onkeyup = "";
                if (isset($vars['onkeyup'])) {
                    $onkeyup = "FormatoMoneda( this ); " . $vars['onkeyup'];
                }

                $onchange = "";
                if (isset($vars['onchange'])) {
                    $onchange = 'FormatoMoneda( this ); ' . $vars['onchange'];
                }

                $style = "";
                if (isset($vars['style'])) {
                    $style = $vars['style'] . " text-align:right; ";
                }

                $vars['onkeyup'] = $onkeyup;
                $vars['onchange'] = $onchange;
                $vars['style'] = $style;
            }
        }

        $notd = "";
        if (isset($vars['notd'])) {
            $notd = $vars['notd'];
        }

        $generate = $this->Generate($vars);

        $html = '';
        if (!$notd) {
            $html .= "\n<td nowrap " . $colspan . " align='left' class='tdInfo' >";
        }

        $html .= "\n<input class='inforForm' readonly type='text'" . $value . $generate . "/>";

        if (!$notd) {
            $html .= "\n</td>";
        }

        echo $html;
    }

    public function Generate($array = "") {
        $str = " ";

        if (is_array($array)) {
            for ($i = 0; $i < sizeof($array); $i++) {
                $row = current($array);

                if ($row) {
                    $key = key($array);
                    $str .= "$key='$row' ";
                }

                next($array);
            }
        }

        return $str;
    }
}
