<?php

namespace Juanjo\Www\config;
require '../../vendor/autoload.php';

class Database
{
    public $host;
    public $port;
    public $database;
    public $username;
    public $password;
    public $conn;

    // Constructor para inicializar la conexión con la configuración
    public function __construct($host, $port, $database, $username, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    // Método para cargar la configuración
    public static function loadConfig($fitxer): array
    {
        $configuracio = [];

        if (!file_exists($fitxer)) {
            throw new \Exception("El fitxer de configuració no existeix: $fitxer");
        }

        $linies = file($fitxer, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($linies as $linia) {
            $linia = trim($linia);

            // Ignorar comentarios
            if (strpos($linia, '#') !== 0) {
                // Dividimos la línea 
                list($clave, $valor) = explode('=', $linia, 2);
                $configuracio[$clave] = $valor;
            }
        }

        return $configuracio;
    }

    // Método para conectarse a la base de datos
    // Mètode per connectar-se a la base de dades
    public function connectDB(): \mysqli
    {
        $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

        // Comprovem si hi ha errors en la connexió
        if ($this->conn->connect_error) {
            die("Error en la connexió: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // Cerrar la conexión
    public function closeDB(): void
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
