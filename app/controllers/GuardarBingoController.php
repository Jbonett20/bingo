<?php
require_once '../models/BingoStartModel.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user']['id'];
$carton_id = $data['carton_id'] ?? null;
$fecha = $data['sorteo_fecha'] ?? null;

if (!$carton_id || !$fecha) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$model = new BingoStartModel();
$result = $model->registrarBingo($user_id, $carton_id, $fecha);

echo json_encode($result);

