<?php
require_once '../models/BingoAllModel.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$model = new getBingoModel();
$data = $model->getBingoAll($_SESSION['user']['id']);
echo json_encode($data);

