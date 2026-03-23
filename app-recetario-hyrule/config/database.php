<?php
namespace config;

/**
 * La clase Database se encarga de obtener la conexión a la base de datos
 */
class Database {
    private static $host = 'localhost';
    private static $database = 'recetario_hyrule';
    private static $username = 'root';
    private static $password = '';

    public static function getConnection() {
        $conn = new \mysqli(self::$host, self::$username, self::$password, self::$database);
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
        return $conn;
    }
}
?>