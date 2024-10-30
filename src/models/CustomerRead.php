<?php

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../index.php");
    exit;
}

require '../../vendor/autoload.php';

use config\Database;
use models\Customer;

try {

    // Verifica que se ha pasado un id.
    if (isset($_GET['id'])) {
        $customer_id = intval($_GET['id']);

        $config = Database::loadConfig('C:/temp/config.db');
        $db = new Database(
            $config['DB_HOST'],
            $config['DB_PORT'],
            $config['DB_DATABASE'],
            $config['DB_USERNAME'],
            $config['DB_PASSWORD']
        );
        $conn = $db->connectDB();

        $sql = "SELECT * FROM customers WHERE CUSTOMER_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró un cliente con el ID proporcionado
        if ($result->num_rows > 0) {

            $customerData = $result->fetch_assoc();

            // Crear una instancia del cliente
            $customer = new Customer(
                customer_id: $customerData['CUSTOMER_ID'],
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
                cust_geo_location: json_encode($customerData['CUST_GEO_LOCATION']),  // Usar json_encode para almacenar la ubicación geográfica
                date_of_birth: $customerData['DATE_OF_BIRTH'],
                marital_status: $customerData['MARITAL_STATUS'],
                gender: $customerData['GENDER'],
                income_level: $customerData['INCOME_LEVEL']
            );
        } else {
            echo "<script>alert('Cliente no encontrado.');</script>";
            exit();
        }
    } else {
        echo "<script>alert('ID del cliente no ha sido proporcionado');</script>";
        exit();
    }
} catch (\mysqli_sql_exception $e) {
    echo "<script>alert('Error en agregar o modificar un cliente.');</script>";

} catch (\Exception $e) {
    echo "<script>alert('Error general ClienteRead. ');</script>";
} finally {
    if ($db) {
        $db->closeDB();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estils.css">
    <title>Human Resource</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }

        table tr td:last-child {
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <div id="header">
        <h1>HR & OE Management</h1>
        <form action="logout.php" method="post">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>
    <div id="content">
        <div id="menu">
            <ul>
                <li><a href="../../index.php">Home</a></li>
                <li>
                    <ul> HR
                        <li><a href="./EmployeeList.php">Employees</a></li>
                        <li><a href="departments.php">Departments</a></li>
                        <li><a href="jobs.php">Jobs</a></li>
                        <li><a href="locations.php">Locations</a></li>
                    </ul>
                </li>
                <li>
                    <ul> OE
                        <li><a href="warehouses.php">Warehouses</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a href="./CustomerList.php">Customers</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="orders.php">Orders</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div id="section">

            <div class="container">
                <h1>Customer details</h1>
                <?php if ($customer !== null): ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td><?= $customer->getCustomerId() ?></td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td><?= $customer->getCustFirstName() ?></td>
                        </tr>
                        <tr>
                            <th>Apellido</th>
                            <td><?= $customer->getCustLastName() ?></td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td><?= $customer->getCustStreetAddress() ?></td>
                        </tr>
                        <tr>
                            <th>Codigo Postal</th>
                            <td><?= $customer->getCustPostalCode() ?></td>
                        </tr>
                        <tr>
                            <th>Ciudad</th>
                            <td><?= $customer->getCustCity() ?></td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td><?= $customer->getCustState() ?></td>
                        </tr>
                        <tr>
                            <th>País</th>
                            <td><?= $customer->getCustCountry() ?></td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td><?= $customer->getPhoneNumbers() ?></td>
                        </tr>
                        <tr>
                            <th>Idioma</th>
                            <td><?= $customer->getNlsLanguage() ?></td>
                        </tr>
                        <tr>
                            <th>Territorio</th>
                            <td><?= $customer->getNlsTerritory() ?></td>
                        </tr>
                        <tr>
                            <th>Límite credito</th>
                            <td><?= $customer->getCreditLimit() ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $customer->getCustEmail() ?></td>
                        </tr>
                        <tr>
                            <th>ID empleado</th>
                            <td><?= $customer->getAccountMgrId() ?></td>
                        </tr>
                        <tr>
                            <th>Geolocalización</th>
                            <td><?= $customer->getCustGeoLocation() ?></td>
                        </tr>
                        <tr>
                            <th>Fecha Nacimiento</th>
                            <td><?= $customer->getDateOfBirth() ?></td>
                        </tr>
                        <tr>
                            <th>Estado civil</th>
                            <td><?= $customer->getMaritalStatus() ?></td>
                        </tr>
                        <tr>
                            <th>Sexo</th>
                            <td><?= $customer->getGender() ?></td>
                        </tr>
                        <tr>
                            <th>Nivel de ingresos</th>
                            <td><?= $customer->getIncomeLevel() ?></td>
                        </tr>

                    </table>
                <?php else: ?>
                    <script>alert('Cliente no encontrado.');</script>
                <?php endif; ?>
                <a href="CustomerList.php" class="btn btn-primary">Volver a la lista de clientes</a>
            </div>
        </div>
    </div>
    <div id="footer">
        <p>(c) IES Emili Darder - 2024</p>
    </div>
</body>

</html>