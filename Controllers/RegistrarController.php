<?php
	
	class RegistrarController{

		/*Esta ruta se encargará de cargar la página de registrar personas del modo administrador, en la que
		se podrá registrar los datos de un usuario en concreto*/
		public function mostrarFormulario()
		{
			session_start();

			/*Si el administrador inició sesión, entonces se redirigirá al inicio del modo 
			administrador y mostrará los datos de todas las personas registradas en el sistema*/

			if (isset($_SESSION['id'])) {
				require "views/registrar.php";
			}
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			
			else{
				require "views/denegado.html";
			}
			
			
		}


		/*Esta ruta se encargará de hacer las validaciones de lo que introduzca el usuario en el formulario de
		registrar datos de persona*/
		public function registrarPersona()
		{
			session_start();
			
			/*Si se intenta acceder a esta ruta sin haber iniciado sesión, entonces se negará acceso al modo administrador*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";
			
			}else{

				/*las variables patronCedula y patronTelefono son expresiones regulares que se encargan
				de validar que la cedula y los telefonos ingresados sean correctos.*/

				$patronCedula = "/^[0-9]{7,8}$/"; /* Indica que la cédula debe tener entre 7 u 8 digitos*/
				
				$patronTelefono = "/^(0416|0426|0412|0424|0414|0246|0243|0212)[0-9]{7}$/"; /*Indica que el telefono debe iniciar con 
				(0416 o 0426 o 0412 o 0424 o 0414 o 0246 o 0243 o 0212) y que seguidamente debe tener 7 dígitos más*/

				require"models/registrar.php";
				$registrar = new Registrar();

				/*Si se accede a la ruta registrarPersona sin haber enviado el formulario, entonces 
				se mostrará el formulario de registro vacío*/
				if (!isset($_POST['Marcar'])) {
					require "views/registrar.php";

				}

				/*Si el usuario envía el formulario con todos los campos requeridos vacíos (Cédula, Nombre, Telefono y Domicilio),
				entonces se mostrará un mensaje de error y se volverá a mostrar el formulario con los datos de la persona a 
				la que le desea editar sus datos*/
				
				else if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['domicilio'])) {
				
					$aviso = "Por favor, rellene todos los campos requeridos (Cédula, Nombre, Telefono, Domicilio)";
					$class = 'AvisoError';
					require "views/registroFallido.php";
				}


				/*Si el usuario envía el formulario y los campos telefono y telefono2 coinciden (tienen el mismo contenido), 
				entonces se mostrará un mensaje de error*/
				else if (!strcmp($_POST['telefono'], $_POST['telefono2'])) {
					
					$aviso = "No puede introducir dos veces el mismo número de telefono";
					$class = 'AvisoError';
					require "views/registroFallido.php";

				}

				/*Si el usuario envía un formulario con número de cédula ya registrado en el sistema, entonces
				se motrará un mensaje de error*/
				else if ($registrar->obtenerCedulaPersona($_POST['cedula'])){

					$cedula = $_POST['cedula'];
					$aviso = "El número de cédula $cedula ya está registrado";
					$class = 'AvisoError';
					require "views/registroFallido.php";
				}


				/*Si el usuario envía un formulario con todos los campos requeridos correctos y no especifica un segundo número de telefono
				entonces se registrarán los datos de la persona*/
				else if (!empty($_POST['nombre']) && !empty($_POST['domicilio']) && preg_match($patronCedula, $_POST['cedula']) && 
				preg_match($patronTelefono, $_POST['telefono']) && empty($_POST['telefono2'])) {

					$registrar->insertarPersona($_POST['nombre'], $_POST['cedula'], $_POST['domicilio'], $_POST['cargo'],$_POST['estatus']);
					$registrar->insertarTelefono($_POST['telefono'], $_POST['cedula']);

					header("Location:?controller=Inicio&action=mostrarInicio");
					exit();
				}

				/*Si el usuario envía un formulario con todos los campos requeridos correctos y ademas se especifica un segundo número de telefono entonces se registrarán los datos de la persona*/
				else if (!empty($_POST['nombre']) && !empty($_POST['domicilio']) && preg_match($patronCedula, $_POST['cedula']) && 
				preg_match($patronTelefono, $_POST['telefono']) && preg_match($patronTelefono, $_POST['telefono2'])) {

					$registrar->insertarPersona($_POST['nombre'], $_POST['cedula'], $_POST['domicilio'], $_POST['cargo'], 
					$_POST['estatus']);
					$registrar->insertarTelefono($_POST['telefono'], $_POST['cedula']);
					$registrar->insertarTelefono($_POST['telefono2'], $_POST['cedula']);

					header("Location:?controller=Inicio&action=mostrarInicio");
					exit();
				}
				/*Si el usuario envía un formulario y la cedula y el telefono no se apegan a las expresiones regulares
				entonces se mostrará un mensaje de error*/
				else if (!preg_match($patronCedula, $_POST['cedula']) || !preg_match($patronTelefono, $_POST['telefono'])) {

					$aviso = "Por favor, verifique el campo cedula y/o el campo telefono";
					$class = 'AvisoError';
					require "views/registroFallido.php";
				
				}
			}	
		}
	}
?>