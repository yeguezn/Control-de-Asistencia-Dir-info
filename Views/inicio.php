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

        <form method="POST" action="?controller=Inicio&action=buscarPersona">
            <input type="text" name="cedula" class="input" placeholder="Introducir número de cédula">
            <input type="submit" value="Buscar" class="btn-Marcar">
        </form>

        <table>
            <tr>
                <th>Nombre y Apellido</th>
                <th>Cédula</th>
                <th>Cargo</th>
                <th>Telefono</th>
                <th>Telefono 2</th>
                <th>Estatus</th>
                <th>Deshabilitar persona</th>
                <th>Editar datos</th>
            </tr>

            <?php if(isset($resultado)): ?>
                <?php while($persona = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $persona['Nombre_apellido']; ?></td>
                        <td><?php echo $persona['cedula']; ?></td>
                        <td><?php echo $persona['Tipo_rol']; ?></td>
                        <td><?php echo $inicio->obtenerTelefonoPersona($persona['cedula']); ?></td>
                        <td><?php echo $inicio->obtenerTelefono2Persona($persona['cedula']); ?></td>
                        <td><?php echo $inicio->obtenerEstatusPersona($persona['cedula']); ?></td>
                        <td><a href="?controller=Inicio&action=eliminarPersona&cedula=<?php echo $persona['cedula'];?>" class="btn-Marcar">Eliminar</a></td>
                        <td><a href="?controller=Editar&action=editarPersonaFormulario&cedula=<?php echo $persona['cedula'];?>" class="btn-Marcar">Editar</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif;?>    
               
        </table>
      	
	</body>
</html>