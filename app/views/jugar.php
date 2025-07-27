<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Administrador</title>
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
     <link rel="stylesheet" href="../css/stylesjugar.css">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <?php include '../src/nav.php'; ?>
    <main>
        <div class="container mt-5">
            <h2>Bienvenido, <?= $_SESSION['user']['nombre']; ?></h2>
   <div class="text-center my-4">
            <h5>⏳ El bingo comienza en:</h5>
            <div id="cuentaRegresiva" class="fs-4 fw-bold text-primary"></div>
        </div>
                <audio class="music">
                </audio>
                  <div class="row mt-4 justify-content-center">
                <div class="col-md-6 d-none" id="bingostart">
                    <div class="alert alert-info" >
                        <h4 class="alert-heading">¡Bingo!</h4>
                        <p id="resultadoBingo"></p>
                    </div>
                </div>
            </div>

            <div class="row mt-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title">Lanzar Dados</h5>
                            <div class="d-flex justify-content-center gap-3 fs-1" id="diceContainer">
                                <span class="dice">🎲</span>
                                <span class="dice">🎲</span>
                                <span class="dice">🎲</span>
                            </div>
                            <button id="lanzarBtn" class="btn btn-success mt-3">Lanzar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-info" id="resultado">
                        <h4 class="alert-heading">Numeros jugados</h4>
                        <p id="resultadoText"></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <?php include '../src/script.php'; ?>
    <script type="module" src="../js/jugarBingo.js" ></script>
</body>

</html>