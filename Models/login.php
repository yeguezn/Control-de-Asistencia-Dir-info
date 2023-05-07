<?php

	require_once 'modelobase.php';
	
	class Login extends ModeloBase{

		/*Este método sirve para obtener la contraseña del administrador del sistema*/
		public function obtenerContrasenia()
		{
			$sql = "SELECT Clave FROM administrador";
			$dato = $this->db->prepare($sql);
			$dato->execute();
			$resultado = $dato->get_result(); 
			$credencial = $resultado->fetch_assoc();
			return $credencial['Clave'];

		}

		/*Este método sirve para obtener el nombre de usuario del administrador del sistema*/
		public function obtenerNombreUsuario()
		{
			$sql = "SELECT Nombre_usuario FROM administrador";
			$dato = $this->db->prepare($sql);
			$dato->execute();
			$resultado = $dato->get_result(); 
			$credencial = $resultado->fetch_assoc();
			return $credencial['Nombre_usuario'];

		}

		/*Este método sirve para obtener el ID del administrador del sistema*/
		public function obtenerID($nombreUsuario)
		{
			$sql = "SELECT ID FROM administrador WHERE Nombre_usuario = ? ";
			$dato = $this->db->prepare($sql);
			$dato->bind_param("s", $nombreUsuario);
			$dato->execute();
			$resultado = $dato->get_result(); 
			$identificador = $resultado->fetch_assoc();
			return $identificador['ID'];

		}

	}
?>