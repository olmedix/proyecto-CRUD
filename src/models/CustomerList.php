<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../index.php");
    exit;
}

?>

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
    <link rel="stylesheet" href="../css/estils.css">
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
                        <li><a>Employees</a></li>
                        <li><a href="departments.php">Departments</a></li>
                        <li><a href="jobs.php">Jobs</a></li>
                        <li><a href="locations.php">Locations</a></li>
                    </ul>
                </li>
                <li>
                    <ul> OE
                        <li><a href="warehouses.php">Warehouses</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a>Customers</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="orders.php">Orders</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div id="section">
            <h3 class="section__title">Customers</h3>

            <div class="section__add"> <a href="./CustomerUpdate.php">Add customer</a></div>

            <!-- CODIGO PHP INTERNO -->
            <?php


            use models\Customer;
            require "../../vendor/autoload.php";

            $customers = Customer::all();

            if (count($customers) > 0) {
                echo '<table class="table table-bordered table-striped">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>First Name</th>';
                echo '<th>Last Name</th>';
                echo '<th>Email</th>';
                echo '<th>Phone Number</th>';
                echo '<th>Date of Birth</th>';
                echo '<th>Employee ID</th>';
                echo '<th>Marital status</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';


                foreach ($customers as $custom) {

                    $custom->getCustomerId();

                    echo '<tr>';
                    echo '<td>' . $custom->getCustomerId() . '</td>';
                    echo '<td>' . $custom->getCustFirstName() . '</td>';
                    echo '<td>' . $custom->getCustLastName() . '</td>';
                    echo '<td>' . $custom->getCustEmail() . '</td>';
                    echo '<td>' . $custom->getPhoneNumbers() . '</td>';
                    echo '<td>' . $custom->getDateOfBirth() . '</td>';
                    echo '<td>' . $custom->getAccountMgrId() . '</td>';
                    echo '<td>' . $custom->getMaritalStatus() . '</td>';
                    echo '<td>';
                    echo '<a href="./EmployeeRead.php?id=' . $custom->getCustomerId() . '" class="mr-2" title="View File" data-toggle="tooltip"><span class="fa fa-eye"></span></a>' .
                        '<a href="./EmployeeUpdate.php?id=' . $custom->getCustomerId() . '" class="mr-2" title="Update File" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>' .
                        '<a href="./EmployeeDestroy.php?id=' . $custom->getCustomerId() . '" class="mr-2" title="Delete File" data-toggle="tooltip"><span class="fa fa-trash"></span></a>' .
                        '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo "<script>alert('No customers found');</script>";
            }
            ?>

        </div>
    </div>

    <div id="footer">
        <p>(c) IES Emili Darder - 2024</p>
    </div>
</body>

</html>