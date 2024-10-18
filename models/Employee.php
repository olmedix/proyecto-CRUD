<?php

namespace Models;

require_once __DIR__ . '/Model.php';

use config\Database;
use Exception;

class Employee extends Model
{

    public function __construct(
        private int $employee_id,
        private ?string $first_name = null,
        private ?string $last_name = null,
        private ?string $email = null,
        private ?string $phone_number = null,
        private ?string $hire_date = null,
        private ?string $job_id = null,
        private ?float $salary = null,
        private ?float $commission_pct = null,
        private ?int $manager_id = null,
        private ?int $department_id = null
    ) {
    }



    // Definir la taula associada a la classe
    protected static $table = 'employees';

    public function save(): void
    {
        try {
            // Carga el fichero donde están los parámetros de configuración
            $config = Database::loadConfig('C:/temp/config.db');

            $db = new Database(
                $config['DB_HOST'],
                $config['DB_PORT'],
                $config['DB_DATABASE'],
                $config['DB_USERNAME'],
                $config['DB_PASSWORD']
            );

            // Aquí realmente hacemos la conexion
            $conn = $db->connectDB();

            $table = static::$table;

            // Verificar si el email ya existe (excluyendo el caso del mismo empleado_id)
            $sql = "SELECT employee_id FROM $table WHERE email = ? AND employee_id != ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die('Error al preparar la consulta: ' . $conn->error);
            }

            // Vincular los parámetros
            $stmt->bind_param("si", $this->email, $this->employee_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Si hay un resultado, significa que ya existe otro registro con ese email
            if ($result->num_rows > 0) {
                echo "Error: ja existeix un altre empleat amb aquest email.";
                $stmt->close();
                return;
            }

            $stmt->close();

            try {

                // Intenta hacer un insert si el employee_id existe 
                $sql = "INSERT INTO $table (employee_id, first_name, last_name, email, phone_number, hire_date, job_id, salary, commission_pct, manager_id, department_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY 
                            UPDATE
                                first_name     = VALUES(first_name),
                                last_name      = VALUES(last_name),
                                email          = VALUES(email),
                                phone_number   = VALUES(phone_number),
                                hire_date      = VALUES(hire_date),
                                job_id         = VALUES(job_id),
                                salary         = VALUES(salary),
                                commission_pct = VALUES(commission_pct),
                                manager_id     = VALUES(manager_id),
                                department_id  = VALUES(department_id)";

                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die('Error al preparar la consulta: ' . $conn->error);
                }

                // Vincular los valores
                $stmt->bind_param(
                    "issssssddii",
                    $this->employee_id,
                    $this->first_name,
                    $this->last_name,
                    $this->email,
                    $this->phone_number,
                    $this->hire_date,
                    $this->job_id,
                    $this->salary,
                    $this->commission_pct,
                    $this->manager_id,
                    $this->department_id
                );

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $conn->commit();
                        echo "L'empleat s'ha afegit o modificat correctament.";
                    } else {
                        $conn->rollback();
                        echo "No s'ha realitzat cap canvi.";
                    }
                } else {
                    echo "Error en afegir o modificar l'empleat: " . $stmt->error;
                }
            } catch (\mysqli_sql_exception $e) {
                // Capturar cualquier excepción de MySQLi y mostrar un mensaje de error
                echo "Error general en afegir o modificar l'empleat: " . $e->getMessage();
            }


        } catch (Exception $e) {
            echo "Error general de save: " . $e->getMessage();
        } finally {
            if ($conn) {
                echo 'conexion cerrada';
                $db->closeDB();
            }
        }

    }


    public function destroy(): void
    {
        try {
            // Carga el fichero donde están los parámetros de configuración
            $config = Database::loadConfig('C:/temp/config.db');

            $db = new Database(
                $config['DB_HOST'],
                $config['DB_PORT'],
                $config['DB_DATABASE'],
                $config['DB_USERNAME'],
                $config['DB_PASSWORD']
            );

            // Aquí realmente hacemos la conexion
            $conn = $db->connectDB();

            $table = static::$table;

            // Connectar a la base de dades
            if (isset($this->employee_id)) {
                $sql = "SELECT * FROM $table WHERE employee_id = $this->employee_id";
                $result = $conn->query($sql);

                // Comprovar si hi ha resultats
                if ($result->num_rows == 1) {
                    $sql = "DELETE FROM $table 
				    WHERE employee_id = ?";
                    $stmt = $db->conn->prepare($sql);
                    // Vincular els valors
                    $stmt->bind_param("i", $this->employee_id);
                    // Executar la consulta
                    if ($stmt->execute()) {
                        echo "L'empleat s'ha eliminat correctament.";
                    } else {
                        echo "Error eliminant l'empleat: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "L'empleat no existeix.";
                }
            } else {
                echo "Error, ID no informat";
            }



            $db->closeDB();
        } catch (Exception $e) {
            echo "Error general de destroy: " . $e->getMessage();
        }
    }


    // Verificamos que exista un empleado con el id y retornamos  ? emleado: null 
    public static function findById(int $id)
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

            $conn = $db->connectDB();


            $sql = "SELECT * FROM " . static::$table . " WHERE EMPLOYEE_ID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new \mysqli_sql_exception("Error al preparar la consulta: " . $conn->error);
            }


            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si se encontró el empleado
            if ($result->num_rows === 0) {
                throw new Exception("No se encontró ningún empleado con ID $id");
            }

            // Recuperar los datos
            $data = $result->fetch_assoc();

            // Debe ser en mayúsculas, así esta en la bdd sino devolverá null
            $employee_id = $data['EMPLOYEE_ID'] ?? null;


            if ($employee_id === null) {
                throw new Exception("El ID del empleado es nulo o inválido.");
            }


            $employee = new self(
                $employee_id,
                $data['FIRST_NAME'] ?? null,
                $data['LAST_NAME'] ?? null,
                $data['EMAIL'] ?? null,
                $data['PHONE_NUMBER'] ?? null,
                $data['HIRE_DATE'] ?? null,
                $data['JOB_ID'] ?? null,
                $data['SALARY'] ?? null,
                $data['COMMISSION_PCT'] ?? null,
                $data['MANAGER_ID'] ?? null,
                $data['DEPARTMENT_ID'] ?? null
            );


            $db->closeDB();

            return $employee;
        } catch (\mysqli_sql_exception $e) {
            echo "Error de MySQL: " . $e->getMessage();
            return null;
        } catch (Exception $e) {
            // Otras excepciones
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public static function getLastEmployeeId()
    {
        $config = Database::loadConfig('C:/temp/config.db');
        $db = new Database(
            $config['DB_HOST'],
            $config['DB_PORT'],
            $config['DB_DATABASE'],
            $config['DB_USERNAME'],
            $config['DB_PASSWORD']
        );

        $conn = null;

        try {
            // Conectar a la base de datos
            $conn = $db->connectDB();

            // Consulta SQL para obtener el mayor `employee_id`
            $query = "SELECT MAX(EMPLOYEE_ID) as last_id FROM employees";
            $result = $conn->query($query);


            if ($row = $result->fetch_assoc()) {
                return $row['last_id'];
            } else {
                return 0; // Si no hay empleados, empezamos con el ID 0
            }

        } catch (Exception $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return 0;
        } finally {
            if ($conn) {
                $conn->close();
            }
        }
    }






    public function getEmployeeId(): int
    {
        return $this->employee_id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function getHireDate(): ?string
    {
        return $this->hire_date;
    }

    public function getJobId(): ?string
    {
        return $this->job_id;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function getCommissionPct(): ?float
    {
        return $this->commission_pct;
    }

    public function getManagerId(): ?int
    {
        return $this->manager_id;
    }

    public function getDepartmentId(): ?int
    {
        return $this->department_id;
    }

    // Setters
    public function setEmployeeId(int $employee_id): void
    {
        $this->employee_id = $employee_id;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setPhoneNumber(?string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    public function setHireDate(?string $hire_date): void
    {
        $this->hire_date = $hire_date;
    }

    public function setJobId(string $job_id): void
    {
        $this->job_id = $job_id;
    }

    public function setSalary(?float $salary): void
    {
        $this->salary = $salary;
    }

    public function setCommissionPct(?float $commission_pct): void
    {
        $this->commission_pct = $commission_pct;
    }

    public function setManagerId(?int $manager_id): void
    {
        $this->manager_id = $manager_id;
    }

    public function setDepartmentId(?int $department_id): void
    {
        $this->department_id = $department_id;
    }

}

?>