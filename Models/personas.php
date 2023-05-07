<?php
	require 'modelobase.php';
	
	class Inicio extends modeloBase{

		/*Este método sirve para buscar los datos de una persona a partir de su número de cédula*/
		public function buscarPersona($cedulaPersona)
		{
			$sql = "SELECT personas.ID, personas.Nombre_apellido, personas.cedula, rol.Tipo_rol, personas.Dirección 
			FROM personas INNER JOIN rol ON personas.ID_rol = rol.ID WHERE personas.cedula=?";
			$persona = $this->db->prepare($sql);
			$persona->bind_param("s", $cedulaPersona);
			$persona->execute();
			$resultado = $persona->get_result(); 
			
			return $resultado;
		}

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

		/*Este método sirve para obtener a todas los trabajadores y pasantes registrados en el sistema*/
		public function mostrarTodos()
		{
			$sql = "SELECT personas.ID, personas.Nombre_apellido, personas.cedula, rol.Tipo_rol FROM 
			personas INNER JOIN rol ON personas.ID_rol = rol.ID";
			$personas = $this->db->prepare($sql);
			$personas->execute();
			$resultado = $personas->get_result(); 
			
			return $resultado;
		}

		/*Este método sirve para obtener el estatus (Activo, sin justificar, de permiso, de reposo, deshabilitado) que puede tener una persona en el departamento*/
		public function obtenerEstatusPersona($cedulaPersona)
		{
			$sql = "SELECT estatus.Estatus FROM estatus INNER JOIN personas ON 
			estatus.ID = personas.ID_estatus WHERE cedula = ?";
			$estatus = $this->db->prepare($sql);
			$estatus->bind_param("s", $cedulaPersona);
			$estatus->execute();
			$resultado = $estatus->get_result(); 
			$estatusPersona = $resultado->fetch_assoc();
			
			return $estatusPersona['Estatus'];
		}

		
		/*Este método sirve para obtener el segundo número de telefono de una persona, en caso de tenerlo de lo contrario
		el método retornará un valor NULL*/
		public function obtenerTelefono2Persona($cedulaPersona)
		{
			$numTelefPersona1 = $this->obtenerTelefonoPersona($cedulaPersona);
			$sql = "SELECT telefono.Num_telef FROM telefono_personas INNER JOIN telefono ON 
			telefono_personas.ID_telefono = telefono.ID WHERE telefono_personas.ID_persona = (SELECT ID FROM personas WHERE cedula=?) AND 
			telefono.Num_telef != ?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("ss", $cedulaPersona, $numTelefPersona1);
			$telefono->execute();
			$resultado = $telefono->get_result(); 
			$telefonoPersona = $resultado->fetch_assoc();
			
			if ($telefonoPersona == NULL) {
				return "La persona no tiene un segundo telefono";
			}else{

				return $telefonoPersona["Num_telef"];

			}
		}

		/*Este método sirve para cambiar el estatus de una persona al estatus deshabilitado*/
		public function deshabilitarPersona($idPersona)
		{
			$sql = "UPDATE personas SET ID_estatus = (SELECT ID FROM estatus WHERE Estatus = 'Deshabilitado') WHERE 
			personas.ID = ?";
			$persona = $this->db->prepare($sql);
			$persona->bind_param("s", $idPersona);
			$persona->execute();
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
			if ($this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			!strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$this->vincularTelefonoExistente($cedulaPersona, $telefonoPersona);
				
			}

			else if ($this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$telefonoActualPersona = $this->obtenerTelefono2Persona($cedulaPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoPersona, $telefonoActualPersona);

				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
				
			}

			else if (!$this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$telefonoActualPersona = $this->obtenerTelefono2Persona($cedulaPersona);
				$this->insertarTelefonoPersona($telefonoPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoPersona, $telefonoActualPersona);

				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
				
			}

			else if (!$this->obtenerExistenciaTelefonoPersona($telefonoPersona) && 
			!strcmp($this->obtenerTelefono2Persona($cedulaPersona), "La persona no tiene un segundo telefono")){

				$this->insertarTelefonoPersona($telefonoPersona);
				$this->vincularTelefonoExistente($cedulaPersona, $telefonoPersona);
				
				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
				
			}
			
		}

		public function TelefonoVinculadoOtraPersona($telefonoPersona, $cedulaPersona)
		{
			$sql = "SELECT personas.cedula FROM telefono_personas INNER JOIN personas ON 
			telefono_personas.ID_persona = personas.ID INNER JOIN telefono ON telefono_personas.ID_telefono = telefono.ID
			WHERE personas.cedula != ? AND telefono.Num_telef = ?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("ss", $cedulaPersona, $telefonoPersona);
			$telefono->execute();	
		}

		public function eliminarTelefonoPersona($telefonoPersona)
		{
			$sql = "DELETE FROM telefono WHERE Num_telef=?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $telefonoPersona);
			$telefono->execute();	
		}

		public function actualizarTelefonoPersona($cedulaPersona, $telefonoNuevoPersona)
		{
			if ($this->obtenerExistenciaTelefonoPersona($telefonoNuevoPersona)) {
					
				$telefonoActualPersona = $this->obtenerTelefonoPersona($cedulaPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoNuevoPersona, $telefonoActualPersona);

				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
			}

			else{
				$telefonoActualPersona = $this->obtenerTelefonoPersona($cedulaPersona);
				$this->insertarTelefonoPersona($telefonoNuevoPersona);
				$this->actualizarIdTelefono($cedulaPersona, $telefonoNuevoPersona, $telefonoActualPersona);

				if ($this->TelefonoVinculadoOtraPersona($telefonoActualPersona, $cedulaPersona) == NULL) {
					
					$this->eliminarTelefonoPersona($telefonoActualPersona);
				}
			}
		}	

	}  
?>