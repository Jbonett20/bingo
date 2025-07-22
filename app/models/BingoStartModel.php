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

    public function registrarBingo($userId, $cartonId, $sorteoFecha)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO bingo_start (user_id, carton_id, sorteo_fecha) VALUES (:user_id, :carton_id, :sorteo_fecha)");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':carton_id', $cartonId, PDO::PARAM_INT);
            $stmt->bindParam(':sorteo_fecha', $sorteoFecha);
            $stmt->execute();

            return ["success" => true];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}