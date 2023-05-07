<?php

	require_once 'modelobase.php';

	class Asistencia extends ModeloBase{

		/*Este método sirve para obtener el rol que desempeña una persona en el departamento de Dirección de Informática 
		(pasante, obrero o administrativo)*/
		
		public function obtenerRolPersona($cedulaPersona){

			$sql = "SELECT rol.Tipo_Rol FROM rol INNER JOIN personas ON personas.ID_rol = rol.ID WHERE cedula=?";
			$rol = $this->db->prepare($sql);
			$rol->bind_param("s", $cedulaPersona);
			$rol->execute();
			$resultado = $rol->get_result(); 
			$rolPersona = $resultado->fetch_assoc();
			return $rolPersona["Tipo_Rol"];

		}

		/*Este método sirve para para saber si una persona (pasante o trabajador) marcó su hora de entrada 
		en la fecha actual*/
		public function obtenerEntradaPersona($cedulaPersona){


			$sql = "SELECT Hora_entrada FROM asistencia WHERE ID_persona=(SELECT ID FROM personas WHERE cedula=?)
			AND Fecha=CURDATE()";
			$asistencia = $this->db->prepare($sql);
			$asistencia->bind_param("s", $cedulaPersona);
			$asistencia->execute();
			$resultado = $asistencia->get_result(); 
			$asistenciaPersona = $resultado->fetch_assoc();

			return $asistenciaPersona;

		}

		/*Este método sirve para para saber si una persona (pasante o trabajador) marcó su hora de salida
		en la fecha actual*/
		public function obtenerSalidaPersona($cedulaPersona){

			$sql = "SELECT Hora_salida FROM asistencia WHERE ID_persona=(SELECT ID FROM personas WHERE cedula=?)
			AND Fecha=CURDATE()";
			$asistencia = $this->db->prepare($sql);
			$asistencia->bind_param("s", $cedulaPersona);
			$asistencia->execute();
			$resultado = $asistencia->get_result(); 
			$asistenciaPersona = $resultado->fetch_assoc();

			return $asistenciaPersona["Hora_salida"];

		}
		/*Este método sirve para para calcular la cantidad de horas que trabajó un pasante y registrar dicho cálculo en la base de datos
		vínculado a los datos del pasante que las desempeñó en la fecha actual*/
		public function calcularCantidadHoras($cedulaPersona){
			$HoraEntrada = $this->obtenerEntradaPersona($cedulaPersona)["Hora_entrada"];
			$HoraSalida = $this->obtenerSalidaPersona($cedulaPersona);

			$sql = "UPDATE asistencia SET Cantidad_horas = TIMEDIFF(?, ?) WHERE 
			ID_persona = (SELECT ID FROM personas WHERE cedula = ?) AND Fecha = CURDATE()";
			$asistencia = $this->db->prepare($sql);
			$asistencia->bind_param("sss", $HoraSalida, $HoraEntrada, $cedulaPersona);
			$asistencia->execute();
			
		}

		/*Este método sirve para registrar la fecha actual, hora de entrada, hora de salida y cantidad de horas
		en caso de los pasantes y en caso de los trabajadores fecha, hora de entrada y hora de salida*/
		public function marcarEntrada($cedulaPersona){
			
			$sql = "INSERT INTO asistencia(Fecha, ID_persona, Hora_entrada, Hora_salida, Cantidad_horas) VALUES(CURDATE(), 
			(SELECT ID FROM personas WHERE cedula=?), CURTIME(), NULL, NULL)";
			
			$asistencia = $this->db->prepare($sql);
			$asistencia->bind_param("s", $cedulaPersona);
			$asistencia->execute();
			
		}

		/*Este método sirve para actualizar la fecha de salida de un trabajador o pasante*/
		public function marcarSalida($cedulaPersona){
			
			$sql = "UPDATE asistencia SET Hora_salida = CURTIME() WHERE 
			ID_persona = (SELECT ID FROM personas WHERE cedula = ?) AND Fecha = CURDATE()";
			$asistencia = $this->db->prepare($sql);
			$asistencia->bind_param("s", $cedulaPersona);
			$asistencia->execute();

			$this->calcularCantidadHoras($cedulaPersona);
		}

		/*Este método sirve para obtener el nombre y apellido de un trabajador o pasante*/
		public function obtenerNombreUsuario($cedulaPersona){

			$sql = "SELECT Nombre_apellido FROM personas WHERE cedula = ?";
			$nombre = $this->db->prepare($sql);
			$nombre->bind_param("s", $cedulaPersona);
			$nombre->execute();
			$resultado = $nombre->get_result(); 
			$nombrePersona = $resultado->fetch_assoc();

			return $nombrePersona["Nombre_apellido"];

		}

		/*Este método sirve para conocer si un trabajador o pasante asistió en la fecha actual*/
		public function obtenerFechaAsistencia($cedulaPersona){

			$sql = "SELECT Fecha FROM asistencia WHERE ID_persona=(SELECT ID FROM personas WHERE cedula=?)
			AND Fecha=CURDATE()";
			$fechaAsistencia = $this->db->prepare($sql);
			$fechaAsistencia->bind_param("s", $cedulaPersona);
			$fechaAsistencia->execute();
			$resultado = $fechaAsistencia->get_result(); 
			$fechaAsistenciaPersona = $resultado->fetch_assoc();

			return $fechaAsistenciaPersona["Fecha"];

		}

	}


?>