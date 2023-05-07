<?php
	
	
	class LoginController{

		/*Al llamar a esta ruta se mostrará en el navegador el formulario login del
		administrador.*/
		public function mostrarFormulario()
		{
			require"views/login.php";
		}

		/*Esta ruta se encargará de hacer las validaciones de lo que puede suceder en el momento
		en el que el usuario interactué con el inicio de sesión del administrador*/
		public function inicioSesion()
		{
			session_start();
			
			require"Models/login.php";

			$login = new Login();

			/*Si se accede a esta ruta habiendo iniciado sesión, entonces se mostrará en el navegador 
			el Inicio del sistema en modo administrador*/
			if (isset($_SESSION['id'])) {

				header("Location:?controller=Inicio&action=mostrarInicio");
				exit();
				
			}

			/*Si los campos POST username y password están vacíos pero no se envía el formulario (no se presionó el botón Entrar),
			entonces se mostrará el login nuevamente*/
			else if (!isset($_POST['Entrar']) && empty($_POST['username']) && empty($_POST['password'])) {
		
				$this->mostrarFormulario();
			}

			/*Si se envía el formulario con ambos campos vacíos, entonces se mostrará un mensaje de error*/
			else if (empty($_POST['username']) || empty($_POST['password'])) {
				
				$aviso = "Por favor, rellene todos los campos";
				require"views/login.php";
			}

			/*Si se envía el formulario con el nombre de usuario y contraseña correctos, entonces
			se redigirá al inicio del sistema en modo administrador*/
			else if (!strcmp($login->obtenerNombreUsuario(), $_POST['username']) && 
			password_verify($_POST['password'], $login->obtenerContrasenia())) {

				$_SESSION['id'] = $login->obtenerID($_POST['username']);
				header("Location:?controller=Inicio&action=mostrarInicio");
				exit();
			}

			/*Si se envía el formulario con el nombre de usuario y contraseña incorrectos, entonces
			se mostrará un mensaje de error*/
			else{

				$aviso = "Credenciales no válidas";
				require"views/login.php";

			}
		}

		/*Esta ruta se encargará de cerrar la sesión del modo administrador
		y redirigir a el formulario del login */
		public function cerrarSesion()
		{
			session_start();
			session_unset();
			session_destroy();
		
			$this->mostrarFormulario();

		}
	}

?>