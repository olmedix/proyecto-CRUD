<?php

namespace Juanjo\Www\models;
require '../../vendor/autoload.php';


use Juanjo\Www\config\Database;
use Exception;
use mysqli_sql_exception;

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
                $conn->autocommit(false);

                $table = static::$table;

                // Verificar si el email ya existe (excluyendo el caso del mismo empleado_id)
                $sql = "SELECT employee_id FROM $table WHERE email = ? AND employee_id != ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    echo "<script>alert('Error al preparar la consulta.');</script>";
                    return;
                }

                // Vincular los parámetros
                $stmt->bind_param("si", $this->email, $this->employee_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Si hay un resultado, significa que ya existe otro registro con ese email
                if ($result->num_rows > 0) {
                    echo "<script>alert('Ya existe otro empleado con ese email.');</script>";
                    $conn->rollback();
                    return;
                }


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
                    echo "<script>alert('Error al preparar la consulta.');</script>";
                    return;
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
                        echo '<p style="font-size: 20px; color: blue; font-weight: bold;">Employee successfully registered.</p>';

                    } else {
                        $conn->rollback();
                        echo '<p style="font-size: 20px; color: red; font-weight: bold;">There was an error registering the employee.</p>';
                    }
                } else {
                    $conn->rollback();
                    echo "Error en afegir o modificar l'empleat: " . $stmt->error;
                }
            } catch (mysqli_sql_exception $e) {
                echo "<script>alert('Error en agregar o modificar un empleado.');</script>";

                if ($conn) {
                    $conn->rollback();
                    $db->closeDB();
                }
                return;
            }

        } catch (Exception $e) {
            "<script>alert('Se ha producido un error.');</script>";
        } finally {
            if ($conn) {
                $db->closeDB();
            }
        }

    }


    public function destroy(int $employee_id): void
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
            $conn->autocommit(false);

            $table = static::$table;

            // Verificar si el employee_id existe
            $sql = "SELECT * FROM $table WHERE employee_id = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                "<script>alert('Error al preparar la consulta.');</script>";
            }

            // Vincular los parámetros
            $stmt->bind_param("i", $employee_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprobar si hay resultados
            if ($result->num_rows == 1) {

                $sql = "DELETE FROM $table WHERE employee_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $employee_id);


                // Ejecutar la consulta
                if ($stmt->execute()) {
                    $conn->commit();
                } else {
                    $conn->rollback();

                }
            } else {
                if ($conn) {
                    $conn->rollback();
                }
            }
        } catch (mysqli_sql_exception $e) {
            if ($conn) {
                $conn->rollback();
            }
        } finally {
            if ($conn) {
                $db->closeDB();
            }
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
                "<script>alert('Se ha producido un error con la consulta');</script>";
            }


            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si se encontró el empleado
            if ($result->num_rows === 0) {
                echo "<script>alert('No se encontró ningún empleado con ID $id');</script>";
                return null;
            }

            // Recuperar los datos
            $data = $result->fetch_assoc();

            // Debe ser en mayúsculas, así esta en la bdd sino devolverá null
            $employee_id = $data['EMPLOYEE_ID'] ?? null;

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

            return $employee;
        } catch (mysqli_sql_exception $e) {
            "<script>alert('Se ha producido un error con la consulta');</script>";

        } catch (Exception $e) {
            "<script>alert('Se ha producido un error.');</script>";
        } finally {
            if ($conn) {
                $db->closeDB();
            }
        }
        return null;
    }


    public static function getLastEmployeeId()
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

            $table = static::$table;

            $query = "SELECT MAX(EMPLOYEE_ID) as last_id FROM $table";
            $result = $conn->query($query);


            if ($row = $result->fetch_assoc()) {
                return $row['last_id'];
            } else {
                return;
            }

        } catch (mysqli_sql_exception $e) {
            "<script>alert('Se ha producido un error con la consulta');</script>";

        } catch (Exception $e) {
            "<script>alert('Se ha producido un error.');</script>";
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

