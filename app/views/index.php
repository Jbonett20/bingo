<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<!doctype html>
<html lang="es">

<head>
    <title>Inicio - Bingo</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        #cartonWrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 50vh;
        }
    </style>
</head>

<body>
    <?php include '../src/nav.php'; ?>

    <main class="container mt-4">
        <h3>Bienvenido, <?= $_SESSION['user']['nombre']; ?></h3>
        <div class="row">
            <div class="col-1">
                <label  for="bingoAll">Bingos:</label>
            </div>
            <div class="col-10">   <select id="bingoAll" class="form-select">
  <option value="">Seleccione un bingo</option>
</select></div>
         
     
        </div>
      

        <button class="btn btn-primary mt-3" id="generarCarton">Obtener Cartón</button>

        <div id="cartonWrapper">
            <div id="cartonGenerado" class="mt-4"></div>
        </div>
    </main>

    <?php include '../src/script.php'; ?>
    <script src="../js/index.js"></script>
</body>

</html>