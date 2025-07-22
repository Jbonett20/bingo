<?php
header('Content-Type: application/json');
require_once '../models/RegisterModel.php';

$input = json_decode(file_get_contents("php://input"), true);

$model = new RegisterModel();
$success = $model->registrarUsuario($input);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar']);
}
