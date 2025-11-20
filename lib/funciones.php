<?php

class Funciones {

    private $fn = NULL;

    public function __construct() {
        
    }

    function getFecha($fecha) {
        $f = explode("/", $fecha);
        return "$f[2]-$f[1]-$f[0]";
    }

    public function getCodigo($codigo, $tabla) {
        $sql = "SELECT  MAX( " . $codigo . " + 0 ) as codigo
                FROM    " . $tabla;
        $codigoCon = Conexion::obtener($sql);
        $codigoObt = $codigoCon[0];

        if (!$codigoObt['codigo']) {
            $codigoObt['codigo'] = 1;
        } else {
            $codigoObt['codigo']++;
        }

        return $codigoObt['codigo'];
    }

    public function getLista($codigo, $nombre, $tabla, $params = "") {

        $select = " SELECT  a." . $codigo . ", UPPER( a." . $nombre . " ) " . $nombre . "
                    FROM    " . $tabla . " a
                    WHERE   '1' = '1'";

        if ($params == "") {
            $params = array();
        }

        if (isset($params['tipoEstado'])) {
            $select .= " AND a.codTipoEstado = " . $params['codTipoEstado'];
        }

        if (isset($params['perfil'])) {
            $select .= " AND a.codPerfil = " . $params['codPerfil'];
        }

        if (isset($params['codEstado'])) {
            $select .= " AND a.codEstado = " . $params['codEstado'];
        }

        if (isset($params['activos'])) {
            $select .= " AND a.codTipoEstado = '20' ";
        }

        if (isset($params['in'])) {
            $select .= " AND a." . $codigo . " IN( " . $params['in'] . " ) ";
        }

        if (isset($params['not'])) {
            $select .= " AND a." . $codigo . " NOT IN( " . $params['not'] . " ) ";
        }

        if (isset($params['orden'])) {
            $select .= " ORDER BY " . $params['orden'];
        } else {
            $select .= "  ORDER BY " . $nombre . " ASC;";
        }
        return Conexion::obtener($select);
    }

    public function obtenerInfo($codigo, $nombre, $tabla, $valorCodigo, $app = "") {
        $select = " SELECT  a." . $nombre . " nombre
                    FROM    $app." . $tabla . " a
                    WHERE   a." . $codigo . " LIKE '%" . $valorCodigo . "%'";

        $resultado = Conexion::obtener($select);
        $retorno = $resultado[0]['nombre'];

        return $retorno;
    }

    public function sumarDiasFecha($fecha, $cantidadDias) {
        $nuevafecha = strtotime("+" . $cantidadDias . " day", strtotime($fecha));
        $nuevafecha = date("Y-m-d", $nuevafecha);

        return $nuevafecha;
    }

    public function sumarMesesFecha($fecha, $cantidadMese) {
        $nuevafecha = strtotime("+" . $cantidadMese . " month", strtotime($fecha));
        $nuevafecha = date("Y-m-d", $nuevafecha);

        return $nuevafecha;
    }

    function getMenu($codOption) {
        $sql = "
                SELECT  a.*, b.nomOption padre
                FROM    tab_options a, tab_options b
                WHERE   a.codOption = '" . $codOption . "'
                AND     a.codParent = b.codOption
                ";
        return Conexion::obtener($sql);
    }

    public function subirArchivo($carpeta, $fuente, $codigo) {
        if ($fuente[tmp_name]) {//SI HAY UN ARCHIVO ADJUNTO.
            $servidor = $_SERVER['DOCUMENT_ROOT'];
            $carpetaFiles = $servidor . MISESSION . "/files";
            $carpetaOpcion = $carpetaFiles . "/" . $carpeta;
            $carpeta = $carpetaOpcion . "/" . $codigo;

            // Si no existe la carpeta files, la creamos
            if (!file_exists($carpetaFiles)) {
                mkdir($carpetaFiles);
                chmod($carpetaFiles, 0777); //LE DOY TODOS LOS PERMISOS.
            }

            // Si no existe la carpeta docs, la creamos.
            if (!file_exists($carpetaOpcion)) {
                mkdir($carpetaOpcion);
                chmod($carpetaOpcion, 0777); //LE DOY TODOS LOS PERMISOS.
            }

            if (!file_exists($carpeta)) {
                mkdir($carpeta);
                chmod($carpeta, 0777); //LE DOY TODOS LOS PERMISOS.
            }

            $nombre = $fuente[name];
            $nombre = str_replace(" ", "_", $nombre);
            $nombre = str_replace(".", "_", $nombre);
            $miNombre = $ext = explode("_", $nombre);

            $cantidadElementos = count($ext);
            $cantidadElementos = $cantidadElementos - 1;
            $ext = $ext[$cantidadElementos];

            $nombreArchivo = "";
            for ($i = 0; $i < $cantidadElementos; $i++) {
                $nombreArchivo = $nombreArchivo . $miNombre[$i];
            }

            //GUARDAR ARCHIVO EN SERVIDOR.
            $archivo = $nombreArchivo . "." . $ext; //NOMBRE DEL ARCHIVO.
            $RUTA = $carpeta . "/" . $archivo; //RUTA DEL ARCHIVO.
            copy($fuente[tmp_name], $RUTA); //COPIO ARCHIVO EN SERVIDOR.

            return $archivo;
        }

        return "";
    }

    public function getDigitoVerificacion($cod) {
        if (!is_numeric($cod)) {
            return false;
        }

        $arr = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19,
            8 => 37, 11 => 47, 14 => 67, 3 => 13, 6 => 23, 9 => 41, 12 => 53, 15 => 71);
        $x = 0;
        $y = 0;
        $z = strlen($cod);
        $dv = '';

        for ($i = 0; $i < $z; $i++) {
            $y = substr($cod, $i, 1);
            $x += ($y * $arr[$z - $i]);
        }

        $y = $x % 11;
        if ($y > 1) {
            $dv = 11 - $y;
            return $dv;
        } else {
            $dv = $y;
            return $dv;
        }
    }

    function getValorMoneda($valor) {
        $v = str_replace(".", "", $valor);
        $v = str_replace(",", "", $v);
        return number_format($v, 0, ".", ",");
    }

    function borrarItem($items, $indDelete) {
        unset($items[$indDelete - 1]); //BORRAR ITEM.			

        $ITEM = array();
        $i = 0;
        if ($items) {
            foreach ($items as $item) {
                $ITEM[$i] = $item;
                $i++;
            }
        }
        return $ITEM;
    }
    
    /*
      public function obtenerTodo( $tabla, $codigo="", $valorCodigo="", $params="", $app="" )
      {
      $sql =  "
      SELECT  a.*
      FROM    ". $app .".". $tabla ." a
      WHERE   '1' = '1'
      ";

      if( $codigo && $valorCodigo )
      {
      $sql = " WHERE   a.".$codigo." = '".$valorCodigo."'  ";
      }

      if( $params['orden'] )
      {
      $sql .= " ORDER BY " . $params['orden'];
      }
      else
      {
      $sql .= "  ORDER BY 1 ASC;";
      }
      return $result = Conexion::obtener( $sql );
      }



      public function getCiudades()
      {
      $select =   "
      SELECT  a.codigoCiudadxx, a.nombreCiudadxx, b.nombreDepartam, c.nombrePaisxxxx,
      c.siglaxPaisxxxx

      FROM    tab_ciudades a, tab_departam b, tab_paisesxx c

      WHERE   a.numeroEstadoxx = '1'
      AND     a.codigoPaisxxxx = b.codigoPaisxxxx
      AND     a.codigoDepartam = b.codigoDepartam
      AND     b.codigoPaisxxxx = c.codigoPaisxxxx

      ORDER BY a.nombreCiudadxx ASC, c.nombrePaisxxxx ASC, b.nombreDepartam ASC
      ;";
      return Conexion::obtener( $select );
      }

      public function getCiudadPorNombre( $nombreCiudadxx )
      {
      $select =   "
      SELECT  a.codigoCiudadxx

      FROM    tab_ciudades a

      WHERE   a.nombreCiudadxx LIKE '%".$nombreCiudadxx."%'
      ";
      return Conexion::obtener( $select );
      }

      public function getPaisDepto( $codigoCiudadxx )
      {
      $sql =  "
      SELECT  a.nombreCiudadxx, a.codigoPaisxxxx,  a.codigoDepartam
      FROM    tab_ciudades a
      WHERE   a.codigoCiudadxx = '".$codigoCiudadxx."'
      ;";
      $resultado = Conexion::obtener( $sql );
      return $resultado[0];
      }

      public function obtenerNombre( $codigo, $nombre, $tabla, $valorCodigo, $app="" )
      {
      $select =   "
      SELECT  UPPER(a.". $nombre .") nombre

      FROM    $app.". $tabla ." a

      WHERE   a.". $codigo ." = '". $valorCodigo ."'
      ";

      $resultado = Conexion::obtener( $select );
      $resultado = $resultado[0]['nombre'];

      return $resultado;
      }



      // Función que me permite obtener los elementos de una tabla.


      public function obtenerInfoUsuario( $emailxUsuariox )
      {
      $select =   "
      SELECT  a.*, UPPER(a.nombreUsuariox) nombreUsuariox

      FROM    tab_usuarios a

      WHERE   a.emailxUsuariox = '". $emailxUsuariox ."'
      ";

      $resultado = Conexion::obtener( $select );
      $resultado = $resultado[0];

      return $resultado;
      }

      public function sumarDiasFecha( $fecha, $cantidadDias )
      {
      $nuevafecha = strtotime ( "+". $cantidadDias ." day" , strtotime ( $fecha ) ) ;
      $nuevafecha = date ( "Y-m-d" , $nuevafecha );

      return $nuevafecha;
      }

      public function diferenciaFechas( $fechaInicial, $fechaFinal )
      {
      $datetime1 = date_create( $fechaFinal );
      $datetime2 = date_create( $fechaInicial );

      $interval = date_diff( $datetime1, $datetime2 );

      return $interval->format('%R%a');
      }

      public function diferenciaHoras( $horaI, $horaF )
      {
      $datetime1 = new DateTime( '2009-10-11 ' . $horaI . ':00' );
      $datetime2 = new DateTime( '2009-10-11 ' . $horaF . ':00' );

      //$datetime1 = new DateTime('2009-10-11 01:00:00');
      //$datetime2 = new DateTime('2009-10-11 02:03:00');

      $interval = $datetime1->diff($datetime2);

      return $interval->format('%H:%I');
      }

      public function guardarDocumento( $carpetaModulo, $fuente, $codigo, $bd="" )
      {
      if( $_SESSION[DIRAPP] && !$bd )
      {
      $bd = $_SESSION[DIRAPP];
      }

      if( $fuente[tmp_name] && $bd )//SI HAY UN ARCHIVO ADJUNTO.
      {
      $servidor = $_SERVER['DOCUMENT_ROOT'];
      $carpetaCliente = $servidor.$bd;
      $carpetaFiles = $servidor.$bd."/files";
      $carpetaOpcion = $carpetaFiles."/".$carpetaModulo;
      $carpeta = $carpetaOpcion."/".$codigo;

      // Si no existe la carpeta files, la creamos
      if( !file_exists( $carpetaFiles ) )
      {
      mkdir( $carpetaFiles );
      chmod( $carpetaFiles, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      // Si no existe la carpeta docs, la creamos.
      if( !file_exists( $carpetaOpcion ) )
      {
      mkdir( $carpetaOpcion );
      chmod( $carpetaOpcion, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      if( !file_exists( $carpeta ) )
      {
      mkdir( $carpeta );
      chmod( $carpeta, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      $nombre = $fuente[name];
      $nombre = str_replace( " ", "_", $nombre );
      $nombre = str_replace( ".", "_", $nombre );
      $miNombre = $ext = explode( "_", $nombre );

      $cantidadElementos = count( $ext );
      $cantidadElementos = $cantidadElementos - 1;
      $ext = $ext[$cantidadElementos];

      $nombreArchivo = "";
      for( $i=0; $i<$cantidadElementos; $i++ )
      {
      $nombreArchivo = $nombreArchivo . $miNombre[$i];
      }

      //GUARDAR ARCHIVO EN SERVIDOR.
      $archivo = $nombreArchivo. "." .$ext; //NOMBRE DEL ARCHIVO.
      $RUTA = $carpeta. "/" .$archivo; //RUTA DEL ARCHIVO.
      copy( $fuente[tmp_name], $RUTA ); //COPIO ARCHIVO EN SERVIDOR.

      return $archivo;
      }

      return "";
      }

      public function escribirArchivo( $carpeta, $codigo, $contenido )
      {
      $servidor = $_SERVER['DOCUMENT_ROOT'];
      $carpetaCliente = $servidor.$_SESSION[DIRAPP];
      $carpetaFiles = $servidor.$_SESSION[DIRAPP]."/files";
      $carpetaOpcion = $carpetaFiles."/".$carpeta;
      $subCarpeta = $carpetaOpcion."/".$codigo;

      // Si no existe la carpeta files, la creamos
      if( !file_exists( $carpetaFiles ) )
      {
      mkdir( $carpetaFiles );
      chmod( $carpetaFiles, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      // Si no existe la carpeta docs, la creamos.
      if( !file_exists( $carpetaOpcion ) )
      {
      mkdir( $carpetaOpcion );
      chmod( $carpetaOpcion, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      if( !file_exists( $subCarpeta ) )
      {
      mkdir( $subCarpeta );
      chmod( $subCarpeta, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      $ruta = "../".$_SESSION[DIRAPP]."/files/".$carpeta."/".$codigo."/".$codigo.".vcf";
      $fp = fopen( $ruta, "x" );
      fputs( $fp, $contenido );
      return fclose( $fp );
      }

      public function SubirLista( $ruta )
      {
      $archivo = File( $ruta );

      $matrix = array();
      $i = 0;
      $j = 0;

      if( $archivo )
      foreach( $archivo as $linea )
      {
      if( trim($linea) )
      {
      if( strstr( $linea,';' ) )
      {
      $miArreglo = explode( ";", $linea);
      }
      else if( strstr( $linea,',' ) )
      {
      $miArreglo = explode( ",", $linea);
      }

      foreach ( $miArreglo as $row )
      {
      $matrix[$i][$j] = trim($this->clear($row));
      $j++;
      }

      $j = 0;

      $i++;
      }
      }

      $_REQUEST['filas'] = $i;
      return $matrix;
      }

      function clear( $cadena )
      {
      $no_permitidas= array ( "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ", ";", "'" );
      $permitidas   = array ( "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "",  "" );
      $texto = str_replace( $no_permitidas, $permitidas, $cadena );

      return $texto;
      }

      function clearMayusculas( $cadena )
      {
      $no_permitidas= array ( "Á", "É", "Í", "Ó", "Ú", ";", "'" );
      $permitidas   = array ( "á", "é", "í", "ó", "ú", "",  "" );
      $texto = str_replace( $no_permitidas, $permitidas, $cadena );

      return $texto;
      }

      function remplazaTildes( $cadena )
      {
      $no_permitidas = array
      (
      "á","é","í","ó","ú",
      "Á","É","Í","Ó","Ú"
      );

      $permitidas   = array
      (
      "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;",
      "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;"
      );

      $texto = str_replace($no_permitidas, $permitidas ,$cadena);

      return $texto;
      }

      public function consultaLogo( $miApp )
      {
      $select =   "
      SELECT  a.*, b.urlLogo AS urlProducto, c.nombreTercerox

      FROM    sg_tendency.tab_productos b,
      sg_tendency.tab_servicliente a LEFT JOIN sg_tendency.tab_tercerox c ON a.codigoTercerox = c.codigoTercerox

      WHERE   a.codigoProducto = b.codigoProducto
      ";
      if( $miApp )
      {
      $sql .= " AND a.nombreAplicacion = '".$miApp."' ";
      }
      else
      {
      $sql .= " AND a.nombreAplicacion = '".$_SESSION[DIRAPP]."' ";
      }

      return $select = Conexion::obtener( $select );
      }

      public function enviarCorreo( $params )
      {
      $hoy = getdate();
      $diaAno = $hoy[yday];
      $hora = $hoy[hours];

      $para = $params[contactos];

      if( $params[app] )
      {
      $_SESSION[DIRAPP] = $params[app];
      $logoApp = $this -> consultaLogo( $params[app] );
      }
      else
      {
      $logoApp = $this -> consultaLogo();
      }
      $logoApp = $logoApp[0][urlProducto];

      if( $params[logo] )
      {
      $logoAplicacion = $params[logo];
      }
      else
      {
      $logoAplicacion  = "https://www.sgestion.co/sg_panel/client/img/logotendency.png";
      }

      if( $params[app] )
      {
      $logoEmpresa  = "https://www.sgestion.co/".$params[app]."/".$params[app].".png";
      }
      else
      {
      $logoEmpresa  = "https://www.sgestion.co/".$_SESSION[DIRAPP]."/".$_SESSION[DIRAPP].".png";
      }
      $barra   = "https://www.sgestion.co/sg_panel/client/img/headtarea.jpg";
      $footer  = "https://www.sgestion.co/sg_panel/client/img/foottarea.png";

      $título = $params[titulo];

      $degra = "background: -webkit-gradient(linear, left top, left bottom, from( #ffffff ), to( #EEEEEE )); background: -moz-linear-gradient(top, #ffffff, #EEEEEE ); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\"#ffffff\", endColorstr=\"#EEEEEE\" );";

      $mensaje  = "<html>";
      $mensaje .= "<head>";
      $mensaje .= "<title>". $params[titulo] ."</title>";
      $mensaje .= "</head>";
      $mensaje .= "<body>";
      $mensaje .= "<style> @import url(http://fonts.googleapis.com/css?family=Open+Sans); </style>";

      $mensaje .= "<table align='center' style='width:100%; border:1px solid #ddd' cellpadding=0 cellspacing=0  >";
      $mensaje .= "<tr>";

      $params[quitaLogo] = 1;
      if( $_SESSION[DIRAPP] == "sg_roadtrack" )
      {
      $params[quitaLogo] = 1;
      $logoEmpresa  = "https://www.sgestion.co/".$_SESSION[DIRAPP]."/chevy.jpg";

      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoEmpresa ." style='height:auto; width:100%; ' >";
      $mensaje .= "</td>";
      }
      else if( $params[quitaLogo] == 1 && $params[logo] )
      {
      $mensaje .= "<td align='center' >";
      $mensaje .= "<img src=". $params[logo] ." style='width: 100% !important; height: auto;'>";
      $mensaje .= "</td>";
      }
      else if( $params[quitaLogo] == 1 )
      {
      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoEmpresa ." style='padding: 14px; max-height:80px; width:auto; ' >";
      $mensaje .= "</td>";
      }
      else
      {
      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoAplicacion ." height='80' style='max-height:80px; width:auto; ' >";
      $mensaje .= "</td>";
      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoEmpresa ."  height='80' style='max-height:80px; width:auto; ' >";
      $mensaje .= "</td>";
      }

      $mensaje .= "</tr>";
      $mensaje .= "</table>";

      if( $params[color]  )
      {
      $color = $params[color];
      }
      else
      {
      $color = "#1992D1";
      }
      $colorBoton = $color;

      if( $_SESSION[DIRAPP] != "sg_bbogota" )
      {
      $mensaje .= "<div style='background-color:".$color."; height:30px' >";
      $mensaje .= "&nbsp;";
      $mensaje .= "</div>";
      }


      $style = " style='color:#193769; text-align: justify !important; font-family: \"Open Sans\", sans-serif;' ";

      $mensaje .= "<div style='border-left:1px solid #ddd; border-right:1px solid #ddd; padding:20px' >";

      if( $params[noTitulo] != 1 )
      {
      $mensaje .= "<h2 align=center style='font-family: \"Open Sans\", sans-serif;' >$params[titulo]</h2>";
      }

      if( $params[nombreContacto] )
      {
      $mensaje .= "<div>Hola! ". $params[nombreContacto] ."</div>";
      }
      else
      {
      $infoUsuario = $this->obtenerInfoUsuario( $para );
      if( $infoUsuario )
      {
      $mensaje .= "<div>Hola! ". $infoUsuario[nombreUsuariox] ."</div>";
      }
      else
      {
      $mensaje .= "<div>Hola! ". $para ."</div>";
      }
      }

      $mensaje .= "<table width='100%' cellpadding=0 cellspacing=0 >";
      $mensaje .= "<tr>";
      $mensaje .= "<td align='left'>";
      $mensaje .= "<p $style >".$params[texto]."</p>";
      $mensaje .= "</td>";
      $mensaje .= "</tr>";
      $mensaje .= "</table>";

      if( $params[colorBoton] )
      {
      $colorBoton = $params[colorBoton];
      }

      if( $params[link] )
      {
      if( $_SESSION[DIRAPP] == "sg_cargex" )
      {
      $mensaje .= "<br/> <p align = 'center'>".$params[link]."</p> <br/>";
      }
      else
      {
      $mensaje .= "<br/>
      <div align = 'center'>
      <a href='". $params[link] ."'
      style='text-decoration: none; background-color:".$colorBoton."; padding:10px 20px; color:#fff; -webkit-border-radius: 05px; -moz-border-radius: 05px; border-radius: 05px;' >
      ".$params[textoLink]."
      </a>
      </div>
      <br/>";
      }
      }
      elseif( $params[noLink] != 1 )
      {
      if( $_SESSION[DIRAPP] == "sg_cargex" )
      {
      $mensaje .= "<br/> <p align = 'center'>https://sgestion.co/".$_SESSION[DIRAPP]."</p> <br/>";
      }
      else
      {
      $mensaje .= "<br/>
      <div align = 'center'>
      <a href='https://sgestion.co/". $_SESSION[DIRAPP] ."'
      style='text-decoration: none; background-color:#007095; padding:10px 20px; color:#fff; -webkit-border-radius: 05px; -moz-border-radius: 05px; border-radius: 05px;' >
      Ingresar al Sistema de Gesti&oacute;n
      </a>
      </div>
      <br/>";
      }
      }

      if( $params[link2] )
      {
      if( $_SESSION[DIRAPP] == "sg_cargex" )
      {
      $mensaje .= "<br/> <p align = 'center'>".$params[link2]."</p> <br/>";
      }
      else
      {
      $mensaje .= "<br/><br/>
      <div align = 'center'>
      <a href='". $params[link2] ."'
      style='text-decoration: none; background-color:#007095; padding:10px 20px; color:#fff; -webkit-border-radius: 05px; -moz-border-radius: 05px; border-radius: 05px;' >
      ".$params[textoLink2]."
      </a>
      </div>
      <br/>";
      }
      }

      $mensaje .= "</div>";

      if( $params[colorBoton] )
      {
      $mensaje .= "
      <table align='center' style='width:100%' cellpadding=0 cellspacing=0 >
      <tr>
      <td style='font-size:10px; color:#fff; text-align:center; background-color:".$params[colorBoton]."; padding:10px' >
      Este mensaje ha sido generado autom&aacute;ticamente, a las " . get_date() . ".
      </td>
      </tr>
      ";
      }
      else
      {
      $mensaje .= "
      <table align='center' style='width:100%' cellpadding=0 cellspacing=0 >
      <tr>
      <td style='font-size:10px; color:#fff; text-align:center; background-color:".$color."; padding:10px' >
      Este mensaje ha sido generado autom&aacute;ticamente por el <b>Sistema de Gesti&oacute;n</b>, a las " . get_date() . ".
      </td>
      </tr>
      ";
      }

      if( $params[footer] )
      {
      $mensaje .= "
      <tr>
      <td align='center'>
      <img src='". $params[footer] ."' style='width:100%; max-width:100%; height:auto' > <br/>
      </td>
      </tr>
      ";
      }
      else if( $params[noFooter] != 1 )
      {
      $mensaje .= "
      <tr>
      <td align='center'>
      <img src='". $footer ."' style='width:100%; max-width:100%; height:auto' > <br/>
      </td>
      </tr>
      ";
      }

      $style = "height: 30px; width: auto; margin-left: auto; margin-right: auto; display: block; margin-top: 5px;  ";
      $logoTendency   = "https://www.sgestion.co/sg_panel/client//img/tendency_web.png";
      $mensaje .= "
      <tr>
      <td align='center'>
      <br><br>
      ". date( "Y" ) ." Todos los derechos reservados.
      <img src='".$logoTendency."' style='".$style."'>
      </td>
      </tr>
      ";

      $mensaje .= " </table>";

      $mensaje .= "</body>";
      $mensaje .= "</html>";


      if( !$params[from] )
      {
      $params[from] = "Sistema de gestion <noreply@sgestion.co>";
      }

      require_once "smtp/TurboApiClient.php";

      if( $params[turbo] == 1 )
      {
      $email = new Email();
      $email->setFrom( $params[from] );
      $email->setToList( $para );
      //$email->setToList( "david.caicedo@tendencyapps.com" );

      $email->setSubject( $título );
      $email->setContent("Contenido SMTP");
      $email->setHtmlContent( $mensaje );
      $email->addCustomHeader('X-FirstHeader', "value");
      $email->addCustomHeader('X-SecondHeader', "value");
      $email->addCustomHeader('X-Header-to-be-removed', 'value');
      $email->removeCustomHeader('X-Header-to-be-removed');

      // creation of a client that connects with turbo-smtp APIs
      $turboApiClient = new TurboApiClient("elkin.beleno@tendencyapps.com", "SFhgHKYP");

      // email sending
      $response = $turboApiClient->sendEmail( $email );
      //show($response);
      //echo $response[message];     show("ojo8");
      }
      else
      {
      // Para enviar un correo HTML, debe establecerse la cabecera Content-type
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
      $cabeceras .= "From: $params[from]"."\r\n";

      // Enviarlo
      mail( $para, $título, $mensaje, $cabeceras );
      }
      }

      public function enviarCorreo2( $params )
      {
      require_once "smtp/TurboApiClient.php";

      if( false )
      {
      $email = new Email();
      $email->setFrom( $params[from] );
      $email->setToList( $params[contactos] );

      $email->setSubject( $params[titulo] );
      $email->setContent("Contenido SMTP");
      $email->setHtmlContent( $mensaje );
      $email->addCustomHeader('X-FirstHeader', "value");
      $email->addCustomHeader('X-SecondHeader', "value");
      $email->addCustomHeader('X-Header-to-be-removed', 'value');
      $email->removeCustomHeader('X-Header-to-be-removed');

      // creation of a client that connects with turbo-smtp APIs
      $turboApiClient = new TurboApiClient("elkin.beleno@tendencyapps.com", "SFhgHKYP");

      // email sending
      $response = $turboApiClient->sendEmail( $email );
      //show($response);
      //echo $response[message];     show("ojo8");
      }
      else
      {
      // Para enviar un correo HTML, debe establecerse la cabecera Content-type
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
      $cabeceras .= "From: ".$params[from]."\r\n";

      // Enviarlo
      mail( $params[contactos], $params[titulo], $params[texto], $cabeceras );
      }
      }

      public function calcularDV( $codigoTercerox )
      {
      if (! is_numeric($codigoTercerox))
      {
      return false;
      }

      $arr = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19,
      8 => 37, 11 => 47, 14 => 67, 3 => 13, 6 => 23, 9 => 41, 12 => 53, 15 => 71);
      $x = 0;
      $y = 0;
      $z = strlen($codigoTercerox);
      $dv = '';

      for ($i=0; $i<$z; $i++)
      {
      $y = substr($codigoTercerox, $i, 1);
      $x += ($y*$arr[$z-$i]);
      }

      $y = $x%11;
      if ($y > 1)
      {
      $dv = 11-$y;
      return $dv;
      }
      else
      {
      $dv = $y;
      return $dv;
      }
      }

      public function Autocompletar( $fuente, $source )
      {

      $html = "<script>$(function() {";
      $html .= "$( '#$fuente' ).autocomplete({
      minLength: 3,
      source: $source,
      focus: function( event, ui )
      {
      $( '#$fuente' ).val( ui.item.label );
      $( '#".$fuente."_value' ).val( ui.item.value );
      return false;
      },
      search:function( event, ui )
      {
      //alert('hip');
      $( '#".$fuente."_value' ).val( '' );
      },
      select: function( event, ui )
      {
      $( '#$fuente' ).val( ui.item.label );
      $( '#".$fuente."_value' ).val( ui.item.value );
      return false;
      }
      });";
      $html .= "});</script>";

      if( !$_REQUEST[$fuente] )
      {
      $_REQUEST[$fuente._value] = "";
      }
      $html .= "<input type='hidden' required name='".$fuente."_value' id='".$fuente."_value' value='".$_REQUEST[$fuente."_value"]."'>";

      return $html;
      }

      public function generaAutocompletar( $datos, $nombreRetorno )
      {
      $script  = "<script>";
      $script .= "var ".$nombreRetorno." = [\n";
      $arreglo = array();      //  show($datos);
      if( $datos )
      {
      foreach( $datos as $row )
      {
      $label = strtoupper( trim( $row['nombre'] ) );
      $arreglo[] = "{ value:\"$row['codigo']\", label:\"$label\" }";
      }
      }
      $script .= implode( ",\n", $arreglo );
      $script .= "\n];";
      $script .= "</script>";
      return $script;
      }

      function obtenerAutocompletar( $codigo, $nombre, $tabla, $params )
      {
      $sql =  "
      SELECT  a.". $codigo ." codigo, a.". $nombre ." nombre

      FROM    ".$tabla ." a

      WHERE   '1' = '1'
      ";

      if( $params['activos'] )
      {
      $sql .= " AND a.numeroEstadoxx = '1' ";
      }

      $sql .= " ORDER BY  a.". $nombre ." ASC;";
      return Conexion::obtener( $sql );
      }

      public function getObtenerNavegador()
      {
      require('lib/browser_class_inc.php');
      $b = new browser();
      $info = $b->whatBrowser();
      $navegador = $info['browsertype'];
      $version = $info['version'];

      return $navegador."-". $version ;
      }

      public function getRealIP()
      {
      if( !empty($_SERVER['HTTP_CLIENT_IP']) )
      {
      return $_SERVER['HTTP_CLIENT_IP'];
      }

      if ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
      {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
      }

      return $_SERVER['REMOTE_ADDR'];
      }

      public function getCiudadPorCodigo( $codigoCiudadxx )
      {
      $select =   "
      SELECT  a.codigoCiudadxx, UPPER(a.nombreCiudadxx) nombreCiudadxx, b.nombreDepartam, c.nombrePaisxxxx,
      c.siglaxPaisxxxx

      FROM    tab_ciudades a, tab_departam b, tab_paisesxx c

      WHERE   a.numeroEstadoxx = '1'
      AND     a.codigoPaisxxxx = b.codigoPaisxxxx
      AND     a.codigoDepartam = b.codigoDepartam
      AND     b.codigoPaisxxxx = c.codigoPaisxxxx
      AND     a.codigoCiudadxx = '".$codigoCiudadxx."'

      ORDER BY a.nombreCiudadxx DESC
      ";

      return Conexion::obtener( $select );
      }

      function guardarImportar( $fuente )
      {
      if( $fuente[tmp_name] )//SI HAY UN ARCHIVO ADJUNTO.
      {
      $servidor = $_SERVER['DOCUMENT_ROOT'];
      $carpetaCliente = $servidor.$_SESSION[DIRAPP];
      $carpetaFiles = $servidor.$_SESSION[DIRAPP]."/files";
      $carpeta = $carpetaFiles."/temporal";

      // Si no existe la carpeta files, la creamos
      if( !file_exists( $carpetaFiles ) )
      {
      mkdir( $carpetaFiles );
      chmod( $carpetaFiles, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      if( !file_exists( $carpeta ) )
      {
      mkdir( $carpeta );
      chmod( $carpeta, 0777 );//LE DOY TODOS LOS PERMISOS.
      }

      $nombreArchivo = $fuente[name];

      //GUARDAR ARCHIVO EN SERVIDOR.
      $archivo = $nombreArchivo;//NOMBRE DEL ARCHIVO.
      $RUTA = $carpeta."/".$archivo;//RUTA DEL ARCHIVO.
      move_uploaded_file( $fuente[tmp_name], $RUTA );//COPIO ARCHIVO EN SERVIDOR.

      return $RUTA;
      }

      return "";
      }

      function obtenerAreaUsuario( $codigoRespasig, $app="" )
      {
      $select =   "
      SELECT  a.codigoAreacont
      FROM    ".$app."tab_usuarios a
      WHERE   a.emailxUsuariox = '". $codigoRespasig ."'
      ;";
      return Conexion::obtener( $select );
      }

      function obtenerMeses()
      {
      $meses = "";

      $meses[0][0] = 1;
      $meses[0][1] = "ENERO";

      $meses[1][0] = 2;
      $meses[1][1] = "FEBRERO";

      $meses[2][0] = 3;
      $meses[2][1] = "MARZO";

      $meses[3][0] = 4;
      $meses[3][1] = "ABRIL";

      $meses[4][0] = 5;
      $meses[4][1] = "MAYO";

      $meses[5][0] = 6;
      $meses[5][1] = "JUNIO";

      $meses[6][0] = 7;
      $meses[6][1] = "JULIO";

      $meses[7][0] = 8;
      $meses[7][1] = "AGOSTO";

      $meses[8][0] = 9;
      $meses[8][1] = "SEPTIEMBRE";

      $meses[9][0] = 10;
      $meses[9][1] = "OCTUBRE";

      $meses[10][0] = 11;
      $meses[10][1] = "NOVIEMBRE";

      $meses[11][0] = 12;
      $meses[11][1] = "DICIEMBRE";

      return $meses;
      }

      function obtenerAnos( $inicio, $fin )
      {
      $ano = "";

      $indice = 0;
      for( $i=$inicio; $i<=$fin; $i++ )
      {
      $ano[$indice][0] = $i;
      $ano[$indice][1] = $i;

      $indice++;
      }

      return $ano;
      }

      function actualizaDivudocu()
      {
      $sql =  "
      SELECT  a.codigoProcesox
      FROM    tab_procesox a
      WHERE   a.numeroEstadoxx = '1'
      ";
      $procesos = Conexion::obtener( $sql );

      foreach ( $procesos as $pr )
      {
      // Consultamos los documentos que pertenecen al proceso
      $sql =  "
      SELECT  a.codigoDocument
      FROM    tab_docproce a
      WHERE   a.codigoProcesox = '".$pr[codigoProcesox]."'
      ";
      $documentos = Conexion::obtener( $sql );

      foreach ( $documentos as $doc )
      {
      // Version
      $sql =  "
      SELECT  a.codigoDocument, a.numeroVersionx
      FROM    tab_gesdocum a
      WHERE   a.codigoProcesox = '".$pr[codigoProcesox]."'
      AND     a.codigoDocument = '".$doc[codigoDocument]."'
      AND     a.indicaAprobaxx = '1'
      GROUP BY a.codigoDocument
      ORDER BY a.numeroVersionx DESC
      ";
      $version = Conexion::obtener( $sql );
      $version = $version[0][numeroVersionx];

      if( $version )
      {
      // Consultamos los usuarios que pertenecen al proceso
      $sql =  "
      SELECT  a.emailxUsuariox
      FROM    tab_usuarios a
      WHERE   a.estadoUsuario = '1'
      AND     a.codigoAreacont = '".$pr[codigoProcesox]."'
      ";
      $usuarios = Conexion::obtener( $sql );
      foreach ( $usuarios as $us )
      {
      $sql =  "
      INSERT INTO  tab_divudocu
      (
      codigoDocument ,codigoProcesox ,numeroVersionx ,
      usuariVistaxxx ,usuariCreacion ,fechaxCreacion ,
      indicaVistaxxx ,
      observDivulgac ,indicaLecturax
      )
      VALUES
      (
      '".$doc[codigoDocument]."', '".$pr[codigoProcesox]."', '".$version."',
      '".$us[emailxUsuariox]."', 'SOPORTE TENDENCY', NOW(),
      '1',
      'Divulgación Inicial', '1'
      )
      ";
      Conexion::ejecutar( $sql );
      }
      }
      }
      }
      }

      function getNombreDia( $fecha )
      {
      $fechats = strtotime( $fecha ); //a timestamp

      //el parametro w en la funcion date indica que queremos el dia de la semana
      //lo devuelve en numero 0 domingo, 1 lunes,....
      switch( date('w', $fechats) )
      {
      case 0:
      $miDia = "DOMINGO";
      break;

      case 1:
      $miDia = "LUNES";
      break;

      case 2:
      $miDia = "MARTES";
      break;

      case 3:
      $miDia = "MIÉRCOLES";
      break;

      case 4:
      $miDia = "JUEVES";
      break;

      case 5:
      $miDia = "VIERNES";
      break;

      case 6:
      $miDia = "SÁBADO";
      break;
      }

      return $miDia;
      }

      function getNombreDiaNumero( $dia )
      {
      switch( $dia )
      {
      case 0:
      $miDia = "DOMINGO";
      break;

      case 1:
      $miDia = "LUNES";
      break;

      case 2:
      $miDia = "MARTES";
      break;

      case 3:
      $miDia = "MIÉRCOLES";
      break;

      case 4:
      $miDia = "JUEVES";
      break;

      case 5:
      $miDia = "VIERNES";
      break;

      case 6:
      $miDia = "SÁBADO";
      break;
      }

      return $miDia;
      }

      function getGrilla( $arrayCampos, $arrayInfo, $nombreMaximo, $miIndice = 0 )
      {
      $_REQUEST[$nombreMaximo] = count( $arrayInfo );

      foreach ( $arrayInfo as $ai )
      {
      foreach ( $arrayCampos as $ac )
      {
      $_REQUEST[$ac.$miIndice] = $ai[$ac];
      }

      $miIndice++;
      }
      }

      function getExtension( $nombre )
      {
      $nombre = str_replace( " ", "_", $nombre );
      $nombre = str_replace( ".", "_", $nombre );
      $miNombre = $ext = explode( "_", $nombre );

      $cantidadElementos = count( $ext );
      $cantidadElementos = $cantidadElementos - 1;
      $ext = $ext[$cantidadElementos];

      return $ext;
      }

      public function enviarCorreoPersonalizado( $params )
      {
      require_once "smtp/TurboApiClient.php";

      if( !$params[from] )
      {
      $params[from] = "info@sgestion.co";
      }

      $logoEmpresa = $params[logo];
      if( !$params[logo] )
      {
      $logoEmpresa  = "https://www.sgestion.co/".$_SESSION[DIRAPP]."/".$_SESSION[DIRAPP].".png";
      }

      if( !$params[marco] )
      {
      $params[marco] = "d4d4d4";
      }

      $div_marco =    "
      width: 760px;
      border: 60px solid #".$params[marco].";
      ";

      $div_cabecera =   " width: 760px !important;
      background: -webkit-gradient(linear, left top, left bottom, from( #ffffff ), to( #EEEEEE ));
      background: -moz-linear-gradient(top, #ffffff, #EEEEEE );
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\"#ffffff\", endColorstr=\"#EEEEEE\" );
      padding: 15px 0px;
      ";

      $div_barra =    "
      height: 30px;
      background-color: #".$params[marco].";
      width: 760px;
      ";

      $div_contenido =    "
      background-color: #fff;
      width: 660px;
      min-height: 200px;
      padding: 30px 50px;
      ";

      $parra =    "
      text-align: justify;
      font-size: 13px;
      color: #5f5f5f;
      ";

      $mensaje  = "<html>";

      $mensaje .= "<head>";
      $mensaje .= "<title>".$params[titulo]."</title>";
      $mensaje .= "</head>";

      $mensaje .= "<body>";


      $mensaje .= "<div align='center'>";
      $mensaje .= "<div style='$div_marco' align='center'>";

      $mensaje .= "<div style='$div_cabecera' align='center'>";
      $mensaje .= "<img src=".$logoEmpresa." style='width:200px; max-width:200px; height:auto;'>";
      $mensaje .= "</div>";

      $mensaje .= "<div style='$div_barra' align='center'>";
      $mensaje .= "</div>";

      $mensaje .= "<div style='$div_contenido'>";

      $mensaje .= "<p style='".$parra."'>";
      $mensaje .= $params[texto];
      $mensaje .= "</p>";

      $mensaje .= "</div>";

      $mensaje .= "</div>";
      $mensaje .= "</div>";

      $mensaje .= "</table>";

      $mensaje .= "</body>";

      $mensaje .= "</html>";

      if( $params[turbo] == 1 )
      {
      $email = new Email();
      $email->setFrom( $params[from] );
      $email->setToList( $params[contactos] );
      //$email->setToList( "david.caicedo@tendencyapps.com" );

      $email->setSubject( $params[titulo] );
      $email->setContent( "Contenido SMTP" );
      $email->setHtmlContent( $mensaje );
      $email->addCustomHeader('X-FirstHeader', "value");
      $email->addCustomHeader('X-SecondHeader', "value");
      $email->addCustomHeader('X-Header-to-be-removed', 'value');
      $email->removeCustomHeader('X-Header-to-be-removed');

      // creation of a client that connects with turbo-smtp APIs
      $turboApiClient = new TurboApiClient("elkin.beleno@tendencyapps.com", "SFhgHKYP");

      $response = $turboApiClient->sendEmail( $email );
      }
      else
      {
      // Para enviar un correo HTML, debe establecerse la cabecera Content-type
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
      $cabeceras .= "From: $params[from]"."\r\n";

      // Enviarlo
      mail( $params[contactos], $params[titulo], $mensaje, $cabeceras );
      }

      /*
      $hoy = getdate();
      $diaAno = $hoy[yday];
      $hora = $hoy[hours];

      $para = $params[contactos];

      if( $params[app] )
      {
      $_SESSION[DIRAPP] = $params[app];
      $logoApp = $this -> consultaLogo( $params[app] );
      }
      else
      {
      $logoApp = $this -> consultaLogo();
      }
      $logoApp = $logoApp[0][urlProducto];

      if( $params[logo] )
      {
      $logoAplicacion = $params[logo];
      }
      else
      {
      $logoAplicacion  = "https://www.sgestion.co/sg_panel/client/img/logotendency.png";
      }

      if( $params[app] )
      {
      $logoEmpresa  = "https://www.sgestion.co/".$params[app]."/".$params[app].".png";
      }
      else
      {
      $logoEmpresa  = "https://www.sgestion.co/".$_SESSION[DIRAPP]."/".$_SESSION[DIRAPP].".png";
      }
      $barra   = "https://www.sgestion.co/sg_panel/client/img/headtarea.jpg";
      $footer  = "https://www.sgestion.co/sg_panel/client/img/foottarea.png";

      $título = $params[titulo];



      $mensaje  = "<html>";
      $mensaje .= "<head>";
      $mensaje .= "<title>". $params[titulo] ."</title>";
      $mensaje .= "</head>";
      $mensaje .= "<body>";
      $mensaje .= "<style> @import url(http://fonts.googleapis.com/css?family=Open+Sans); </style>";

      $mensaje .= "<table align='center' style='width:100%; border:1px solid #ddd' cellpadding=0 cellspacing=0  >";
      $mensaje .= "<tr>";

      $params[quitaLogo] = 1;
      if( $_SESSION[DIRAPP] == "sg_roadtrack" )
      {
      $params[quitaLogo] = 1;
      $logoEmpresa  = "https://www.sgestion.co/".$_SESSION[DIRAPP]."/chevy.jpg";

      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoEmpresa ." style='height:auto; width:100%; ' >";
      $mensaje .= "</td>";
      }
      else if( $params[quitaLogo] == 1 && $params[logo] )
      {
      $mensaje .= "<td align='center' >";
      $mensaje .= "<img src=". $params[logo] ." style='width: 100% !important; height: auto;'>";
      $mensaje .= "</td>";
      }
      else if( $params[quitaLogo] == 1 )
      {
      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoEmpresa ." style='padding: 14px; max-height:80px; width:auto; ' >";
      $mensaje .= "</td>";
      }
      else
      {
      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoAplicacion ." height='80' style='max-height:80px; width:auto; ' >";
      $mensaje .= "</td>";
      $mensaje .= "<td align='center' style='$degra' >";
      $mensaje .= "<img src=". $logoEmpresa ."  height='80' style='max-height:80px; width:auto; ' >";
      $mensaje .= "</td>";
      }

      $mensaje .= "</tr>";
      $mensaje .= "</table>";

      if( $params[color]  )
      {
      $color = $params[color];
      }
      else
      {
      $color = "#1992D1";
      }
      $colorBoton = $color;

      if( $_SESSION[DIRAPP] != "sg_bbogota" )
      {
      $mensaje .= "<div style='background-color:".$color."; height:30px' >";
      $mensaje .= "&nbsp;";
      $mensaje .= "</div>";
      }


      $style = " style='color:#193769; text-align: justify !important; font-family: \"Open Sans\", sans-serif;' ";

      $mensaje .= "<div style='border-left:1px solid #ddd; border-right:1px solid #ddd; padding:20px' >";

      if( $params[noTitulo] != 1 )
      {
      $mensaje .= "<h2 align=center style='font-family: \"Open Sans\", sans-serif;' >$params[titulo]</h2>";
      }

      if( $params[nombreContacto] )
      {
      $mensaje .= "<div>Hola! ". $params[nombreContacto] ."</div>";
      }
      else
      {
      $infoUsuario = $this->obtenerInfoUsuario( $para );
      if( $infoUsuario )
      {
      $mensaje .= "<div>Hola! ". $infoUsuario[nombreUsuariox] ."</div>";
      }
      else
      {
      $mensaje .= "<div>Hola! ". $para ."</div>";
      }
      }

      $mensaje .= "<table width='100%' cellpadding=0 cellspacing=0 >";
      $mensaje .= "<tr>";
      $mensaje .= "<td align='left'>";
      $mensaje .= "<p $style >".$params[texto]."</p>";
      $mensaje .= "</td>";
      $mensaje .= "</tr>";
      $mensaje .= "</table>";

      if( $params[colorBoton] )
      {
      $colorBoton = $params[colorBoton];
      }

      if( $params[link] )
      {
      $mensaje .= "<br/>
      <div align = 'center'>
      <a href='". $params[link] ."'
      style='text-decoration: none; background-color:".$colorBoton."; padding:10px 20px; color:#fff; -webkit-border-radius: 05px; -moz-border-radius: 05px; border-radius: 05px;' >
      ".$params[textoLink]."
      </a>
      </div>
      <br/>";
      }
      elseif( $params[noLink] != 1 )
      {
      $mensaje .= "<br/>
      <div align = 'center'>
      <a href='https://sgestion.co/". $_SESSION[DIRAPP] ."'
      style='text-decoration: none; background-color:#007095; padding:10px 20px; color:#fff; -webkit-border-radius: 05px; -moz-border-radius: 05px; border-radius: 05px;' >
      Ingresar al Sistema de Gesti&oacute;n
      </a>
      </div>
      <br/>";
      }

      if( $params[link2] )
      {
      $mensaje .= "<br/><br/>
      <div align = 'center'>
      <a href='". $params[link2] ."'
      style='text-decoration: none; background-color:#007095; padding:10px 20px; color:#fff; -webkit-border-radius: 05px; -moz-border-radius: 05px; border-radius: 05px;' >
      ".$params[textoLink2]."
      </a>
      </div>
      <br/>";
      }

      $mensaje .= "</div>";

      if( $params[colorBoton] )
      {
      $mensaje .= "
      <table align='center' style='width:100%' cellpadding=0 cellspacing=0 >
      <tr>
      <td style='font-size:10px; color:#fff; text-align:center; background-color:".$params[colorBoton]."; padding:10px' >
      Este mensaje ha sido generado autom&aacute;ticamente, a las " . get_date() . ".
      </td>
      </tr>
      ";
      }
      else
      {
      $mensaje .= "
      <table align='center' style='width:100%' cellpadding=0 cellspacing=0 >
      <tr>
      <td style='font-size:10px; color:#fff; text-align:center; background-color:".$color."; padding:10px' >
      Este mensaje ha sido generado autom&aacute;ticamente por el <b>Sistema de Gesti&oacute;n</b>, a las " . get_date() . ".
      </td>
      </tr>
      ";
      }

      if( $params[footer] )
      {
      $mensaje .= "
      <tr>
      <td align='center'>
      <img src='". $params[footer] ."' style='width:100%; max-width:100%; height:auto' > <br/>
      </td>
      </tr>
      ";
      }
      else if( $params[noFooter] != 1 )
      {
      $mensaje .= "
      <tr>
      <td align='center'>
      <img src='". $footer ."' style='width:100%; max-width:100%; height:auto' > <br/>
      </td>
      </tr>
      ";
      }

      $style = "height: 30px; width: auto; margin-left: auto; margin-right: auto; display: block; margin-top: 5px;  ";
      $logoTendency   = "https://www.sgestion.co/sg_panel/client//img/tendency_web.png";
      $mensaje .= "
      <tr>
      <td align='center'>
      <br><br>
      ". date( "Y" ) ." Todos los derechos reservados.
      <img src='".$logoTendency."' style='".$style."'>
      </td>
      </tr>
      ";

      $mensaje .= " </table>";

      $mensaje .= "</body>";
      $mensaje .= "</html>";


      if( !$params[from] )
      {
      $params[from] = "Sistema de gestion <noreply@sgestion.co>";
      }


     * 
     *       
      } */
}
