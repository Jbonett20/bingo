<?php
require_once '../models/JugadorModel.php';

header('Content-Type: application/json');

$model = new JugadoresModel();

$action = $_GET['action'] ?? '';
$bingo_id = $_GET['bingo_id'] ?? $_POST['bingo_id'] ?? null;

if ($action === 'listar' && $bingo_id) {
    echo json_encode($model->obtenerJugadoresPorBingo($bingo_id));
    exit;
}

if ($action === 'pagar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? null;
    if ($bingo_id && $usuario_id) {
        $resultado = $model->marcarPago($bingo_id, $usuario_id);
        echo json_encode(['success' => $resultado]);
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
    exit;
}

echo json_encode(['error' => 'Acción inválida']);

