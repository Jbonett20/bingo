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
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <?php if (isset($_GET['registered'])): ?>
  <div class="alert alert-success">Registro exitoso. Ahora puedes iniciar sesión.</div>
<?php endif; ?>

        <div class="container mt-5">
            <h2>Iniciar Sesión</h2>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="clave" required>
                </div>
                <button type="submit" class="btn btn-primary">Ingresar</button>
                <div id="errorMsg" class="text-danger mt-2"></div>
            </form>
            <div class="mt-3">
                <a href="register.php">¿No tienes cuenta? Regístrate aquí</a>
        </div>
    </main>
    <footer>

    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <?php include '../src/script.php'; ?>
    <script src="../js/login.js"></script>

</body>

</html>