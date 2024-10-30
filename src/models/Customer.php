<?php


namespace models;

use config\Database;

class Customer extends Model
{

    public function __construct(
        private int $customer_id,
        private ?string $cust_first_name,
        private ?string $cust_last_name,
        private ?string $cust_street_address,
        private ?string $cust_postal_code,
        private ?string $cust_city,
        private ?string $cust_state,
        private ?string $cust_country,
        private ?string $phone_numbers,
        private ?string $nls_language,
        private ?string $nls_territory,
        private ?float $credit_limit,
        private ?string $cust_email,
        private ?int $account_mgr_id,
        private ?string $cust_geo_location,
        private ?string $date_of_birth,
        private ?string $marital__status,
        private ?string $gender,
        private ?string $income_level
    ) {
    }


    protected static $table = 'customers';


    /*
    
 public function save(): void
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


        } catch (\mysqli_sql_exception $e) {
            echo "<script>alert('Error en agregar o modificar un empleado.');</script>";
            return;
        } catch (\Exception $e) {
            "<script>alert('Se ha producido un error.');</script>";
        } finally {
            if ($conn) {
                $db->closeDB();
            }
        }

    }
     */

}