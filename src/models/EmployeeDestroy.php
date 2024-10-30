<?php

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../index.php");
    exit;
}

require '../../vendor/autoload.php';

use models\Employee;

if (isset($_GET['id'])) {
    $employeeId = (int) $_GET['id'];

    $employee = new Employee(0);

    $employee->destroy($employeeId);

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estils.css">
    <title>Human Resource</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../src/css/estils.css">
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <style>
        .delete__title {
            text-align: center;
            font-size: 40px;
            margin-bottom: 40px;
        }

        .delete__link a {
            margin: 0 auto;
            align-items: center;
        }


        .delete__link a {
            display: block;
            text-align: center;
            font-size: 22px;
            margin: 0 30%;
        }
    </style>
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

        <div id="section" class="section__delete">
            <h3 class="delete__title">The employee has been successfully removed!</h3>
            <div class="delete__link">
                <a href="./EmployeeList.php" class="btn btn-primary ">Return to employee list</a>
            </div>


        </div>
    </div>

    <div id="footer">
        <p>(c) IES Emili Darder - 2024</p>
    </div>
</body>

</html>