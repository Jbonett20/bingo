<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Bootstrap CSS v5.2.1 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/styles.css">

</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main>
    <div class="form-wrapper">
      <form id="registerForm" class="register-card">
        <h2>Registro de Usuario</h2>

        <div class="form-group">
          <label>Nombre</label>
          <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="form-group">
          <label>Apellido</label>
          <input type="text" class="form-control" name="apellido" required>
        </div>
        <div class="form-group">
          <label>Tipo de Identificación</label>
          <select class="form-control" name="tipo_identificacion_id" required>
            <option value="1">Cédula</option>
            <option value="2">Tarjeta de Identidad</option>
          </select>
        </div>
        <div class="form-group">
          <label>Identificación</label>
          <input type="text" class="form-control" name="identificacion" required>
        </div>
        <div class="form-group">
          <label>Correo</label>
          <input type="email" class="form-control" name="correo" required>
        </div>
        <div class="form-group">
          <label>Género</label>
          <select class="form-control" name="genero_id" required>
            <option value="1">Masculino</option>
            <option value="2">Femenino</option>
            <option value="3">Otro</option>
          </select>
        </div>
        <div class="form-group">
          <label>Usuario</label>
          <input type="text" class="form-control" name="usuario" required>
        </div>
        <div class="form-group">
          <label>Contraseña</label>
          <input type="password" class="form-control" name="clave" required>
        </div>
        <div class="form-group">
          <label>Rol</label>
          <select class="form-control" name="rol_id" required>
            <option value="1">Jugador</option>
            <!-- <option value="2">Administrador</option> -->
          </select>
        </div>

        <button type="submit" class="btn-submit">Registrarse</button>
      </form>
    </div>
  </main>
  <footer>

  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <?php include '../src/script.php'; ?>
  <script src="../js/register.js"></script>

</body>

</html>