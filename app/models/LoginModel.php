<?php
require_once '../config/db.php';

class LoginModel {
    private $pdo;

    public function __construct() {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

    public function verificarUsuario($usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND estado_id = 1 LIMIT 1");
        $stmt->execute(['usuario' => $usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
