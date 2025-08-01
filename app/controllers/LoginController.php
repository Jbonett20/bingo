<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../config/db.php';
require_once '../models/LoginModel.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

$usuario = $input['usuario'] ?? '';
$clave = $input['clave'] ?? '';

$model = new LoginModel();
$user = $model->verificarUsuario($usuario);

if ($user && password_verify($clave, $user['clave'])) {
    $_SESSION['user'] = [
        'id' => $user['id_user'],
        'nombre' => $user['nombre'],
        'rol' => $user['rol_id']
    ];
    echo json_encode(['success' => true,'rol_id' => $user['rol_id']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
}
