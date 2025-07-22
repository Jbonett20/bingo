<?php
require_once '../config/db.php';

class getBingoModel {
    private $pdo;

    public function __construct() {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

    public function getBingoAll($userId) {
        $sql = "SELECT b.bingo_id AS id, bj.nombre_bingo AS nombre, sb.sorteo_id
                FROM bingo_jugadores b
                JOIN bingo_juegos bj ON b.bingo_id = bj.id_bingo
                JOIN sorteos_bingos sb ON bj.id_bingo = sb.bingo_id
                JOIN sorteos s ON sb.sorteo_id = sb.sorteo_id
                WHERE b.user_id = :user_id
                gROUP BY b.bingo_id, bj.nombre_bingo, sb.sorteo_id
                ORDER BY s.fecha_juego DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
