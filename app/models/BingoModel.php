<?php
require_once '../config/db.php';

class BingoModel {
    private $pdo;

    public function __construct() {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

    public function crearBingoYJuego($nombre, $valor, $fecha_juego) {
        $this->pdo->beginTransaction();
        try {
            // 1. Crear sorteo
            $stmt1 = $this->pdo->prepare("INSERT INTO sorteos (fecha_juego, fecha_creacion, estado) VALUES (:fecha_juego, NOW(), 1)");
            $stmt1->execute([':fecha_juego' => $fecha_juego]);
            $sorteo_id = $this->pdo->lastInsertId();

            // 2. Crear bingo
            $stmt2 = $this->pdo->prepare("INSERT INTO bingo_juegos (nombre_bingo, valor, status_id, fecha_creacion)
                                          VALUES (:nombre, :valor, 1, NOW())");
            $stmt2->execute([
                ':nombre' => $nombre,
                ':valor' => $valor
            ]);
            $bingo_id = $this->pdo->lastInsertId();

            // 3. Relacionar sorteo y bingo
            $stmt3 = $this->pdo->prepare("INSERT INTO sorteos_bingos (bingo_id, sorteo_id) VALUES (:bingo_id, :sorteo_id)");
            $stmt3->execute([
                ':bingo_id' => $bingo_id,
                ':sorteo_id' => $sorteo_id
            ]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function listarBingos()
{
    $sql = "SELECT id_bingo, nombre_bingo FROM bingo_juegos ORDER BY fecha_creacion DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
