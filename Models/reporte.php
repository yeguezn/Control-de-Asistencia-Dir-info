<?php

	require_once 'modelobase.php';
	
	class Reporte extends ModeloBase{


		/*Este método sirve para obtener UNICAMENTE a los trabajadores registrados en el sistema*/
		public function obtenerTrabajadoresAsistentes()
		{
			$sql = "SELECT Nombre_apellido, cedula FROM personas WHERE 
			ID_rol != (SELECT ID FROM rol WHERE Tipo_Rol = 'pasante')";
			$personal = $this->db->prepare($sql);
			$personal->execute();
			$resultado = $personal->get_result(); 

			return $resultado;
		}

		/*Este método sirve para obtener UNICAMENTE a los pasantes que hicieron su cumplimiento de horas entre una 
		fecha de inicio y otra fecha de fin*/
		public function obtenerPasantesAsistentes($fechaInicio, $fechaFin)
		{
			$sql = "SELECT DISTINCT personas.Nombre_apellido, personas.cedula FROM personas INNER JOIN asistencia ON 
			personas.ID = asistencia.ID_persona WHERE Fecha BETWEEN ? AND ? AND 
			personas.ID_rol = (SELECT ID FROM rol WHERE Tipo_Rol='pasante')";
			$pasantes = $this->db->prepare($sql);
			$pasantes->bind_param("ss", $fechaInicio, $fechaFin);
			$pasantes->execute();
			$resultado = $pasantes->get_result(); 

			return $resultado;
		}

		/*Este método sirve para darle a una fecha el formato de fecha año-mes-día*/
		public function darFormatoFecha($fecha)
		{
			
			$fecha = strtotime($fecha);
			$fecha = date("Y-m-d", $fecha);
			return $fecha;
		}

		/*Este método sirve para obtener el estatus de un trabajador o pasante (Activo, de reposo, de permiso, sin justificar)*/
		public function obtenerEstatusPersona($cedula)
		{
			$sql = "SELECT estatus.Estatus FROM estatus WHERE estatus.ID = (SELECT ID_estatus FROM personas WHERE cedula=?)";
			$estatus = $this->db->prepare($sql);
			$estatus->bind_param("s", $cedula);
			$estatus->execute();
			$resultado = $estatus->get_result(); 
			$estatusPersona = $resultado->fetch_assoc();

			return $estatusPersona["Estatus"];	
		}
		/*Este método sirve para obtener el número de veces que asistió un trabajador entre una fecha de inicio y una fecha de fin*/
		public function obtenerNumeroAsistencia($fechaInicio, $fechaFin, $cedula)
		{
			$sql = "SELECT count(ID) AS 'Conteo' FROM asistencia WHERE Fecha BETWEEN ? AND ? AND 
			ID_persona = (SELECT ID FROM personas WHERE cedula = ? )";
			$asistencias = $this->db->prepare($sql);
			$asistencias->bind_param("sss", $fechaInicio, $fechaFin, $cedula);
			$asistencias->execute();
			$resultado = $asistencias->get_result();
			$numeroAsistencias = $resultado->fetch_assoc();
			
			return $numeroAsistencias['Conteo'];	
		}
		
		/*Este método sirve para saber el número de veces que no asistió un trabajador entre una fecha de inicio y una fecha de fin*/
		public function calcularInasistencias($fechaInicio, $fechaFin, $cedula)
		{
			$numeroAsistenciasMes = 20;
			$numeroAsisteciasPersona = intval($this->obtenerNumeroAsistencia($fechaInicio, $fechaFin, $cedula));
			return $numeroAsistenciasMes - $numeroAsisteciasPersona;

		}

		/*Este método suma las asistencias e inasistencias de un trabajador*/
		public function calcularTotal($fechaInicio, $fechaFin, $cedula)
		{
			$numeroAsistenciasPersona = intval($this->obtenerNumeroAsistencia($fechaInicio, $fechaFin, $cedula));
				
			$numeroInasistenciasPersona = $this->calcularInasistencias($fechaInicio, $fechaFin, $cedula);

			return $numeroAsistenciasPersona + $numeroInasistenciasPersona;
		}
		
		/*Este método genera el reporte de asistencias e inasistencias de un trabajador*/
		public function generarReporteTrabajador($fechaInicio, $fechaFin)
		{
			
			$personal = $this->obtenerTrabajadoresAsistentes();
			
			require 'ReporteTrabajador.php';
			$pdf = new ReporteTrabajador($fechaInicio, $fechaFin, 'L','mm','A4');
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			
			while ($registro = $personal->fetch_assoc()) {
				$pdf->Cell(46, 5, utf8_decode($registro['Nombre_apellido']), 1, 0, 'C', 0);
            	$pdf->Cell(46, 5, $registro['cedula'], 1, 0, 'C', 0);
            	$pdf->Cell(46, 5, $this->obtenerNumeroAsistencia($fechaInicio, $fechaFin, $registro['cedula']), 1, 0, 'C', 0);
            	$pdf->Cell(46, 5, $this->calcularInasistencias($fechaInicio, $fechaFin, $registro['cedula']), 1, 0, 'C', 0);
            	$pdf->Cell(46, 5, $this->calcularTotal($fechaInicio, $fechaFin, $registro['cedula']), 1, 0, 'C', 0);
            	$pdf->Cell(46, 5, $this->obtenerEstatusPersona($registro['cedula']), 1, 1, 'C', 0);
			}
			
			$pdf->Output();
		}

		/*Este método calcula cuántas horas desempeñó un pasante durante sus pasantías*/
		public function calcularHorasPasante($cedula, $fechaInicio, $fechaFin)
		{
			$sql = "SELECT SUM(EXTRACT(HOUR FROM Cantidad_horas)) AS 'Cantidad_horas' FROM asistencia WHERE 
			ID_persona = (SELECT ID FROM personas WHERE cedula = ?) AND Fecha BETWEEN ? AND ?";
			$cantidadHoras = $this->db->prepare($sql);
			$cantidadHoras->bind_param("sss", $cedula, $fechaInicio, $fechaFin);
			$cantidadHoras->execute();
			$resultado = $cantidadHoras->get_result(); 
			$cantidadHorasPersona = $resultado->fetch_assoc();

			return $cantidadHorasPersona["Cantidad_horas"];	
		}

		/*Este método genera el reporte del número de horas que desempeñó cada uno de los pasantes*/
		public function generarReportePasante($fechaInicio, $fechaFin)
		{
			
			$personal = $this->obtenerPasantesAsistentes($fechaInicio, $fechaFin);
			
			require 'ReportePasante.php';
			$pdf = new ReportePasante($fechaInicio, $fechaFin, 'L','mm','A4');
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			
			while ($registro = $personal->fetch_assoc()) {
				$pdf->Cell(92, 5, utf8_decode($registro['Nombre_apellido']), 1, 0, 'C', 0);
            	$pdf->Cell(92, 5, $registro['cedula'], 1, 0, 'C', 0);
            	$pdf->Cell(92, 5, $this->calcularHorasPasante($registro['cedula'], $fechaInicio, $fechaFin), 1, 1, 'C', 0);
			}
			
			$pdf->Output();
		}

		/*Este método llama a generar un reporte de trabajadores o pasantes comprendido entre una fecha de inicio y una fecha de  fin*/
		public function generarPDF($cargo, $fechaInicio, $fechaFin)
		{
		
			if (!strcmp($cargo, "Pasante")) {

				$this->generarReportePasante($fechaInicio, $fechaFin);
				
			}else{

				$this->generarReporteTrabajador($fechaInicio, $fechaFin);
			}	
		}
	}
?>