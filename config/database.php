<?php
    
    class database{
        public  static function conectar(){
            
            $conexion = new mysqli("localhost", "root", "", "control_asistencia");
            return $conexion;
        }

    } 


?>