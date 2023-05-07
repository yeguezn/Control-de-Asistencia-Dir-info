<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <nav class="nav-login">
      <h1>Dirección de Informática</h1>
    </nav>

    <?php if(!empty($aviso)): ?>
      <?php echo "<div class='AvisoError'>$aviso</div>";?>
    <?php endif; ?>
    
    <div class="contenedor">
      
      <h2>Iniciar sesión</h2>
      
      <form action="?controller=Login&action=inicioSesion" method="POST">
          
        <input class="input" name="username" type="text" placeholder="Nombre de Usuario">
          
        <input class="input" name="password" type="password" placeholder="Contraseña">
          
        <input type="submit" value="Entrar" class="btn-Marcar" name="Entrar">        
      </form>

      <a href="?controller=Asistencia&action=mostrarFormulario">Modo Usuario</a>      
    </div>

  </body>
</html>