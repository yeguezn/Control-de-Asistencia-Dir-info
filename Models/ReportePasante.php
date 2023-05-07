<?php

    require'ReporteEncabezado.php';

    class ReportePasante extends ReporteEncabezado
    {
       

        public function __construct($fechaInicio, $fechaFinal, $orientation, $unit, $size)
        {
            parent::__construct($fechaInicio, $fechaFinal, $orientation, $unit, $size);
            
        }
        
        public function encabezadoTabla()
        {

            $this->SetFont('Arial','B',10);
            $this->Cell(92, 5, utf8_decode("Nombre y Apellido"), 1, 0, 'C', 0);
            $this->Cell(92, 5, utf8_decode("Cédula"), 1, 0, 'C', 0);
            $this->Cell(92, 5, utf8_decode("Cantidad de Horas"), 1, 1, 'C', 0);
            
        }

        public function tituloDocumento()
        {
            $this->Cell(280, 10, utf8_decode('CUMPLIMIENTO DE HORAS DE LOS PASANTES'),0,0,'C');
        }
        
    }

?>