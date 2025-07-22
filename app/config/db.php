<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class DB {
    public static $dsn;
    public static $user;
    public static $password;

    public static function init() {
        self::$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        self::$user = $_ENV['DB_USER'];
        self::$password = $_ENV['DB_PASS'];
    }
}

DB::init();
