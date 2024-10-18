<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <li><a href="index.php">Home</a></li>
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
                        <li><a href="./Customers.php">Customers</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="orders.php">Orders</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div id="section">
            <h3>Employees</h3>
            <h4></h4>

            <!----------------------------------------------------------------------->

            <?php
            require_once __DIR__ . "./Employee.php";

            use Models\Employee;


            // Si es un POST (enviar formulario para agregar o actualizar)
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $employee = new Employee(0);

                $employee->setEmployeeId($_POST['employee_id']);
                $employee->setFirstName($_POST['first_name']);
                $employee->setLastName($_POST['last_name']);
                $employee->setJobId($_POST['job_id']);
                $employee->setEmail(!empty($_POST['email']) ? $_POST['email'] : null);
                $employee->setPhoneNumber(!empty($_POST['phone_number']) ? $_POST['phone_number'] : null);
                $employee->setHireDate(!empty($_POST['hire_date']) ? $_POST['hire_date'] : null);
                $employee->setSalary(!empty($_POST['salary']) ? (float) $_POST['salary'] : null);
                $employee->setCommissionPct(!empty($_POST['commission_pct']) ? (float) $_POST['commission_pct'] : null);
                $employee->setManagerId(!empty($_POST['manager_id']) ? (int) $_POST['manager_id'] : null);
                $employee->setDepartmentId(!empty($_POST['department_id']) ? (int) $_POST['department_id'] : null);

                // Guardar (insertar o actualizar)
                $employee->save();
            }

            if (isset($_GET['id'])) {
                $id = (int) $_GET['id'];

                // Buscar empleado por ID
                $employee = Employee::findById($id);

                if ($employee === null) {
                    echo "No se pudo encontrar el empleado.";
                }
            } else {
                // Obtener el último id de la base de datos y asignar el nuevo id ( +1)
                $lastEmployeeId = Employee::getLastEmployeeId();
                $lastEmployeeId += 1;
                $employee = new Employee($lastEmployeeId);


            }

            ?>

            <form method="post">
                <label for="employee_id">Employee ID:</label>
                <input type="number" name="employee_id" value="<?php echo $employee->getEmployeeId(); ?>" readonly>


                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="<?= $employee->getFirstName() ?? '' ?>"
                    required maxlength="20" pattern="[a-zA-Z]{1,20}">


                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?= $employee->getLastName() ?? '' ?>"
                    required maxlength="25" pattern="[a-zA-Z]{1,20}">

                <label for="job_id">Job ID:</label>
                <input type="text" name="job_id" id="job_id" value="<?= $employee->getJobId() ?? '' ?>" required
                    maxlength="10">


                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= $employee->getEmail() ?? '' ?>" maxlength="25">

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number"
                    value="<?= $employee->getPhoneNumber() ?? '' ?>" maxlength="20">

                <label for="hire_date">Hire Date:</label>
                <input type="date" name="hire_date" id="hire_date" value="<?= $employee->getHireDate() ?? '' ?>">

                <label for="salary">Salary:</label>
                <input type="text" name="salary" id="salary" value="<?= $employee->getSalary() ?? null ?>"
                    pattern="^\d{1,6}(\.\d{1,2})?$" title="Introduce un número de hasta 2 decimales.">

                <label for="commission_pct">Commission Percentage:</label>
                <input type="number" step="0.01" name="commission_pct" id="commission_pct"
                    value="<?= $employee->getCommissionPct() ?? '' ?>" pattern="^\d{1,6}(\.\d{1,2})?$"
                    title="Introduce un número de hasta 2 decimales.">

                <label for="manager_id">Manager ID:</label>
                <input type="number" name="manager_id" id="manager_id" value="<?= $employee->getManagerId() ?? '' ?>">

                <label for="department_id">Department ID:</label>
                <input type="number" name="department_id" id="department_id"
                    value="<?= $employee->getDepartmentId() ?? '' ?>">

                <button type="submit">
                    Guardar
                </button>
            </form>


        </div>
    </div>

    <div id="footer">
        <p>(c) IES Emili Darder - 2024</p>
    </div>
</body>

</html>