<?php

	require"modelobase.php";

	class Contrasenia extends ModeloBase
	{
		/*Este método actualiza la contraseña del administrador por una nueva contraseña que
		el administrador haya definido*/
		public function cambiarContrasenia($nuevaContrasenia)
		{
			$sql = "UPDATE administrador SET Clave=?";
			$contrasenia = $this->db->prepare($sql);
			$contrasenia->bind_param("s", $nuevaContrasenia);
			$contrasenia->execute();
		}
	}
?>