<?php
	
	require_once 'modelobase.php';
	
	class Registrar extends ModeloBase
	{
		

		/*Este método sirve para registrar un número de telefono asociado a un trabajador o pasante*/
		public function insertarTelefono($telefono, $cedulaPersona)
		{
			if ($this->obtenerExistenciaTelefonoPersona($telefono)) {

				$this->vincularTelefonoExistentePersona($telefono, $cedulaPersona);
			}
			else{
				
				$this->insertarTelefonoPersona($telefono);
				$this->vincularTelefonoExistentePersona($telefono, $cedulaPersona);
			}	
		}

		/*Este método sirve para registrar los datos básicos de un trabajador o pasante*/
		public function insertarPersona($nombre, $cedula, $domicilio, $cargo, $estatus)
		{
			$nombre = mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
			$direccion = mb_convert_case($direccion, MB_CASE_TITLE, "UTF-8");
			
			$sql = "INSERT INTO personas(Nombre_apellido, Cedula, Dirección, ID_rol, ID_estatus)
			VALUES (?, ?, ?, (SELECT ID FROM rol WHERE Tipo_rol=?), 
			(SELECT ID FROM estatus WHERE Estatus=?))";
			$personaNueva = $this->db->prepare($sql);
			$personaNueva->bind_param("sssss", $nombre, $cedula, $domicilio, $cargo, $estatus);
			$personaNueva->execute();
		}
	}
?>