<?php

    require'ReporteEncabezado.php';

    class ReporteTrabajador extends ReporteEncabezado
    {

        public function __construct($fechaInicio, $fechaFinal, $orientation, $unit, $size)
        {
            parent::__construct($fechaInicio, $fechaFinal, $orientation, $unit, $size);
            
        }
    }
?>