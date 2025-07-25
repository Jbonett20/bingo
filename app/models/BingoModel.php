<?php
require_once '../config/db.php';

class BingoModel {
    private $pdo;

    public function __construct() {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

    public function crearBingoYJuego($nombre, $valor,$link, $fecha_juego) {
        $this->pdo->beginTransaction();
        try {
            // 1. Crear sorteo
            $stmt1 = $this->pdo->prepare("INSERT INTO sorteos (fecha_juego, fecha_creacion, estado) VALUES (:fecha_juego, NOW(), 1)");
            $stmt1->execute([':fecha_juego' => $fecha_juego]);
            $sorteo_id = $this->pdo->lastInsertId();

            //verificar que no se existan dos bingos con el mismo nombre 
            $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM bingo_juegos WHERE nombre_bingo = :nombre");
            $stmtCheck->execute([':nombre' => $nombre]);
            if ($stmtCheck->fetchColumn() > 0) {
                $this->pdo->rollBack();
                return false; 
            }

            // 2. Crear bingo
            $stmt2 = $this->pdo->prepare("INSERT INTO bingo_juegos (nombre_bingo, valor,link, status_id, fecha_creacion)
                                          VALUES (:nombre, :valor,:link,1, NOW())");
            $stmt2->execute([
                ':nombre' => $nombre,
                ':valor' => $valor,
                ':link' => $link
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
     public function registrarNumeroJugado($bingo_id, $numero) {
     
        try {
          
            $stmt = $this->pdo->prepare("SELECT sb.sorteo_id FROM sorteos_bingos sb WHERE sb.bingo_id = :bingo_id LIMIT 1");
            $stmt->execute(['bingo_id' => $bingo_id]);
            $sorteo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sorteo) {
                return ['success' => false, 'message' => 'No se encontró el sorteo asociado al bingo.'];
            }

            $sorteo_id = $sorteo['sorteo_id'];

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM bingo_numeros_sorteados 
                                         WHERE id_bingo = :bingo_id AND numero_sorteado = :numero");
            $stmt->execute(['bingo_id' => $bingo_id, 'numero' => $numero]);

            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'El número ya ha sido sorteado.'];
            }

            $stmt = $this->pdo->prepare("INSERT INTO bingo_numeros_sorteados (id_bingo, id_sorteo, numero_sorteado)
                                         VALUES (:bingo_id, :sorteo_id, :numero)");
            $stmt->execute([
                'bingo_id' => $bingo_id,
                'sorteo_id' => $sorteo_id,
                'numero' => $numero
            ]);

            return ['success' => true, 'message' => "Número $numero registrado para bingo ID $bingo_id."];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al registrar el número. ' . $e->getMessage()];
        }
    }
public function getNumerosSorteados($bingo_id) {
    $stmt = $this->pdo->prepare("SELECT numero_sorteado FROM bingo_numeros_sorteados WHERE id_bingo = :bingo_id ORDER BY id_bingo DESC LIMIT 90");
    $stmt->execute(['bingo_id' => $bingo_id]);
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $result ?: false;
}
public function getBingoGnado($bingo_id) {
    $stmt = $this->pdo->prepare("
        SELECT u.nombre, u.apellido, bs.id_bingo, bs.carton_id
        FROM bingo_start bs
        INNER JOIN usuarios u ON bs.user_id = u.id_user
        WHERE bs.id_bingo = :bingo_id
          AND bs.estado = 'GANADOR'  
        LIMIT 1
    ");
    $stmt->execute(['bingo_id' => $bingo_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: false;
}

}
