<?php

namespace Juanjo\Www\models;
require '../../vendor/autoload.php';


use Juanjo\Www\config\Database;
use Exception;

abstract class Model
{
    // Método para obtener todos los registros de la tabla
    public static function all()
    {
        try {

            $config = Database::loadConfig('C:/temp/config.db');
            $db = new Database(
                $config['DB_HOST'],
                $config['DB_PORT'],
                $config['DB_DATABASE'],
                $config['DB_USERNAME'],
                $config['DB_PASSWORD']
            );

            $db->connectDB();

            // Obtener el nombre de la tabla de la clase hija
            $table = static::$table;

            try {
                $sql = "SELECT * FROM $table";
                $result = $db->conn->query($sql);

                // Comprobar si hay resultados
                $rows = [];
                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {
                        $employee = new static(...array_values($row));
                        $rows[] = $employee;
                    }
                }
            } catch (\mysqli_sql_exception $e) {
                echo "<script>alert('Error al ejecutar la consulta'); </script>";
            }

            // Retornar los registros obtenidos
            return $rows;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($db) && $db) {
                $db->closeDB();
            }
        }
    }
}

