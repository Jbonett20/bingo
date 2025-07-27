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
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h3 {
            font-weight: 600;
            color: #343a40;
        }

        #cartonWrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 50vh;
        }

        .alert {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        #diceContainer .dice {
            font-size: 2.5rem;
        }

        #resultadoText {
            font-size: 1.25rem;
            color: #333;
        }

        #cuentaRegresiva {
            font-size: 2rem;
            color: #0d6efd;
        }

        .card {
            border-radius: 1rem;
        }

        .btn-primary {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

    </style>
</head>

<body>
    <?php include '../src/nav.php'; ?>

    <main class="container mt-4">
        <h3 class="text-center mb-4">🎉 Bienvenido, <?= $_SESSION['user']['nombre']; ?> 🎉</h3>

        <div class="text-center mb-5">
            <h5 id="info">⏳ El bingo comienza en:</h5>
            <div id="cuentaRegresiva" class="fw-bold"></div>
            <button class="btn btn-primary btn-sm mt-3 d-none" id="iniciarJuego">Iniciar Juego</button>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-6 d-none" id="bingostart">
                <div class="alert alert-success text-center">
                    <h4 class="alert-heading">¡🎊 BINGO! 🎊</h4>
                    <p id="resultadoBingo"></p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center align-items-start">
            <div class="col-md-4 d-none" id="cardPrincipal">
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
            </div>

            <div class="col-md-6 mt-4 mt-md-0 d-none" id="cardResultados">
                <div class="alert alert-info shadow-sm" id="resultado">
                    <h4 class="alert-heading">🧾 Números Jugados</h4>
                    <p id="resultadoText" class="mb-0"></p>
                </div>
            </div>
        </div>

        <div id="cartonWrapper" class="mt-5">
            <div id="cartonGenerado" class="text-center"></div>
        </div>
    </main>

    <?php include '../src/script.php'; ?>
    <script type="module" src="../js/cartonBingo.js"></script>
</body>

</html>
