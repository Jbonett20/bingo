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
    <link rel="stylesheet" href="../css/estilosjugarcarton.css">
   
</head>

<body>
    <?php include '../src/nav.php'; ?>

    <main class="container mt-4">
        <h3 class="text-center mb-4">🎉 Bienvenido, <?= $_SESSION['user']['nombre']; ?> 🎉</h3>

        <div class="text-center mb-2">
            <h5 id="info">⏳ El bingo comienza en:</h5>
            <div id="cuentaRegresiva" class="fw-bold"></div>
            <button class="btn btn-primary btn-sm mt-3 d-none" id="iniciarJuego">Iniciar Juego</button>
        </div>

        <div class="row justify-content-center align-items-start">
            <div class="bingo-overlay d-none" id="bingostart">
            <div class="bingo-message text-center">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Cerrar" onclick="cerrarBingoOverlay()"></button>
                <h4 class="alert-heading">¡🎊 BINGO! 🎊</h4>
                <p id="resultadoBingo" class="mt-3"></p>
            </div>
        </div>
            <div class="col-md-6 d-none" id="cardPrincipal">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">🎲 Lanzamiento de Dados</h5>
                        <div class="d-flex justify-content-center gap-4 mt-3" id="diceContainer">
                            <span class="dice">🎲</span>
                            <span class="dice">🎲</span>
                            <span class="dice">🎲</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 mt-3 mt-md-0 d-none" id="cardResultados">
                    <div class="alert alert-info shadow-sm" id="resultado">
                        <h4 class="alert-heading">🧾 Números Jugados</h4>
                        <p id="resultadoText" class=""></p>
                    </div>
                </div>
            </div>


        </div>

        <div id="cartonWrapper" class="mt-2">
            <div id="cartonGenerado" class="text-center"></div>
        </div>
    </main>
 <!-- place footer here -->
             <?php include '../src/footer.php'; ?>
    <?php include '../src/script.php'; ?>
    <script type="module" src="../js/cartonBingo.js"></script>
</body>

</html>