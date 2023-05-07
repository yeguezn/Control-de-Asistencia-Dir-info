<!DOCTYPE html>
<html>
	
	<head>
		<meta charset="utf-8">
		<title>Generar Reportes PDF</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    	<link rel="stylesheet" href="assets/css/style.css">
	</head>
	
	<body>

		<nav class="nav-principal">
			<div class="nav-nombreDept">
				<h1>Dirección de Informática</h1>
			</div>
			<div class="nav-container">
				<ul class="nav-container__ul">
					<li><a href="?controller=Inicio&action=mostrarInicio">Inicio</a></li>
					<li><a href="?controller=Registrar&action=mostrarFormulario">Registrar Persona</a></li>
					<li><a href="?controller=Contrasenia&action=mostrarFormulario">Cambiar contraseña</a></li>
					<li><a href="?controller=Reporte&action=mostrarFormulario">Generar Reportes</a></li>
					<li><a href="?controller=Login&action=cerrarSesion">Salir</a></li>
				</ul>
			</div>
		</nav>

		<?php if(!empty($aviso)): ?>
     		<?php echo "<div class='AvisoError'>$aviso</div>";?>
    	<?php endif; ?>

		<div class="contenedor">
     
      		<h1>Generar Reportes PDF</h1>
    

      		<form action="?controller=Reporte&action=generarReporte" method="POST">
        
        		<input name="fechaInicio" type="date" placeholder="Ingrese fecha de inicio" class="input">
        		<input name="fechaFin" type="date" placeholder="Ingrese fecha de fin" class="input">
        		
        		<div>
        			<select name="cargo">
        				<option>Pasante</option>
        				<option>Roles administrativos</option>
        			</select>
        		</div>
        
        		<input type="submit" value="Generar Reporte" name="Marcar" class="btn-Marcar">
      
      		</form>
    	</div>

	</body>
</html>