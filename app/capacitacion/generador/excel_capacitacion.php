<?php

include( "../../../lib/params.php" );
include( "../../../lib/helper.php" );
include( "../../../lib/phpspreadsheet/autoload.php" );
include( "../../../lib/conexion.php" );

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelCapacitacion {

    var $spreadsheet;
    var $hoja1;
    var $codCapacitacion = '';

    function GenerarExcel() {
        $this->init();
        $this->Contenido();
    }

    function init() {

        $this->spreadsheet = new Spreadsheet();
        $this->hoja1 = $this->spreadsheet->getActiveSheet();

        if (isset($_REQUEST['codCapacitacionEx'])) {
            $this->codCapacitacion = $_REQUEST['codCapacitacionEx'];
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
        header('Content-Disposition: attachment; filename="' . urlencode("informe_capacitacion-" . date("YmdHis") . ".xlsx") . '"');
        $writer->save('php://output');
    }

    function encabezadosHoja() {
        $encabezado = [
            "Tipo Capacitacion", "Capacitacion", "Fecha Capacitacion", "Duracion (Min)",
            "Ciudad Capacitacion", "Departamento Capacitacion", "Estado Capacitacion",
            "Nro. Documento", "Colaborador", "Email", "Telefono", "Ciudad Colaborador",
            "Departamento Colaborador", "Estado Colaborador", "Asistio ?"
        ];
        $this->hoja1->setTitle("Capacitaciones");
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
            $capacitacion = $this->getCapacitacion();
            foreach ($lista as $row) {
                $count = 1;
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['nomTipoCapacitacion']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['nomCapaCitacion']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['fecha']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['tiempo']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['nomCiudad']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['nomDepto']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $capacitacion['nomEstado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['codEmpleado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomEmpleado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['emailEmpleado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['celEmpleado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomCiudad']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomDepto']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['nomEstado']);
                $this->hoja1->setCellValueByColumnAndRow($count++, $numeroDeFila, $row['asistio']);
                $numeroDeFila++;
            }
        }
    }

    function getInforme() {

        $sql = "SELECT  a.codEmpleado,
                        a.nomEmpleado,
                        a.emailEmpleado,
                        a.celEmpleado,
                        b.nomCiudad,
                        c.nomDepto,
                        d.nomEstado,
                        CASE 
                            WHEN e.codEmpleado IS NULL THEN 'NO'
                            ELSE 'SI'
                        END AS asistio
                 FROM   tab_empleados a
                                LEFT JOIN tab_ciudades b ON a.codCiudad = b.codCiudad
                                LEFT JOIN tab_deptos c ON b.codDepto = c.codDepto
                                LEFT JOIN tab_estados d ON a.codEstado = d.codEstado
                                LEFT JOIN tab_asistencias e ON e.codEmpleado = a.codEmpleado AND e.codCapacitacion = " . $this->codCapacitacion . "
                    WHERE a.codEstado = 1
                    ORDER BY asistio DESC";

        return Conexion::obtener($sql);
    }

    function getCapacitacion() {
        $sql = " SELECT a.nomCapaCitacion,
                        a.fecha,
                        a.tiempo,
                        b.nomTipoCapacitacion,                      
                        c.nomCiudad,
                        d.nomDepto,
                        e.nomEstado
                  FROM  tab_capacitaciones a
                                LEFT JOIN tab_tipo_capacitacion b ON a.codTipoCapacitacion = b.codTipoCapacitacion
                                LEFT JOIN tab_ciudades c ON a.codCiudad = c.codCiudad                                
                                LEFT JOIN tab_deptos d ON c.codDepto = d.codDepto
                                LEFT JOIN tab_estados e ON a.codEstado = e.codEstado
                 WHERE  a.codCapacitacion = " . $this->codCapacitacion;
        $result = Conexion::obtener($sql);
        if (isset($result)) {
            return $result[0];
        }
        return null;
    }
}

$excelCapacitacion = new ExcelCapacitacion();
$excelCapacitacion->GenerarExcel();
