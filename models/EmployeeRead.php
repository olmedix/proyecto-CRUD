<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Employee.php';



use Config\Database;
use Models\Employee;

try {

    $employee = null;

    // Verifica que se ha pasado un id.
    if (isset($_GET['id'])) {
        $employee_id = intval($_GET['id']);

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

        // Consultar los datos del empleado por ID
        $sql = "SELECT e.employee_id, e.first_name, e.last_name, e.email, e.phone_number, e.hire_date, e.job_id, e.salary, e.commission_pct, e.manager_id, e.department_id, d.department_name
                FROM employees e
                INNER JOIN departments d ON e.department_id = d.department_id
                WHERE e.employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró un empleado con el ID proporcionado
        if ($result->num_rows > 0) {
            $employeeData = $result->fetch_assoc();

            // Crear una instancia del empleado
            $employee = new Employee(
                $employeeData['employee_id'],
                $employeeData['first_name'],
                $employeeData['last_name'],
                $employeeData['email'],
                $employeeData['phone_number'],
                $employeeData['hire_date'],
                $employeeData['job_id'],
                $employeeData['salary'],
                $employeeData['commission_pct'],
                $employeeData['manager_id'],
                $employeeData['department_id']
            );
            $department_name = $employeeData['department_name'];  // Almacenar el nombre del departamento
        } else {
            echo "Empleado no encontrado.";
            exit;
        }

        $stmt->close();
        $db->closeDB();
    } else {
        echo "ID de empleado no ha sido proporcionado.";
        exit;
    }
} catch (Exception $e) {
    echo 'Error general EmployeeRead: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../src/css/estils.css">
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
    </div>
    <div id="content">
        <div id="menu">
            <ul>
                <li><a href="../index.php">Home</a></li>
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
                        <li><a href="customers.php">Customers</a></li>
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
                            <th>Nombre del Departamento</th> <!-- Nueva fila -->
                            <td><?= $department_name ?></td> <!-- Mostrar nombre del departamento -->
                        </tr>
                    </table>
                <?php else: ?>
                    <p>Empleado no encontrado.</p>
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