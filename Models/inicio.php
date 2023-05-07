<?php
	require 'modelobase.php';
	
	class Inicio extends modeloBase{


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
		

		/*Este método sirve para eliminar a una persona de la base de datos*/
		public function eliminarDatosPersona($cedulaPersona)
		{
			$telefono1Persona = $this->obtenerTelefonoPersona($cedulaPersona);
			$telefono2Persona = $this->obtenerTelefono2Persona($cedulaPersona);

			if ($this->TelefonoVinculadoOtraPersona($telefono1Persona, $cedulaPersona) == NULL) {
					
				$this->eliminarTelefonoPersona($telefono1Persona);
			}

			if (strcmp($telefono2Persona, "La persona no tiene un segundo telefono") && 
			$this->TelefonoVinculadoOtraPersona($telefono2Persona, $cedulaPersona) == NULL) {
					
				$this->eliminarTelefonoPersona($telefono2Persona);
			}

			$this->eliminarAsistenciasPersona($cedulaPersona);
			$this->eliminarPersona($cedulaPersona);
		}
	}
		
?>