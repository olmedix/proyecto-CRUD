<?php


namespace models;

use config\Database;

class Customer extends Model
{

    public function __construct(
        private int $customer_id,
        private ?string $cust_first_name = null,
        private ?string $cust_last_name = null,
        private ?string $cust_street_address = null,
        private ?string $cust_postal_code = null,
        private ?string $cust_city = null,
        private ?string $cust_state = null,
        private ?string $cust_country = null,
        private ?string $phone_numbers = null,
        private ?string $nls_language = null,
        private ?string $nls_territory = null,
        private ?float $credit_limit = null,
        private ?string $cust_email = null,
        private ?int $account_mgr_id = null,
        private ?string $cust_geo_location = null,
        private ?string $date_of_birth = null,
        private ?string $marital_status = null,
        private ?string $gender = null,
        private ?string $income_level = null
    ) {
    }

    protected static $table = 'customers';


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
            // Aquí realmente hacemos la conexion
            $conn = $db->connectDB();
            $conn->autocommit(false);

            $table = static::$table;

            // Verificar si el email ya existe (excluyendo el caso del mismo customer_id)
            $sql = "SELECT customer_id FROM $table WHERE cust_email = ? AND customer_id != ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "<script>alert('Error al preparar la consulta.');</script>";
                return;
            }
            $stmt->bind_param("si", $this->cust_email, $this->customer_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Si hay un resultado, significa que ya existe otro registro con ese email
            if ($result->num_rows > 0) {
                echo "<script>alert('Ya existe otro cliente con ese email.');</script>";
                return;
            }


            //Verificar que el id del empleado existe
            $sql = "SELECT employee_id FROM employees";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $exists = false;

            while ($row = $result->fetch_assoc()) {
                if ($row['employee_id'] == $this->account_mgr_id) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists && $this->account_mgr_id != null) {
                echo "<span style='color: red; font-weight: bold;'>It has not been possible to add or modify the client, check the employee ID or the geo location format</span>";
                return;
            }



            $sql = "INSERT INTO $table (CUSTOMER_ID, 
    CUST_FIRST_NAME, 
    CUST_LAST_NAME, 
    CUST_STREET_ADDRESS, 
    CUST_POSTAL_CODE, 
    CUST_CITY, 
    CUST_STATE, 
    CUST_COUNTRY, 
    PHONE_NUMBERS, 
    NLS_LANGUAGE, 
    NLS_TERRITORY, 
    CREDIT_LIMIT, 
    CUST_EMAIL, 
    ACCOUNT_MGR_ID, 
    CUST_GEO_LOCATION, 
    DATE_OF_BIRTH, 
    MARITAL_STATUS, 
    GENDER, 
    INCOME_LEVEL
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
) ON DUPLICATE KEY UPDATE
    CUST_LAST_NAME = VALUES(CUST_LAST_NAME),
    CUST_LAST_NAME =VALUES(CUST_LAST_NAME), 
    CUST_STREET_ADDRESS =VALUES(CUST_STREET_ADDRESS), 
    CUST_POSTAL_CODE =VALUES(CUST_POSTAL_CODE), 
    CUST_CITY =VALUES(CUST_CITY), 
    CUST_STATE =VALUES(CUST_STATE), 
    CUST_COUNTRY =VALUES(CUST_COUNTRY), 
    PHONE_NUMBERS =VALUES(PHONE_NUMBERS), 
    NLS_LANGUAGE =VALUES(NLS_LANGUAGE), 
    NLS_TERRITORY =VALUES(NLS_TERRITORY), 
    CREDIT_LIMIT =VALUES(CREDIT_LIMIT), 
    CUST_EMAIL =VALUES(CUST_EMAIL), 
    ACCOUNT_MGR_ID =VALUES(ACCOUNT_MGR_ID), 
    CUST_GEO_LOCATION =VALUES(CUST_GEO_LOCATION), 
    DATE_OF_BIRTH =VALUES(DATE_OF_BIRTH), 
    MARITAL_STATUS =VALUES(MARITAL_STATUS), 
    GENDER =VALUES(GENDER), 
    INCOME_LEVEL =VALUES(INCOME_LEVEL)
";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "<script>alert('Error al preparar la consulta.');</script>";
                return;
            }
            $stmt->bind_param(
                "issssssssssdsisssss",
                $this->customer_id,
                $this->cust_first_name,
                $this->cust_last_name,
                $this->cust_street_address,
                $this->cust_postal_code,
                $this->cust_city,
                $this->cust_state,
                $this->cust_country,
                $this->phone_numbers,
                $this->nls_language,
                $this->nls_territory,
                $this->credit_limit,
                $this->cust_email,
                $this->account_mgr_id,
                $this->cust_geo_location,
                $this->date_of_birth,
                $this->marital_status,
                $this->gender,
                $this->income_level
            );

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $conn->commit();
                    echo '<p style="font-size: 20px; color: blue; font-weight: bold;">Customer successfully registered.</p>';

                } else {
                    $conn->rollback();
                    echo '<p style="font-size: 20px; color: red; font-weight: bold;">There was an error registering the customer.</p>';
                }
            } else {
                $conn->rollback();
                echo "<script>alert('Error al agregar o modificar un cliente. ELSE DE EXECUTE EN SAVE');</script>";
            }

        } catch (\mysqli_sql_exception $e) {
            echo "" . $e->getMessage();
            echo "<script>alert('Error en agregar o modificar un cliente. CATCH DE SAVE');</script>";
            return;
        } catch (\Exception $e) {
            "<script>alert('Se ha producido un error general en save.');</script>";
        } finally {
            if ($conn) {
                $db->closeDB();
            }
        }

    }

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


            $sql = "SELECT * FROM " . static::$table . " WHERE CUSTOMER_ID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                "<script>alert('Se ha producido un error con la consulta');</script>";
            }


            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si se encontró el empleado
            if ($result->num_rows === 0) {
                echo "<script>alert('No se encontró ningún cliente con ID $id');</script>";
                return null;
            }

            $customerData = $result->fetch_assoc();

            $customer_id = $customerData['CUSTOMER_ID'];

            $customer = new Customer(
                customer_id: $customer_id,
                cust_first_name: $customerData['CUST_FIRST_NAME'],
                cust_last_name: $customerData['CUST_LAST_NAME'],
                cust_street_address: $customerData['CUST_STREET_ADDRESS'],
                cust_postal_code: $customerData['CUST_POSTAL_CODE'],
                cust_city: $customerData['CUST_CITY'],
                cust_state: $customerData['CUST_STATE'],
                cust_country: $customerData['CUST_COUNTRY'],
                phone_numbers: $customerData['PHONE_NUMBERS'],
                nls_language: $customerData['NLS_LANGUAGE'],
                nls_territory: $customerData['NLS_TERRITORY'],
                credit_limit: $customerData['CREDIT_LIMIT'],
                cust_email: $customerData['CUST_EMAIL'],
                account_mgr_id: $customerData['ACCOUNT_MGR_ID'],
                cust_geo_location: $customerData['CUST_GEO_LOCATION'],
                date_of_birth: $customerData['DATE_OF_BIRTH'],
                marital_status: $customerData['MARITAL_STATUS'],
                gender: $customerData['GENDER'],
                income_level: $customerData['INCOME_LEVEL']
            );

            return $customer;
        } catch (\mysqli_sql_exception $e) {
            "<script>alert('Se ha producido un error con la consulta');</script>";

        } catch (\Exception $e) {
            "<script>alert('Se ha producido un error.');</script>";
        } finally {
            if ($conn) {
                $db->closeDB();
            }
        }
        return null;
    }

    public static function getLastCustomerId()
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

            $query = "SELECT MAX(CUSTOMER_ID) as last_id FROM $table";
            $result = $conn->query($query);


            if ($row = $result->fetch_assoc()) {
                return $row['last_id'];
            } else {
                return;
            }

        } catch (\mysqli_sql_exception $e) {
            "<script>alert('Se ha producido un error con la consulta');</script>";

        } catch (\Exception $e) {
            "<script>alert('Se ha producido un error.');</script>";
        } finally {
            if ($conn) {
                $conn->close();
            }
        }
    }


    public function destroy(int $customer_id): void
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

            // Verificar si el customer_id existe
            $sql = "SELECT * FROM $table WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                "<script>alert('Error al preparar la consulta.');</script>";
            }

            // Vincular los parámetros
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Comprobar si hay resultados
            if ($result->num_rows == 1) {

                $sql = "DELETE FROM $table WHERE customer_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $customer_id);

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
        } catch (\mysqli_sql_exception $e) {
            if ($conn) {
                $conn->rollback();
            }
        } catch (\Exception $e) {
            echo "<script>alert('Error general eliminando un cliente.');</script>";
        } finally {
            if ($conn) {
                $db->closeDB();
            }
        }
    }


    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    public function getCustFirstName(): ?string
    {
        return $this->cust_first_name;
    }

    public function setCustFirstName(?string $cust_first_name): void
    {
        $this->cust_first_name = $cust_first_name;
    }

    public function getCustLastName(): ?string
    {
        return $this->cust_last_name;
    }

    public function setCustLastName(?string $cust_last_name): void
    {
        $this->cust_last_name = $cust_last_name;
    }

    public function getCustStreetAddress(): ?string
    {
        return $this->cust_street_address;
    }

    public function setCustStreetAddress(?string $cust_street_address): void
    {
        $this->cust_street_address = $cust_street_address;
    }

    public function getCustPostalCode(): ?string
    {
        return $this->cust_postal_code;
    }

    public function setCustPostalCode(?string $cust_postal_code): void
    {
        $this->cust_postal_code = $cust_postal_code;
    }

    public function getCustCity(): ?string
    {
        return $this->cust_city;
    }

    public function setCustCity(?string $cust_city): void
    {
        $this->cust_city = $cust_city;
    }

    public function getCustState(): ?string
    {
        return $this->cust_state;
    }

    public function setCustState(?string $cust_state): void
    {
        $this->cust_state = $cust_state;
    }

    public function getCustCountry(): ?string
    {
        return $this->cust_country;
    }

    public function setCustCountry(?string $cust_country): void
    {
        $this->cust_country = $cust_country;
    }

    public function getPhoneNumbers(): ?string
    {
        return $this->phone_numbers;
    }

    public function setPhoneNumbers(?string $phone_numbers): void
    {
        $this->phone_numbers = $phone_numbers;
    }

    public function getNlsLanguage(): ?string
    {
        return $this->nls_language;
    }

    public function setNlsLanguage(?string $nls_language): void
    {
        $this->nls_language = $nls_language;
    }

    public function getNlsTerritory(): ?string
    {
        return $this->nls_territory;
    }

    public function setNlsTerritory(?string $nls_territory): void
    {
        $this->nls_territory = $nls_territory;
    }

    public function getCreditLimit(): ?float
    {
        return $this->credit_limit;
    }

    public function setCreditLimit(?float $credit_limit): void
    {
        $this->credit_limit = $credit_limit;
    }

    public function getCustEmail(): ?string
    {
        return $this->cust_email;
    }

    public function setCustEmail(?string $cust_email): void
    {
        $this->cust_email = $cust_email;
    }

    public function getAccountMgrId(): ?int
    {
        return $this->account_mgr_id;
    }

    public function setAccountMgrId(?int $account_mgr_id): void
    {
        $this->account_mgr_id = $account_mgr_id;
    }

    public function getCustGeoLocation(): ?string
    {
        return $this->cust_geo_location;
    }

    public function setCustGeoLocation(?string $cust_geo_location): void
    {
        $this->cust_geo_location = $cust_geo_location;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?string $date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->marital_status;
    }

    public function setMaritalStatus(?string $marital_status): void
    {
        $this->marital_status = $marital_status;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getIncomeLevel(): ?string
    {
        return $this->income_level;
    }

    public function setIncomeLevel(?string $income_level): void
    {
        $this->income_level = $income_level;
    }

}