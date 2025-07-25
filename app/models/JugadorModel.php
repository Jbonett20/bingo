<?php
require_once '../config/db.php';

class JugadoresModel {
    private $db;

   public function __construct() {
        DB::init();
        $this->db = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

public function obtenerJugadoresPorBingo($bingo_id)
{
    $sql = "SELECT 
                u.id_user, 
                u.nombre, 
                u.apellido, 
                u.identificacion, 
                u.telefono, 
                u.estado_id,
                IFNULL(bj.estado_pago, 0) AS pagado
            FROM usuarios u
            LEFT JOIN bingo_jugadores bj 
                ON u.id_user = bj.user_id AND bj.bingo_id = ?
            WHERE u.rol_id = 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$bingo_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function marcarPago($bingo_id, $usuario_id) {
        // Si ya existe registro, actualiza. Si no, inserta.
        $sql = "INSERT INTO bingo_jugadores (bingo_id, user_id, estado_pago)
                VALUES (?, ?, 'Pagado')
                ON DUPLICATE KEY UPDATE estado_pago = 'Pagado'";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$bingo_id, $usuario_id]);
    }
}
