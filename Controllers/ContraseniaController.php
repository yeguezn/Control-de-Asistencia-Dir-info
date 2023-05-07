<?php

	class ContraseniaController
	{
		/*Al llamar a esta ruta se mostrará en el navegador el formulario para cambiar la contraseña del
		administrador. Para ello se necesita inicar sesión, Si la sesión no está iniciada entonces
		se mostrará una vista que negará el uso del formulario*/
		public function mostrarFormulario()
		{

			session_start();
			
			if (isset($_SESSION['id'])) {
				require "views/cambiarContrasenia.php";
			}else{
				require "views/denegado.html";
			}
			
		}

		/*Esta ruta se encargará de hacer las validaciones de lo que introduzca el usuario en el formulario de
		cambiar contraseña, esta ruta es tipo POST y las variables POST que maneja son la contraseña nueva y la confirmación
		de la misma*/
		public function cambiarContrasenia()
		{
			require 'Models/contrasenia.php';
			require 'Controllers/LoginController.php';

			session_start();
			
			/*Si se accede a esta ruta sin haber iniciado sesión, entonces se negará el acceso al formulario de cambiar contraseña del sistema*/
			if (!isset($_SESSION['id'])) {
				
				require "views/denegado.html";
			
			/*Si se accede a esta ruta habiendo iniciado sesión, entonces se llevarán a cabo las siguientes
			casos dependiendo de la acción que realice el usuario dentro del formulario*/
			}else{

				$contrasenia = new Contrasenia();
				

				/*Si el usuario envía el formulario con los dos campos vacíos, entonces se mostrará un mensaje de error*/
				if (empty($_POST["contrasenia"]) || empty($_POST["contraseniaConfirmada"])) {
					$aviso = "Por favor, rellene todos los campos";
					require"Views/cambiarContrasenia.php";
				}

				/*Si el usuario envía el formulario y las variables POST coinciden (que tengan el mismo contenido), entonces
				se cambiará la contraseña del administrador registrada en el sistema*/
				else if (!strcmp($_POST["contrasenia"], $_POST["contraseniaConfirmada"])) {

					$nuevaContrasenia = password_hash($_POST['contrasenia'], PASSWORD_BCRYPT);

					$contrasenia->cambiarContrasenia($nuevaContrasenia);

					header("Location:?controller=Login&action=cerrarSesion");
					exit();
				
				/*Si el usuario envía el formulario y las variables POST no coinciden (no tienen el mismo contenido), 
				entonces se mostrará un mensaje de error*/
				}else{

					$aviso = "Las contraseñas no coinciden";
					require"Views/cambiarContrasenia.php";
				}
			}	
		}
	}
?>