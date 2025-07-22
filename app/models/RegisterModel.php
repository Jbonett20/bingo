<?php
require_once '../config/db.php';

class RegisterModel {
    private $pdo;

    public function __construct() {
        DB::init();
        $this->pdo = new PDO(DB::$dsn, DB::$user, DB::$password);
    }

    public function registrarUsuario($data) {
        $sql = "INSERT INTO usuarios (
                    nombre, apellido, identificacion, tipo_identificacion_id,
                    genero_id, correo, usuario, clave, estado_id, rol_id, creado_en
                ) VALUES (
                    :nombre, :apellido, :identificacion, :tipo_identificacion_id,
                    :genero_id, :correo, :usuario, :clave, 1, :rol_id, NOW()
                )";

        $stmt = $this->pdo->prepare($sql);

        $data['clave'] = password_hash($data['clave'], PASSWORD_DEFAULT);

        return $stmt->execute($data);
    }
}
