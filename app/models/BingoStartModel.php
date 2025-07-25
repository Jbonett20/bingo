<?php
// app/models/BingoStartModel.php

require_once '../config/db.php';

class BingoStartModel
{
    private $pdo;

    public function __construct()
    {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

public function registrarBingo($userId, $id_bingo, $cartonId, $sorteoFecha)
{
    try {
        // 1. Obtener los números del cartón
        $stmt = $this->pdo->prepare("SELECT numero1, numero2, numero3, numero4, numero5, bingo_id FROM cartones WHERE id_carton = :carton_id");
        $stmt->execute([
            ':carton_id' => $cartonId
        ]);

        $carton = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$carton) {
            return ["success" => false, "message" => "Cartón no encontrado para el usuario."];
        }

        $numerosCarton = [
            $carton['numero1'],
            $carton['numero2'],
            $carton['numero3'],
            $carton['numero4'],
            $carton['numero5']
        ];

        // 2. Verificar cuántos de esos números están en los números sorteados del mismo bingo
        $placeholders = implode(',', array_fill(0, count($numerosCarton), '?'));
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total
            FROM bingo_numeros_sorteados
            WHERE id_bingo = ? AND numero_sorteado IN ($placeholders)
        ");

        $stmt->execute(array_merge([$carton['bingo_id']], $numerosCarton));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Validar si los 5 números están presentes
        if ((int)$result['total'] !== 5) {
            return ["success" => false, "message" => "Bingo incorrecto: no todos los números han sido sorteados."];
        }

        // 4. Insertar el registro del bingo válido
        $stmt = $this->pdo->prepare("
            INSERT INTO bingo_start (user_id, id_bingo,carton_id, sorteo_fecha)
            VALUES (:user_id,:id_bingo,:carton_id, :sorteo_fecha)
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':id_bingo', $id_bingo, PDO::PARAM_INT);
        $stmt->bindParam(':carton_id', $cartonId, PDO::PARAM_INT);
        $stmt->bindParam(':sorteo_fecha', $sorteoFecha);
        $stmt->execute();

        return ["success" => true];
    } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
    }
}

}