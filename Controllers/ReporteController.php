<?php

	
	class ReporteController{

		/*Esta ruta se encargará de cargar el formulario para generar los reportes de 
		trabajadores o pasantes*/
		public function mostrarFormulario()
		{
			session_start();
			
			if (isset($_SESSION['id'])) {
				require 'views/reportes.php';
			}
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			else{
				require 'views/denegado.html';
			}
		}

		/*Esta ruta se encargará de hacer las validaciones de lo que introduzca el usuario en el formulario de
		generar reportes de asistencia de trabajadores o pasantes*/
		public function generarReporte()
		{
			require 'Models/reporte.php';

			session_start();
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";
			
			}
			else{

				/*Si el usuario envía el formulario con los dos campos requeridos vacíos (fecha de inicio y fecha de fin),
				entonces se mostrará un mensaje de error*/
				if (empty($_POST["fechaInicio"]) || empty($_POST["fechaFin"])) {
				
					$aviso = "Por favor, rellene los campos";
					require 'views/reportes.php';
			
				}else{

					$reporte = new Reporte();
					$fechaInicio = $reporte->darFormatoFecha($_POST['fechaInicio']); /*esta instrucción sirve para darle al campo 
					fechaInicio el formato de fecha año-mes-día*/
					
					$fechaFin = $reporte->darFormatoFecha($_POST['fechaFin']); /*esta instrucción sirve para darle al campo 
					fechaFin el formato de fecha año-mes-día*/
					
					$primeraFechaAnio = date("Y-01-01"); //Primero de Enero del año actual
					$ultimaFechaAnio = date("Y-12-31"); //Treinta y uno de Diciembre del año actual

					/*Si se envía el formulario con el campo fechaInicio con una fecha posterior a la que tiene el
					campo fechaFin, entonces se mostrará un mensaje de error*/
					if ($fechaInicio > $fechaFin) {
					
						$aviso = "La fecha de inicio no puede ser mayor a la fecha del fin";
						require 'views/reportes.php';
					}

					/*Si se envía el formulario con el campo fechaInicio con una fecha anterior al primero de Enero del año actual
					o si la fechaFin tiene una fecha posterior al último día del año actual*/
					else if ($fechaInicio < $primeraFechaAnio || $fechaFin > $ultimaFechaAnio){

						$aviso = "Las fechas deben estar en un rango entre el $primeraFechaAnio y $ultimaFechaAnio";
						require 'views/reportes.php';

					}

					/*Si se envía el formulario con ambas fechas entre el primero de Enero del año actual y
					el último de Diciembre del año actual, entonces se generará el reporte respectivo*/
					else{
				
						$reporte->generarPDF($_POST['cargo'], $fechaInicio, $fechaFin);
					}
				}	
			}	
			
		}
		
		
	}
?>