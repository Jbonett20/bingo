<?php
require_once '../config/db.php';

class CartonModel
{
    private $pdo;

    public function __construct()
    {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

    public function generarCarton($usuario_id, $bingo_id)
    {
        // Obtener sorteo activo asociado al bingo
        $stmt = $this->pdo->prepare("
            SELECT s.*
            FROM sorteos s
            INNER JOIN sorteos_bingos sb ON s.id_sorteo = sb.sorteo_id
            WHERE sb.bingo_id = ? AND s.estado = 1
            LIMIT 1
        ");
        $stmt->execute([$bingo_id]);
        $sorteo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sorteo) {
            return false; // No hay sorteo activo para ese bingo
        }
        // Obtener datos del bingo
        $stmt = $this->pdo->prepare("SELECT id_bingo, nombre_bingo, valor,link FROM bingo_juegos WHERE id_bingo = ?");
        $stmt->execute([$bingo_id]);
        $bingo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si ya tiene un cartón generado
        $stmt = $this->pdo->prepare("
            SELECT * FROM cartones 
            WHERE jugador_id = ? AND bingo_id = ? AND sorteo_id = ? 
            LIMIT 1
        ");
        $stmt->execute([$usuario_id, $bingo_id, $sorteo['id_sorteo']]);
        $cartonExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartonExistente) {
            $valorbingo = $bingo['valor'];
            $valorpartida = $this->valorPartidaBingo($bingo['id_bingo'], $valorbingo);
            return [
                'success' => true,
                'carton' => [
                    $cartonExistente['numero1'],
                    $cartonExistente['numero2'],
                    $cartonExistente['numero3'],
                    $cartonExistente['numero4'],
                    $cartonExistente['numero5']
                ],
                'sorteo_id' => $sorteo['id_sorteo'],
                'fecha' => $sorteo['fecha_juego'],
                'link' => $bingo['link'],
                'idBingo' => $bingo['id_bingo'],
                'carton_id' => $cartonExistente['id_carton'],
                'valor' => $bingo['valor'],
                'partida' => $valorpartida
            ];
        }

        $carton_generado = false;

        while (!$carton_generado) {
            // Generar 5 números únicos entre 3 y 18
            $nuevo = [];
            while (count($nuevo) < 5) {
                $num = rand(4, 16);
                if (!in_array($num, $nuevo)) {
                    $nuevo[] = $num;
                }
            }

            // Verificar si ese cartón ya existe
            $stmt = $this->pdo->prepare("
                SELECT numero1, numero2, numero3, numero4, numero5 
                FROM cartones 
                WHERE bingo_id = ? AND sorteo_id = ?
            ");
            $stmt->execute([$bingo_id, $sorteo['id_sorteo']]);
            $anteriores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $diferente = true;
            foreach ($anteriores as $carton) {
                $nums = array_map('intval', [
                    $carton['numero1'],
                    $carton['numero2'],
                    $carton['numero3'],
                    $carton['numero4'],
                    $carton['numero5']
                ]);
                if (count(array_intersect($nuevo, $nums)) === 5) {
                    $diferente = false;
                    break;
                }
            }

            if ($diferente) {
                // Insertar el nuevo cartón
                $stmt = $this->pdo->prepare("
                    INSERT INTO cartones 
                    (jugador_id, bingo_id, sorteo_id, numero1, numero2, numero3, numero4, numero5, creado_en) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([
                    $usuario_id,
                    $bingo_id,
                    $sorteo['id_sorteo'],
                    $nuevo[0],
                    $nuevo[1],
                    $nuevo[2],
                    $nuevo[3],
                    $nuevo[4]
                ]);

                $carton_id = $this->pdo->lastInsertId();
                $carton_generado = true;
            }
        }
        $valorbingo = $bingo['valor'];
        $valorpartida = $this->valorPartidaBingo($bingo['id_bingo'], $valorbingo);
        return [
            'success' => true,
            'carton' => $nuevo,
            'sorteo_id' => $sorteo['id_sorteo'],
            'fecha' => $sorteo['fecha_juego'],
            'carton_id' => $carton_id,
            'nombre_bingo' => $bingo['nombre_bingo'],
            'link' => $bingo['link'],
            'idBingo' => $bingo['id_bingo'],
            'valor' => $bingo['valor'],
            'partida' => $valorpartida
        ];
    }

    public function valorPartidaBingo($id, $valor)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as vendidos FROM cartones WHERE bingo_id = ?");
        $stmt->execute([$id]);
        $vendidos = $stmt->fetchColumn();

        if ($vendidos > 0) {
            return ($vendidos * $valor * 0.75);
        }

        return '';
    }
}
