<?php
	
	require_once "config/database.php";

	class ModeloBase{

		public $db;
        
        //Conexión a la base de datos
        public function __construct(){
            
            $this->db = database::conectar();

        }

        /*Este método sirve para para saber si una persona se encuentra registrada en la base de datos,
		en el caso de estar registrada el método retornará la cédula de lo contrario retornará un valor nulo*/
		public function obtenerCedulaPersona($cedulaPersona){

			$sql = "SELECT cedula FROM personas WHERE cedula=?";
			$existencia = $this->db->prepare($sql);
			$existencia->bind_param("s", $cedulaPersona);
			$existencia->execute();
			$resultado = $existencia->get_result(); 
			$existenciaPersona = $resultado->fetch_assoc();

			return $existenciaPersona;

		}

		/*Este método sirve para obtener el número de telefono de una persona*/
		public function obtenerTelefonoPersona($cedulaPersona)
		{
			$sql = "SELECT telefono.Num_telef FROM telefono_personas INNER JOIN telefono ON 
			telefono_personas.ID_telefono = telefono.ID WHERE telefono_personas.ID_persona = (SELECT ID FROM personas WHERE cedula=?)";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $cedulaPersona);
			$telefono->execute();
			$resultado = $telefono->get_result(); 
			$telefonoPersona = $resultado->fetch_assoc();
			
			return $telefonoPersona['Num_telef'];
		}

		/*Este método sirve para vincular nu número de telefono ya registrado en la base de datos con el portador de un número 
		de cédula*/
		public function vincularTelefonoExistentePersona($telefono, $cedulaPersona)
		{
			$sql = "INSERT INTO telefono_personas(ID_telefono, ID_persona)
			VALUES ((SELECT ID FROM telefono WHERE Num_telef = ?), (SELECT ID FROM personas WHERE cedula = ?))";
			$numTelef = $this->db->prepare($sql);
			$numTelef->bind_param("ss", $telefono, $cedulaPersona);
			$numTelef->execute();
		}


		/*Este método sirve para saber si un número de telefono dado se encuentra registrado en la base de datos*/
		public function obtenerExistenciaTelefonoPersona($telefonoPersona)
		{

			$sql = "SELECT Num_telef FROM telefono WHERE Num_telef=?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $telefonoPersona);
			$telefono->execute();
			$resultado = $telefono->get_result(); 
			$existenciaTelefono = $resultado->fetch_assoc();
			
			return $existenciaTelefono;
		}

		/*En el caso de que se quiera registrar un segundo número de telefono vinculado a una persona
		que en el momento de su registro no lo haya agregado*/
		public function insertarTelefonoPersona($telefono)
		{
			$sql = "INSERT INTO telefono(Num_telef) VALUES (?)";
			$numTelef = $this->db->prepare($sql);
			$numTelef->bind_param("s", $telefono);
			$numTelef->execute();
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

		public function eliminarTelefonoPersona($telefonoPersona)
		{
			$sql = "DELETE FROM telefono WHERE Num_telef=?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $telefonoPersona);
			$telefono->execute();
			$this->eliminarTelefonoVinculadoPersona($cedulaPersona);	
		}

		public function eliminarTelefonoVinculadoPersona($cedulaPersona)
		{
			$sql = "DELETE FROM telefono_personas WHERE ID_persona=(SELECT ID FROM personas WHERE cedula=?)";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $cedulaPersona);
			$telefono->execute();	
		}


		public function eliminarPersona($cedulaPersona)
		{
			$this->eliminarTelefonoVinculadoPersona($cedulaPersona);
			$sql = "DELETE FROM personas WHERE cedula=?";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $cedulaPersona);
			$telefono->execute();	
		}

	
		public function eliminarAsistenciasPersona($cedulaPersona)
		{
			$sql = "DELETE FROM asistencia WHERE ID_persona=(SELECT ID FROM personas WHERE cedula=?)";
			$telefono = $this->db->prepare($sql);
			$telefono->bind_param("s", $cedulaPersona);
			$telefono->execute();	
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
		
	}
?>