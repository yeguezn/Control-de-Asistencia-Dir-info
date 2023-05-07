<!DOCTYPE html>
<html>
	<head>
		<title>Control de Asistencia</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
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
     
      		<h1>Cambiar contraseña</h1>
    

      		<form action="?controller=Contrasenia&action=cambiarContrasenia" method="POST">
        
        		<input name="contrasenia" type="password" placeholder="Ingrese su nueva contraseña" class="input">
        		<input name="contraseniaConfirmada" type="password" placeholder="Confirme su contraseña" class="input">
        
        		<input type="submit" value="Cambiar" name="cambiarBtn" class="btn-Marcar">
      
      		</form>
    	</div>

	</body>
</html>