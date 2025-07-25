<?php
require_once '../models/BingoModel.php';
header('Content-Type: application/json');

$model = new BingoModel();

// Leer datos si llegan en JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listar') {
    // Acción: Listar bingos existentes
    $bingos = $model->listarBingos();
    echo json_encode($bingos);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Acción: Crear nuevo bingo y sorteo
    $nombre = $data['nombre'] ?? '';
    $valor = $data['valor'] ?? '';
    $link = $data['link'] ?? '';
    $fecha_juego = $data['fecha_juego'] ?? '';

    if ($model->crearBingoYJuego($nombre, $valor,$link, $fecha_juego)) {
        echo json_encode(['status' => 'success', 'message' => 'Bingo y sorteo creados correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al crear bingo/sorteo puede que ya exista un bingo con el mismo nombre']);
    }
    exit;
}

// Si no coincide ninguna acción
http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
