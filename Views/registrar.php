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
      		<?php echo "<div class='$class'>$aviso</div>";?>
    	<?php endif; ?>
     
      	<h1>Formulario de Registrar Persona</h1>

      	<form action="?controller=Registrar&action=registrarPersona" method="POST">
        
        	<label for="cedulaId">Número de cedula</label>
            
            <input name="cedula" type="text" class="input" id="cedulaId">
            
            <label for="nombreId">Nombre y Apellido</label>
            
            <input name="nombre" type="text" class="input" id="nombreId">
            
            <label for="telefonoId">Número de telefono</label>
            
            <input name="telefono" type="text" class="input" id="telefonoId">
            
            <label for="telefono2Id">Segundo número de telefono (opcional)</label>
            
            <input name="telefono2" type="text" class="input" id="telefono2Id">
            
            <label for="domicilioId">Domicilio</label>
            
            <textarea name="domicilio" type="text" class="input" id="domicilioId"></textarea>

        	<div class="input-select">
                <label for="cargoId">Cargo de la persona</label>
        		<select name="cargo">
        			<option>administrativo</option>
        			<option>obrero</option>
        			<option>pasante</option>
        		</select>
        	</div>

        	<div class="input-select">
                <label for="estatusId">Estatus de la persona</label>
        		<select name="estatus">
        			<option>Activo</option>
        			<option>Deshabilitado</option>
        			<option>De reposo</option>
                    <option>De permiso</option>
                    <option>Sin Justificar</option>
        		</select>
        	</div>
        
        	<input type="submit" value="Guardar" name="Marcar" class="btn-Marcar">
      
      	</form>

	</body>
</html>