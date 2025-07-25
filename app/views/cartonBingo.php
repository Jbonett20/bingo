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
          
       
           <div class="row mt-4 justify-content-center">
            <div class="col-md-6 d-none" id="bingostart">
                    <div class="alert alert-info" >
                        <h4 class="alert-heading">¡Bingo!</h4>
                        <p id="resultadoBingo"></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info" id="resultado">
                        <h4 class="alert-heading">Numeros jugados</h4>
                        <p id="resultadoText"></p>
                    </div>
                </div>
            </div>

        <div id="cartonWrapper">
            <div id="cartonGenerado" class="mt-4"></div>
        </div>
    </main>

    <?php include '../src/script.php'; ?>
    <script src="../js/cartonBingo.js"></script>
</body>

</html>