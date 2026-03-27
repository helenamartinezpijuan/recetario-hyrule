<?php
namespace config;

/**
 * La clase Database se encarga de obtener la conexión a la base de datos
 */
class Database {
    private static $host = 'sql200.infinityfree.com';   // Free hosting site InfinityFree
    private static $database = 'if0_41455627_db_recetario_hyrule';
    private static $username = 'if0_41455627';
    private static $password = '****'; // Lo oculto para subir el archivo a DeepSeek, la contraseña real es un texto, no símbolos.

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