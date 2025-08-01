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
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="wrapper">
        <?php include '../src/nav.php'; ?>

        <main class="container mt-4">
            <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user']['nombre']); ?></h3>

            <div class="row mb-3 align-items-center">
                <div class="col-auto">
                    <label for="bingoAll" class="col-form-label">Bingos:</label>
                </div>
                <div class="col">
                    <select id="bingoAll" class="form-select">
                        <option value="">Seleccione un bingo</option>
                        <!-- Opciones se insertan dinámicamente -->
                    </select>
                </div>
            </div>

            <button class="btn btn-primary mt-3" id="generarCarton">Obtener Cartón</button>

            <div id="cartonWrapper"></div>
        </main>

        <?php include '../src/footer.php'; ?>
    </div>

    <?php include '../src/script.php'; ?>
    <script src="../js/index.js"></script>
</body>
</html>
