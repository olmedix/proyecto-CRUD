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

    $customer = null;

    // Verifica que se ha pasado un id.
    if (isset($_GET['id'])) {
        $customer_id = intval($_GET['id']);

        // Cargar la configuración de la base de datos
        $config = Database::loadConfig('C:/temp/config.db');
        $db = new Database(
            $config['DB_HOST'],
            $config['DB_PORT'],
            $config['DB_DATABASE'],
            $config['DB_USERNAME'],
            $config['DB_PASSWORD']
        );

        // Obtener la conexión a la base de datos
        $conn = $db->connectDB();

        $customers = Customer::all();

        // Verificar si se encontró un empleado con el ID proporcionado
        if ($result->num_rows > 0) {
            $customerData = $result->fetch_assoc();

            // Crear una instancia del empleado
            $customer = new Customer(
                $customerData['customer_id'],
                $customerData['cust_first_name'],
                $customerData['cust_last_name'],
                $customerData['cust_street_address'],
                $customerData['cust_postal_code'],
                $customerData['cust_city'],
                $customerData['cust_state'],
                $customerData['cust_country'],
                $customerData['phone_numbers'],
                $customerData['nls_language'],
                $customerData['nls_territory'],
                $customerData['credit_limit'],
                $customerData['cust_email'],
                $customerData['account_mgr_id'],
                $customerData['cust_geo_location'],
                $customerData['date_of_birth'],
                $customerData['marital_status'],
                $customerData['gender'],
                $customerData['income_level']
            );
        } else {
            echo "
                    <script>alert('Cliente no encontrado.');</script>";
            exit();
        }
    } else {
        echo "<script>alert('ID del cliente no ha sido proporcionado');</script>";
        exit();
    }
} catch (\mysqli_sql_exception $e) {
    echo "<script>alert('Error en agregar o modificar un cliente.');</script>";

} catch (Exception $e) {
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
                <h1>Employee details</h1>
                <?php if ($employee !== null): ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td><?= $employee->getEmployeeId() ?></td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td><?= $employee->getFirstName() ?></td>
                        </tr>
                        <tr>
                            <th>Apellido</th>
                            <td><?= $employee->getLastName() ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $employee->getEmail() ?></td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td><?= $employee->getPhoneNumber() ?></td>
                        </tr>
                        <tr>
                            <th>Fecha de Contratación</th>
                            <td><?= $employee->getHireDate() ?></td>
                        </tr>
                        <tr>
                            <th>ID del Trabajo</th>
                            <td><?= $employee->getJobId() ?></td>
                        </tr>
                        <tr>
                            <th>Salario</th>
                            <td><?= $employee->getSalary() ?></td>
                        </tr>
                        <tr>
                            <th>Porcentaje de Comisión</th>
                            <td><?= $employee->getCommissionPct() ?></td>
                        </tr>
                        <tr>
                            <th>ID del Gerente</th>
                            <td><?= $employee->getManagerId() ?></td>
                        </tr>
                        <tr>
                            <th>ID del Departamento</th>
                            <td><?= $employee->getDepartmentId() ?></td>
                        </tr>
                        <tr>
                            <th>Nombre del Departamento</th>
                            <td><?= $department_name ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    echo "
                    <script>alert('Empleado no encontrado.');</script>";
                <?php endif; ?>
                <a href="EmployeeList.php" class="btn btn-primary">Volver a la lista de empleados</a>
            </div>
        </div>
    </div>
    <div id="footer">
        <p>(c) IES Emili Darder - 2024</p>
    </div>
</body>

</html>