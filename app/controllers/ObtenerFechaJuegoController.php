<?php
require_once __DIR__ . '/../models/SorteoModel.php';

header('Content-Type: application/json');

if (!isset($_GET['id_bingo'])) {
    echo json_encode(['success' => false, 'message' => 'ID de bingo no proporcionado']);
    exit;
}

$id_bingo = intval($_GET['id_bingo']);

try {
    $modelo = new SorteoModel();
    $datos = $modelo->obtenerFechaJuego($id_bingo);

    if ($datos) {
        echo json_encode(['success' => true, 'fecha_juego' => $datos['fecha_juego']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sorteo no encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
