<?php
require_once '../models/BingoModel.php'; // Asegúrate de tener este archivo

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'sorteado') {
    $bingo_id = $_POST['bingo_id'] ?? 0;
    $numero = $_POST['numero'] ?? 0;

    if ($bingo_id < 1 || $numero < 1 || $numero > 90) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $model = new BingoModel();
    $respuesta = $model->registrarNumeroJugado($bingo_id, $numero);
    echo json_encode($respuesta);
}
if ($action === 'ultimoSorteado') {
   $bingo_id = $_GET['id_bingo'] ?? 0;


    $model = new BingoModel();
    $carton = $model->getNumerosSorteados($bingo_id);
    
    if ($carton) {
        echo json_encode(['success' => true, 'carton' => $carton]);
    } else {
        echo json_encode(['success' => false, 'carton' => []]);
    }
}
if ($action === 'Bingoganado') {
   $bingo_id = $_GET['id_bingo'] ?? 0;


    $model = new BingoModel();
    $bingostart = $model->getBingoGnado($bingo_id);
    
    if ($bingostart) {
        echo json_encode(['success' => true, 'ganador' => $bingostart]);
    } else {
        echo json_encode(['success' => false, 'ganador' => []]);
    }
}
