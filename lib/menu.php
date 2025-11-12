<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/avatar<?php echo $_SESSION[MISESSION]['codGenero']; ?>.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Hola, <?php echo strtoupper($_SESSION[MISESSION]['nomUsuario']); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> En linea</a>
            </div>
        </div>
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Busqueda..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>       
        <ul class="sidebar-menu">

            <?php
            if ($_SESSION[MISESSION]['isChangePass']) {
                $html_menu = '';
                $menu1 = getMenu($_SESSION[MISESSION]['codPerfil'], 1);
                if (isset($menu1)) {
                    $cod = "";
                    $codMenuParental = "";
                    if (isset($_REQUEST['cod'])) {
                        $cod = base64_decode($_REQUEST['cod']);
                        $codMenuParental = getMenuParental($cod);
                    }

                    foreach ($menu1 as $m1) {
                        if ($m1['levOption'] == 1) {

                            $activePrin = "";
                            $display = "";
                            if ($m1['codOption'] == $codMenuParental) {
                                $activePrin = " active";
                                $display = " style='display: block;'";
                            }

                            $html_menu .= '<li class="treeview' . $activePrin . '">';
                            $html_menu .= '<a href="javascript: void(0);">';
                            $html_menu .= '<i class="' . $m1['icoOption'] . '"></i>';
                            $html_menu .= '<span>';
                            $html_menu .= $m1['nomOption'];
                            $html_menu .= '</span>';
                            $html_menu .= '<i class="fa pull-right fa-angle-down"></i>';
                            $html_menu .= '</a>';

                            $menu2 = getMenu($_SESSION[MISESSION]['codPerfil'], 2, $m1['codOption']);
                            if ($menu2) {
                                $html_menu .= '<ul class="treeview-menu"' . $display . '>';
                                foreach ($menu2 as $m2) {

                                    $active = "";
                                    if ($m2['codOption'] == $cod) {
                                        $active = ' class="active"';
                                    }
                                    $url = 'index.php?cod=' . base64_encode($m2['codOption']);

                                    $html_menu .= '<li' . $active . '>';
                                    $html_menu .= '<a href="' . $url . '"' . $active . ' style="margin-left: 10px;">';
                                    $html_menu .= '<i class="fa fa-angle-double-right"></i>';
                                    $html_menu .= $m2['nomOption'];
                                    $html_menu .= '</a>';
                                    $html_menu .= '</li>';
                                }
                                $html_menu .= '</ul>';
                            }

                            $html_menu .= '</li>';
                        }
                    }
                }
                echo $html_menu;
            }
            ?>
        </ul>
    </section>
</aside>

<?php

function getMenuParental($codOption) {
    $sql = "SELECT codParent 
            FROM   tab_options
            WHERE  codOption = " . $codOption;
    $result = Conexion::obtener($sql);
    if (isset($result)) {
        return $result[0]['codParent'];
    }
    return null;
}

function getMenu($codigoPerfil, $numeroNivel, $codigoPadre = 1) {
    if ($numeroNivel == 1) {
        $sql = "SELECT  a.*
                FROM    tab_options a
                WHERE   a.levOption = '" . $numeroNivel . "'
                AND     a.codParent = '" . $codigoPadre . "'
                AND     a.codEstado = '1'
                AND     a.codOption IN
                    (
                        SELECT  DISTINCT(a.codParent) codOption
                        FROM    tab_options a
                        WHERE   codOption IN
                        (
                            SELECT  codOption
                            FROM    tab_permisos b
                            WHERE   b.codPerfil = '" . $codigoPerfil . "'
                        )
                )
                ORDER BY a.ordOption";
        return Conexion::obtener($sql);
    } else {
        $sql = "SELECT  a.*
                FROM    tab_options a
                WHERE   a.levOption = '" . $numeroNivel . "'
                AND     a.codParent = '" . $codigoPadre . "'
                AND     a.codEstado = '1'
                AND     a.codOption IN
                (
                    SELECT  codOption
                    FROM    tab_permisos b
                    WHERE   b.codPerfil = '" . $codigoPerfil . "'
                )
                ORDER BY a.ordOption";
        return Conexion::obtener($sql);
    }
}
