<?php
session_start();
header('Content-Type: application/json');
require_once '../models/CartonModel.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no iniciada']);
    exit;
}

// Decodificar datos JSON de entrada
$input = json_decode(file_get_contents("php://input"), true);
$bingo_id = $input['bingo_id'] ?? null;

if (!$bingo_id) {
    echo json_encode(['success' => false, 'message' => 'ID de bingo no proporcionado']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$model = new CartonModel();
$result = $model->generarCarton($user_id, $bingo_id);

echo json_encode($result);

