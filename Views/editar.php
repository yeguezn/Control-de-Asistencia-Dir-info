<!DOCTYPE html>
<html>
	<head>
		<title>Control de Asistencia</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	</head>
	<body>

		<nav class="nav-login">
            <h1><a href="?controller=Inicio&action=mostrarInicio">Volver al Inicio</a></h1>
        </nav>
		
        <?php if(!empty($aviso)): ?>
      		<?php echo "<div class='$class'>$aviso</div>";?>
    	<?php endif; ?>
     
      	<h1>Editar datos de la persona</h1>

      	<form action="?controller=Editar&action=editarPersona&cedula=<?php echo $datosPersona['cedula'];?>" method="POST">
        
            <label for="cedulaId">Número de cedula</label>
        	<input name="cedula" type="text" class="input" id="cedulaId" value="<?php echo $datosPersona['cedula'];?>">
            <label for="nombreId">Nombre y Apellido</label>
        	<input name="nombre" type="text" class="input" id="nombreId" value="<?php echo $datosPersona['Nombre_apellido'];?>">
            <label for="telefonoId">Número de telefono</label>
        	<input name="telefono" type="text" class="input" id="telefonoId" value="<?php echo $editar->obtenerTelefonoPersona(
            $datosPersona['cedula']);?>">
            <label for="telefono2Id">Segundo número de telefono (opcional)</label>
        	<input name="telefono2" type="text" class="input" id="telefono2Id" value="<?php echo $editar->obtenerTelefono2Persona(
            $datosPersona['cedula']);?>">
            <label for="domicilioId">Domicilio</label>
        	 
            <textarea name="domicilio" type="text" class="input" id="domicilioId"><?php echo $datosPersona['Dirección'];?></textarea>
            
            <div class="input-select">
                <label for="cargoId">Cargo de la persona</label>
        		<select name="cargo" id="cargoId">
        			<option><?php echo $datosPersona['Tipo_rol'];?></option>
        			
                    <?php $roles = $editar->obtenerOtrosRoles($datosPersona['Tipo_rol']);?>

                    <?php while($rol = $roles->fetch_assoc()):?>
                        <option>
                            
                            <?php echo $rol['Tipo_rol'];?>
                                
                        </option>
                    
                    <?php endwhile;?>      
        		</select>
        	</div>

        	<div class="input-select">
                <label for="estatusId">Estatus de la persona</label>
        		<select name="estatus" id="estatusId">
                    
                    <option><?php echo $estatusPersona;?></option>

                    <?php $otrosEstatus = $editar->obtenerOtrosEstatus($estatusPersona);?>

                     <?php while($estatus = $otrosEstatus->fetch_assoc()):?>
                        <option>
                            
                            <?php echo $estatus['Estatus'];?>
                                
                        </option>
                    
                    <?php endwhile;?>

        		</select>
        	</div>
        
        	<input type="submit" value="Guardar" name="Marcar" class="btn-Marcar">
      
      	</form>

	</body>
</html>