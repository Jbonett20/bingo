<?php
require_once __DIR__ . '/../config/db.php';

class SorteoModel
{
    private $pdo;

    public function __construct()
    {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function obtenerFechaJuego($idBingo)
{
    $stmt = $this->pdo->prepare("SELECT sorteo_id FROM sorteos_bingos WHERE bingo_id = :bingo_id LIMIT 1");
    $stmt->execute([':bingo_id' => $idBingo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !isset($row['sorteo_id'])) {
        return null; 
    }

    $sorteoId = $row['sorteo_id'];

    $stmt2 = $this->pdo->prepare("SELECT fecha_juego FROM sorteos WHERE id_sorteo = :id");
    $stmt2->execute([':id' => $sorteoId]);
    return $stmt2->fetch(PDO::FETCH_ASSOC);
}

}
