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
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
           <?php include '../src/nav.php'; ?>
        <main>
    <div class="container mt-5">
        <h2>Bienvenido, <?= $_SESSION['user']['nombre']; ?></h2>

        <!-- Tabs -->
        <ul class="nav nav-tabs mt-4" id="bingoTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="gestionar-tab" data-bs-toggle="tab" data-bs-target="#gestionar" type="button" role="tab">Gestionar Bingo</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="jugar-tab" data-bs-toggle="tab" data-bs-target="#jugar" type="button" role="tab">Jugar Bingo</button>
            </li>
            <li class="nav-item" role="presentation">
    <button class="nav-link" id="jugadores-tab" data-bs-toggle="tab" data-bs-target="#jugadores" type="button" role="tab">Jugadores</button>
</li>

        </ul>

        <div class="tab-content mt-3" id="bingoTabContent">
            <!-- TAB 1: GESTIONAR BINGO -->
            <div class="tab-pane fade show active" id="gestionar" role="tabpanel">
               <form id="form-bingo" method="POST" class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Nombre del Bingo</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Valor</label>
        <input type="number" step="0.01" name="valor" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">link</label>
        <input type="text" step="0.01" name="link" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Fecha de Juego</label>
        <input type="date" name="fecha_juego" class="form-control" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Crear Bingo y Sorteo</button>
    </div>
</form>
            </div>
            <!-- Fragmento modificado para el tab JUGAR BINGO -->
<div class="tab-pane fade" id="jugar" role="tabpanel">
    <div class="row mt-3">
        <div class="col-md-4">
            <label for="selectBingoJuego" class="form-label">Bingo para jugar</label>
            <select class="form-select" id="selectBingoJuego">
                <option value="">Seleccione un bingo</option>
            </select>
        </div>
    </div>
</div>

            <!-- TAB 3: JUGAR BINGO -->
           <div class="tab-pane fade" id="jugadores" role="tabpanel">
    <div class="row mt-3">
        <div class="col-md-4">
            <label for="selectBingo" class="form-label">Seleccionar Bingo</label>
            <select class="form-select" id="selectBingo">
                 <option value="all" selected>Seleccione un bingo</option>
            </select>
           
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped" id="tablaJugadores">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Identificación</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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
        <script src="../js/bingo.js"></script>
    </body>
</html>
