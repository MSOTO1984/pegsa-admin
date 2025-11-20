<?php

include( "../../../lib/params.php" );
include( "../../../lib/helper.php" );
include( "../../../lib/phpspreadsheet/autoload.php" );
include( "../../../lib/conexion.php" );

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelAsistencias {

    var $spreadsheet;
    var $hoja1;
    var $codUsuario = '';
    var $fechaIni = '';
    var $fechaFin = '';
    var $codTipoCapacitacion = '';
    var $codCapacitacion = '';
    var $codEmpleado = '';
    var $codEstado = '';

    function GenerarExcel() {
        $this->init();
        $this->Contenido();
    }

    function init() {

        $this->spreadsheet = new Spreadsheet();
        $this->hoja1 = $this->spreadsheet->getActiveSheet();

        if (isset($_REQUEST['codUsuarioEx'])) {
            $this->codUsuario = $_REQUEST['codUsuarioEx'];
        }

        if (isset($_REQUEST['codEmpleadoEx'])) {
            $this->codEmpleado = $_REQUEST['codEmpleadoEx'];
        }

        if (isset($_REQUEST['codTipoCapacitacionEx'])) {
            $this->codTipoCapacitacion = $_REQUEST['codTipoCapacitacionEx'];
        }

        if (isset($_REQUEST['codCapacitacionEx'])) {
            $this->codCapacitacion = $_REQUEST['codCapacitacionEx'];
        }

        if (isset($_REQUEST['fechaIniEx'])) {
            $this->fechaIni = $_REQUEST['fechaIniEx'];
        }

        if (isset($_REQUEST['fechaFinEx'])) {
            $this->fechaFin = $_REQUEST['fechaFinEx'];
        }

        if (isset($_REQUEST['codEstadoEx'])) {
            $this->codEstado = $_REQUEST['codEstadoEx'];
        }

        Conexion::conectar();
    }

    function Contenido() {

        $this->encabezadosHoja();
        $this->contenidoHoja();

        $this->spreadsheet
                ->getProperties()
                ->setCreator(MIAPP)
                ->setLastModifiedBy('msoto')
                ->setTitle('Informe Excel Asistencias')
                ->setDescription('Informe de Excel Asistencias');

        $writer = new Xlsx($this->spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode("informe_asistencias-" . date("YmdHis") . ".xlsx") . '"');
        $writer->save('php://output');
    }

    function encabezadosHoja() {
        $encabezado = [
            "Tipo Capacitacion", "Capacitacion", "Fecha Capacitacion",
            "Duracion", "Documento Colaborador", "Colaborador",
            "Fecha Firma", "Ciudad", "Departamento", "Estado"
        ];
        $this->hoja1->setTitle("Asistencias");
        $this->hoja1->fromArray($encabezado, null, 'A1');
        $this->hoja1->getStyle("A1:{$this->hoja1->getHighestColumn()}1")->getFont()->setBold(true);
        foreach ($this->hoja1->getColumnIterator() as $column) {
            $this->hoja1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    function contenidoHoja() {
        $lista = $this->getInforme();
        if (isset($lista)) {
            $numeroDeFila = 2;
            foreach ($lista as $row) {
                $count = 1;
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomTipoCapacitacion']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomCapacitacion']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['fecha']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['tiempo']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['codEmpleado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomEmpleado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['fechaCreacion']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomCiudad']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomDepto']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomEstado']);
                $numeroDeFila++;
            }
        }
    }

    function getInforme() {
        $sql = "SELECT  a.*, 
                        b.nomEmpleado, 
                        c.nomCapacitacion, 
                        c.fecha,
                        c.tiempo,
                        c.observacion,
                        c.codEstado,
                        d.nomTipoCapacitacion,
                        e.nomUsuario,
                        f.nomCiudad,
                        g.nomDepto,
                        h.nomEstado
                  FROM  tab_asistencias a
                            LEFT JOIN tab_empleados b on a.codEmpleado = b.codEmpleado
                            LEFT JOIN tab_capacitaciones c on a.codCapacitacion = c.codCapacitacion
                            LEFT JOIN tab_tipo_capacitacion d on c.codTipoCapacitacion = d.codTipoCapacitacion          
                            LEFT JOIN tab_usuarios e on c.codUsuario = e.codUsuario
                            LEFT JOIN tab_ciudades f on c.codCiudad = f.codCiudad
                            LEFT JOIN tab_deptos g on f.codDepto = g.codDepto
                            LEFT JOIN tab_estados h on c.codEstado = h.codEstado
                 WHERE  1 = 1";

        if ($this->codUsuario != '') {
            $sql .= " AND c.codUsuario = '" . $this->codUsuario . "'";
        }

        if ($this->fechaIni != '' && $this->fechaFin != '') {
            $sql .= " AND c.fecha between '" . $this->fechaIni . "' AND '" . $this->fechaFin . "'";
        }

        if ($this->codTipoCapacitacion != '') {
            $sql .= " AND c.codTipoCapacitacion = '" . $this->codTipoCapacitacion . "'";
        }

        if ($this->codCapacitacion != '') {
            $sql .= " AND a.codCapacitacion = '" . $this->codCapacitacion . "'";
        }

        if ($this->codEmpleado != '') {
            $sql .= " AND a.codEmpleado = '" . $this->codEmpleado . "'";
        }

        if ($this->codEstado != '') {
            $sql .= " AND c.codEstado = '" . $this->codEstado . "' ";
        }

        $sql .= " ORDER BY c.codCapacitacion DESC";
        return Conexion::obtener($sql);
    }
}

$excelAsistencias = new ExcelAsistencias();
$excelAsistencias->GenerarExcel();
