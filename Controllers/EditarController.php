<?php
	
	class EditarController 
	{
		
		/*A esta ruta se accede dandole click al botón editar que tiene cada persona en la tabla del Inicio
		del modo administrador. Al darle click aparece un formulario con nombre, cédula, rol, telefonos y estatus
		de una persona en concreto*/
		public function editarPersonaFormulario()
		{
			session_start();
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";
			
			}

			/*Caso contrario, se cargan los datos de una persona en concreto en un formulario*/
			else{
				require"models/editar.php";
				$editar = new Editar();
				$datosPersona = $editar->buscarPersona($_GET["cedula"]);
				$datosPersona = $datosPersona->fetch_assoc();
				$estatusPersona = $editar->obtenerEstatusPersona($datosPersona['cedula']);
				require"views/editar.php";
			}	
			
		}

		/*Esta ruta se encargará de hacer las validaciones de lo que introduzca el usuario en el formulario de
		editar datos de persona*/
		public function editarPersona()
		{
			session_start();
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";
			
			}

			/*En el caso contrario, se evaluará lo que introduzca o no el usuario*/
			else{

				/*las variables patronCedula y patronTelefono son expresiones regulares que se encargan
				de validar que la cedula y los telefonos ingresados sean correctos.*/

				$patronCedula = "/^[0-9]{7,8}$/"; /* Indica que la cédula debe empezar con la letra V y que seguidamente debe tener entre 7 u 8 digitos*/
				
				$patronTelefono = "/^(0416|0426|0412|0424|0414|0246|0243|0212)[0-9]{7}$/"; /*Indica que el telefono debe iniciar con 
				(0416 o 0426 o 0412 o 0424 o 0414 o 0246 o 0243 o 0212) y que seguidamente debe tener 7 dígitos más*/
				
				require"models/editar.php";
				$editar = new Editar();

				/*Si el usuario envía el formulario con todos los campos requeridos vacíos (Cédula, Nombre, Telefono y Domicilio),
				entonces se mostrará un mensaje de error y se volverá a mostrar el formulario con los datos de la persona a 
				la que le desea editar sus datos*/
				
				if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['domicilio'])) {

					$datosPersona = $editar->buscarPersona($_GET["cedula"]);
					$datosPersona = $datosPersona->fetch_assoc();
					$estatusPersona = $editar->obtenerEstatusPersona($datosPersona['cedula']);
				
					$aviso = "Por favor, rellene todos los campos requeridos (Cédula, Nombre, Telefono, Domicilio)";
					$class = 'AvisoError';
					require "views/editar.php";
				}

				/*Si el usuario envía el formulario y los campos telefono y telefono2 coinciden (tienen el mismo contenido), 
				entonces se mostrará un mensaje de error*/
				else if (!strcmp($_POST['telefono'], $_POST['telefono2'])) {
					
					$datosPersona = $editar->buscarPersona($_GET["cedula"]);
					$datosPersona = $datosPersona->fetch_assoc();
					$estatusPersona = $editar->obtenerEstatusPersona($datosPersona['cedula']);
					
					$aviso = "No puede introducir dos veces el mismo número de telefono";
					$class = 'AvisoError';
					require "views/editar.php";

				}

				/*Si el usuario envía el formulario y los campos cédula y telefono se apegan a las expresiones regulares
				mientras que telefono2 no se apega a su expresión regular entonces se actualizan todos los datos de la persona
				a excepción del telefono2*/
				else if (preg_match($patronCedula, $_POST['cedula']) && preg_match($patronTelefono, $_POST['telefono']) && 
				!preg_match($patronTelefono, $_POST['telefono2'])) {

					$editar->actualizarDatosPersona($_POST['nombre'], $_POST['cedula'], $_POST['domicilio'], $_POST['cargo'], 
					$_POST['estatus'], $_GET['cedula']);
					$editar->actualizarTelefonoPersona($_POST['cedula'], $_POST['telefono']);
					header("Location:?controller=Inicio&action=mostrarInicio");
					exit();
				}

				/*Si el usuario envía el formulario y los campos cédula, telefono y telefono2 se apegan a  las expresiones regulares
				entonces se actualizan todos los datos de la persona, incluyendo un segundi número de telefono*/
				else if (preg_match($patronCedula, $_POST['cedula']) && preg_match($patronTelefono, $_POST['telefono']) && 
				preg_match($patronTelefono, $_POST['telefono2'])) {

					$editar->actualizarDatosPersona($_POST['nombre'], $_POST['cedula'], $_POST['domicilio'], $_POST['cargo'], 
					$_POST['estatus'], $_GET['cedula']);
					$editar->actualizarTelefonoPersona($_POST['cedula'], $_POST['telefono']);
					$editar->actualizarTelefono2Persona($_POST['cedula'], $_POST['telefono2']);
					header("Location:?controller=Inicio&action=mostrarInicio");
					exit();
				
				/*Si ninguna de las condiciones anteriores se cumplen, entonces se mostrará un mensaje de error*/
				}else{
					
					$datosPersona = $persona->buscarPersona($_GET["cedula"]);
					$datosPersona = $datosPersona->fetch_assoc();
					$estatusPersona = $persona->obtenerEstatusPersona($datosPersona['cedula']);
					
					$aviso = "Por favor, compruebe los datos que ingresó";
					$class = 'AvisoError';
					require "views/editar.php";
				}

			}	
			
		}
	}
?>