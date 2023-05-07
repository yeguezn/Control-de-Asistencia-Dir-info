<?php
	require 'modelobase.php';
	
	class Editar extends modeloBase{

		/*Este método sirve para obtener los otros roles (pasante, obrero, administrativo) que puede desempeñar una persona en el departamento, a excepción del que se le pase por parametro*/
		public function obtenerOtrosRoles($rolPersona)
		{
			$sql = "SELECT Tipo_rol FROM rol WHERE Tipo_rol != ?";
			$rol = $this->db->prepare($sql);
			$rol->bind_param("s", $rolPersona);
			$rol->execute();
			$resultado = $rol->get_result(); 
			
			return $resultado;
		}

		/*Este método sirve para obtener los otros estatus (Activo, sin justificar, de permiso, de reposo, deshabilitado) que puede tener una persona en el departamento, a excepción del que se le pase por parametro*/
		public function obtenerOtrosEstatus($estatusPersona)
		{
			$sql = "SELECT Estatus FROM estatus WHERE Estatus != ?";
			$estatus = $this->db->prepare($sql);
			$estatus->bind_param("s", $estatusPersona);
			$estatus->execute();
			$resultado = $estatus->get_result(); 
			
			return $resultado;
		}

		public function actualizarDatosPersona($nombre, $cedulaNueva, $direccion, $rol, $estatus, $cedulaOriginal)
		{

			$nombre = mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
			$direccion = mb_convert_case($direccion, MB_CASE_TITLE, "UTF-8");

			$sql = "UPDATE personas SET Nombre_apellido=?, cedula=?, Dirección=?, ID_rol=(SELECT ID FROM rol WHERE Tipo_rol=?),
			ID_estatus=(SELECT ID FROM estatus WHERE Estatus=?) WHERE cedula= ?";
			$persona = $this->db->prepare($sql);
			$persona->bind_param("ssssss", $nombre, $cedulaNueva, $direccion, $rol, $estatus, $cedulaOriginal);
			$persona->execute();
		}

		public function actualizarIdTelefono($cedulaPersona, $telefonoNuevoPersona, $telefonoActualPersona)
		{
			$sql = "UPDATE telefono_personas SET ID_telefono=(SELECT ID FROM telefono WHERE Num_telef=?) WHERE 
			ID_persona=(SELECT ID FROM personas WHERE cedula=?) AND ID_telefono=(SELECT ID FROM telefono WHERE Num_telef=?)";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("sss", $telefonoNuevoPersona, $cedulaPersona, $telefonoActualPersona);
			$telefono->execute();
		}

		public function actualizarTelefono2($cedulaPersona, $telefonoNuevo)
		{
			$telefono2Persona = $this->obtenerTelefono2Persona($cedulaPersona);
			$sql = "UPDATE telefono SET Num_telef=? WHERE Num_telef=?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("ss", $telefonoNuevo, $telefono2Persona);
			$telefono->execute();
		}

		/*Dependiendo de si la persona tenga o no un segundo telefono, se registrará un nuevo número de telefono o 
		se actualizara aquel que se encuentre almacenado en la base de datos*/
		public function actualizarTelefono2Persona($cedulaPersona, $telefonoPersona)
		{
			/*Si el numero de telefono enviado en el formulario se encuentra ya registrado en la base de datos y la persona no tiene asociado un segundo número de telefono, entonces relacionar a la persona con el número de telefono ya existente*/
			if ($this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			!strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$this->vincularTelefonoExistentePersona($telefonoPersona, $cedulaPersona);
				
			}

			/*Si el numero de telefono enviado en el formulario se encuentra ya registrado en la base de datos y la persona tiene asociado un segundo número de telefono, entonces asociar a la persona con el número de telefono ya existente*/
			else if ($this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$telefonoActualPersona = $this->obtenerTelefono2Persona($cedulaPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoPersona, $telefonoActualPersona);

				//Si el número actual de la persona NO está vinculado a otro usuario, entonces eliminarlo
				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
				
			}

			/*Si el numero de telefono enviado en el formulario se no encuentra ya registrado en la base de datos y la persona tiene asociado un segundo número de telefono, entonces registrar el nuevo número y asociarlo a la persona que lo registró*/
			else if (!$this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$telefonoActualPersona = $this->obtenerTelefono2Persona($cedulaPersona);
				$this->insertarTelefonoPersona($telefonoPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoPersona, $telefonoActualPersona);


				//Si el número actual de la persona NO está vinculado a otro usuario, entonces eliminarlo
				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
				
			}

			/*Si el numero de telefono enviado en el formulario se no encuentra ya registrado en la base de datos y la persona no tiene asociado un segundo número de telefono, entonces registrar el nuevo número y asociarlo a la persona que lo registró*/
			else if (!$this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			!strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$this->insertarTelefonoPersona($telefonoPersona);
				$this->vincularTelefonoExistentePersona($telefonoPersona, $cedulaPersona);

				//Si el número actual de la persona NO está vinculado a otro usuario, entonces eliminarlo
				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
				
			}
			
		}


		public function actualizarTelefonoPersona($cedulaPersona, $telefonoNuevoPersona)
		{
			/*Si el numero de telefono enviado en el formulario se encuentra ya registrado en la base de datos,
			entonces remplazar el telefono que la persona ingresó en su registro por el número que ya fue registrado previamente
			por otro usuario*/
			if ($this->obtenerExistenciaTelefonoPersona($telefonoNuevoPersona)) {
					
				$telefonoActualPersona = $this->obtenerTelefonoPersona($cedulaPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoNuevoPersona, $telefonoActualPersona);

				//Si el número actual de la persona NO está vinculado a otro usuario, entonces eliminarlo
				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
			}
			
			//Caso contrario, registrar en la base de datos el nuevo número enviado desde el formulario 
			else{

				$telefonoActualPersona = $this->obtenerTelefonoPersona($cedulaPersona);
				$this->insertarTelefonoPersona($telefonoNuevoPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoNuevoPersona, $telefonoActualPersona);

				//Si el número actual de la persona NO está vinculado a otro usuario, entonces eliminarlo
				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
			}
		}	

	}  
?>