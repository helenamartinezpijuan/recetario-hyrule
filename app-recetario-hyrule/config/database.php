<?php
namespace config;

/**
 * La clase Database se encarga de obtener la conexión a la base de datos
 */
class Database {
    private static $host = '';   // Free hosting site InfinityFree 
    private static $database = ''; // InfinityFree database
    private static $username = ''; // InfinityFree username
    private static $password = ''; // InfinityFree password

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