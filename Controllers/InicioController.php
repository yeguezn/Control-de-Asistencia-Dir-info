<?php
	
	class InicioController{

		/*Esta ruta se encargará de cargar la página de inicio del administrador, en la que
		se mostrará todos los datos de las personas registradas en el sistema*/
		public function mostrarInicio()
		{
			session_start();
			
			/*Si el administrador inició sesión, entonces se redirigirá al inicio del modo 
			administrador y mostrará los datos de todas las personas registradas en el sistema*/
			if (isset($_SESSION['id'])) {
				require 'models/inicio.php';
				$inicio = new Inicio();
				$resultado = $inicio->mostrarTodos();
				require "views/inicio.php";
				
			}
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			else{
				require "views/denegado.html";
			}
		}

		/*Esta ruta se encargará de de lo que introduzca o no el usuario en el inicio, esta ruta es tipo POST 
		y la variable POST que maneja es la cedula de la persona*/
		public function buscarPersona()
		{
			session_start();
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";
			}

			/*Si el administrador inició sesión correctamente, entonces se redirigirá al inicio del modo 
			administrador*/
			else{
				require 'models/inicio.php';
				$inicio = new Inicio();

				/*Si la variable POST cedula tiene un valor entonces se mostrará en la tabla los datos del titular
				de esa cédula*/
				if (isset($_POST["cedula"])) {
					$resultado = $inicio->buscarPersona($_POST["cedula"]);
				}
				require "views/inicio.php";
			}	
		}

		/*A esta ruta se accede dandole click al botón deshabilitar que tiene cada persona en la tabla del Inicio
		del modo administrador*/
		public function eliminarPersona()
		{	
			session_start();
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";

			
			}
			/*Si se da click en el botón deshabilitar habiendo iniciado sesión, entonces
			se eliminará a la persona de la base de datos*/
			else{
				require"models/inicio.php";
				$inicio = new Inicio();
				$inicio->eliminarDatosPersona($_GET["cedula"]);
				header("Location:?controller=Inicio&action=mostrarInicio");
				exit();
			}	
			
		}
	}
?>