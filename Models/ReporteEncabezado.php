<?php

    require'fpdf/fpdf.php';

    class ReporteEncabezado extends FPDF
    {
        private $fechaInicio;
        private $fechaFinal;

        public function __construct($fechaInicio, $fechaFinal, $orientation, $unit, $size)
        {
            parent::__construct($orientation, $unit, $size);
            $this->fechaInicio = $fechaInicio;
            $this->fechaFinal = $fechaFinal;
        }
        
        public function encabezadoTabla()
        {
            $this->SetFont('Arial','B',10);
            $this->Cell(46, 5, utf8_decode("Nombre y Apellido"), 1, 0, 'C', 0);
            $this->Cell(46, 5, utf8_decode("Cédula"), 1, 0, 'C', 0);
            $this->Cell(46, 5, utf8_decode("Asistencias"), 1, 0, 'C', 0);
            $this->Cell(46, 5, utf8_decode("Inasistencias"), 1, 0, 'C', 0);
            $this->Cell(46, 5, utf8_decode("Total"), 1, 0, 'C', 0);
            $this->Cell(46, 5, utf8_decode("Observaciones"), 1, 1, 'C', 0);
            
        }

        public function tituloDocumento()
        {
            $this->Cell(280, 10, utf8_decode('CONTROL DE ASISTENCIA MENSUAL'),0,0,'C');
        }

        public function fechaReporte()
        {
            $this->fechaInicio = strtotime($this->fechaInicio);
            $this->fechaFinal = strtotime($this->fechaFinal);
            return"Fecha: " . date("d-m-y", $this->fechaInicio) . " hasta: " . date("d-m-y", $this->fechaFinal);
        }
        
        /*Cabecera del docummento PDF. Tiene el logo de la universidad en el extremo superior izquierdo del documento,
        un título centrado, una tabla con información (Fecha, Dependecia y Departamento) y por último los encabezados de la tabla
        con nombre y apellido, cédula y cantidad de horas trabajadas*/
    
        function Header()
        {
            //logo de la universidad
            $this->Image('unerg.png',5,8,30);
    
            $this->SetFont('Arial','B',14); //fuente de letra
   
            $this->Cell(80);

            // Inicio de Membrete
           $this->Cell(120, 10, utf8_decode('República Bolivariana de Venezuela'),0,0,'C');
           $this->Ln(10);//Salto de línea
           $this->Cell(280, 10, utf8_decode('Ministerio del Poder Popular para la Educación Universitaria Ciencia y Tecnología'),0,0,'C');
           $this->Ln(10);//Salto de línea
           $this->Cell(280, 10, utf8_decode('Universidad Nacional Experimental de los Llanos Centrales Rómulo Gallegos'),0,0,'C');
           //Fin de membrete

           //Información adicional
            $this->SetXY(180, 60);
            $this->Cell(100, 5, $this->fechaReporte(), 1, 1, 'L', 0);
            $this->SetXY(180, 65);
            $this->Cell(100, 5, utf8_decode("Dependencia: Despacho Rectorado"), 1, 1, 'L', 0);
            $this->SetXY(180, 70);
            $this->Cell(100, 5, utf8_decode("Departamento: Dirección Informática"), 1, 1, 'L', 0);
            $this->SetXY(180, 75);
            $this->Cell(100, 5, utf8_decode("OTRAS"), 1, 1, 'L', 0);

            //Salto de línea
            $this->Ln(10);

            $this->tituloDocumento();            
            
            //Salto de línea
            $this->Ln(20);
           
            //Encabezado de la tabla
            $this->encabezadoTabla();

        }
    }

?>