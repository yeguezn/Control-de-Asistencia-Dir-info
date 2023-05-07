<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="utf-8">
    <title>Formulario de Asistencia</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  
  <body>

    <div class="contenedor">
     
      <h1>Formulario de Asistencia</h1>
    

      <form action="?controller=Asistencia&action=marcarAsistencia" method="POST">
        
        <input name="cedula" type="text" placeholder="Ingrese su número de cédula" class="input">
        
        <input type="submit" value="Marcar" name="Marcar" class="btn-Marcar">
      
      </form>

      <a href="?controller=Login&action=mostrarFormulario">Modo Administrador</a>
    
    </div>
  
  </body>
</html>