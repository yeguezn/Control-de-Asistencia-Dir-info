<?php

	class AsistenciaController{

		/*Al llamar a esta ruta se mostrará en el navegador el formulario para marcar la asistencia de una persona
		en la fecha actual*/
		public function mostrarFormulario(){

			require_once "views/formularioAsistencia.php";
		}

		/*Esta ruta se encargará de hacer las validaciones de lo que introduzca el usuario en el formulario de
		asistencia, esta ruta es tipo POST y la variable POST que maneja es el número de cédula de una persona*/
		public function marcarAsistencia()
		{
			require_once "models/asistencia.php";

			$asistencia = new Asistencia();

			/*Si el campo cedula del formulario está vacío y no se ha pulsado el botón Marcar, entonces
			que se muestre nuevamente el formulario*/
			if (!isset($_POST['Marcar']) && empty($_POST['cedula'])) {
				$this->mostrarFormulario();
			}
			
			/*Si el campo cedula del formulario está vacío, entonces
			que se muestre el formulario con un mensaje de error*/
			else if (empty($_POST["cedula"])) {
        
       			echo "<div class='AvisoError'>Por favor, rellene el campo cédula</div>";
       			require "views/formularioAsistencia.php";
      		
      		}

      		/*Si la cédula ingresada en el formulario no se encuentra registrada en el sistema, 
      		entonces que se muestre el formulario con un mensaje de error*/
      		else if (!$asistencia->obtenerCedulaPersona($_POST["cedula"])) {
      			
      			echo "<div class='AvisoError'>No existe un trabajador con ese número de cédula</div>";
       			require "views/formularioAsistencia.php";
      		}
      				
      		/*Si la persona, al ingresar su cédula, se obtiene su hora de entrada en la fecha actual
      		pero no ha marcado su salida entonces, marcará la hora de salida en la fecha actual*/
      		else if ($asistencia->obtenerEntradaPersona($_POST["cedula"]) && 
      		$asistencia->obtenerSalidaPersona($_POST["cedula"]) == NULL){
					
				$asistencia->marcarSalida($_POST['cedula']);

				$horaSalida = $asistencia->obtenerSalidaPersona($_POST["cedula"]);
				$nombreUsuario = $asistencia->obtenerNombreUsuario($_POST["cedula"]);
				$fecha = $asistencia->obtenerFechaAsistencia($_POST["cedula"]);

				echo "<div class='AvisoExito'>El personal administrativo $nombreUsuario marcó 
				su salida en la fecha: $fecha y hora a las $horaSalida</div>";
       			require "views/formularioAsistencia.php";
			
			}

			/*Si la persona, al ingresar su cédula, se detecta que está registrada en el sistema pero no 
			ha marcado su hora de entrada en la fecha actual, entonces marcará su hora de entrada en la fecha
			actual*/
      		else if ($asistencia->obtenerCedulaPersona($_POST["cedula"]) &&
      		!$asistencia->obtenerEntradaPersona($_POST["cedula"])) {

      			$asistencia->marcarEntrada($_POST["cedula"]);

      			$horaEntrada = $asistencia->obtenerEntradaPersona($_POST["cedula"])["Hora_entrada"];
				$nombreUsuario = $asistencia->obtenerNombreUsuario($_POST["cedula"]);
				$fecha = $asistencia->obtenerFechaAsistencia($_POST["cedula"]);


				echo "<div class='AvisoExito'>El personal administrativo $nombreUsuario marcó 
				su entrada en la fecha: $fecha y hora a las $horaEntrada</div>";
       			require "views/formularioAsistencia.php";

       		/*Si ninguna de las condiciones anteriores se cumple eso quiere decir que la persona ya marcó su asistencia en la fecha actual*/
			}else{

				$nombreUsuario = $asistencia->obtenerNombreUsuario($_POST["cedula"]);
				echo "<div class='AvisoExito'>El personal administrativo $nombreUsuario ya marcó
				su asistencia por el día de hoy</div>";
       			require "views/formularioAsistencia.php";
			}
		}
	}

?>
