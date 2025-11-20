<?php

function FormatoTexto($texto) {
    $t = $texto;
    $t = str_replace("\n", "<br/>", $t);
    $t = mb_strtolower($t, 'utf-8');
    $t = mb_ucfirst($t);
    return $t;
}

function mb_ucfirst($str, $charset = 'utf-8') {
    $first = mb_strtoupper(mb_substr($str, 0, 1, $charset), $charset);
    $end = mb_substr($str, 1, mb_strlen($str, $charset), $charset);
    return $first . $end;
}

function show($object) {
    echo '<pre style="background: #aaaaaa !important; color: #ffffff !important; margin-left: 100px;">';
    print_r($object);
    echo '</pre>';
}

function get_array($array, $index = 'assoc') {
    $output = NULL;
    if ($index === 'assoc') {
        foreach ($array as $key => $item) {
            if (!is_int($key)) {
                $output[$key] = $item;
            }
        }
        return $output;
    } elseif ($index === 'index') {
        foreach ($array as $key => $item) {
            if (is_int($key)) {
                $output[$key] = $item;
            }
        }
        return $output;
    } else {
        return $array;
    }
}

function get_meses($month) {
    switch ((int) $month) {
        case 1 :
            return 'Enero';
        case 2 :
            return 'Febrero';
        case 3 :
            return 'Marzo';
        case 4 :
            return 'Abril';
        case 5 :
            return 'Mayo';
        case 6 :
            return 'Junio';
        case 7 :
            return 'Julio';
        case 8 :
            return 'Agosto';
        case 9 :
            return 'Septiembre';
        case 10 :
            return 'Octubre';
        case 11 :
            return 'Noviembre';
        case 12 :
            return 'Diciembre';
    }
}

function form_select($params = "", $lista = "") {

    if ($params == "") {
        $params = array();
    }

    $multiple = "";
    if (isset($params['multiple'])) {
        $multiple = ' multiple data-placeholder="Seleccionar..." ';
    }

    $onchange = "";
    if (isset($params['onchange'])) {
        $onchange = ' onchange="' . $params['onchange'] . '" ';
    }

    $disabled = "";
    if (isset($params['disabled']) && $params['disabled'] == 1) {
        $disabled = " disabled ";
    }

    $form = '<select ' . $disabled . ' class="form-control" id="' . $params['id'] . '" name="' . $params['id'] . '" ' . $onchange . '>';

    if (!isset($params['first']) && !isset($params['multiple'])) {
        $params['first'] = "Selecciona una opci&oacute;n";
        $form .= '<option value="">' . $params['first'] . '</option>';
    }

    if ($lista != null) {
        $rows = sizeof($lista);

        if ($rows > 0) {
            $keys = array_keys($lista[0]);
            for ($i = 0; $i < $rows; $i++) {
                $selected = NULL;

                $value = isset($_REQUEST[$params['id']]) ? $_REQUEST[$params['id']] : "";

                if ($multiple !== "") {
                    if ($value !== "") {
                        foreach ($value as $val) {
                            if ($val == $lista[$i][$keys[0]]) {
                                $selected = 'selected="selected"';
                            }
                        }
                    }
                } else {
                    if ((string) $lista[$i][$keys[0]] == (string) $value) {
                        $selected = 'selected="selected"';
                    }
                }

                $form .= '<option value="' . $lista[$i][$keys[0]] . '"' . $selected . '>' . $lista[$i][$keys[1]] . '</option>';
            }
        }
    }
    $form .= '</select>';
    return $form;
}
