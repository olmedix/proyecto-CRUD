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
    <link rel="stylesheet" href="../css/estils.css">
    <title>Human Resource</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../src/css/estils.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                        <li><a href="./Customers.php">Customers</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="orders.php">Orders</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div id="section">
            <h3>Customer</h3>

            <!----------------------------------------------------------------------->
            <?php
            require '../../vendor/autoload.php';

            use models\Customer;
            use Faker\Factory as FakerFactory;

            $faker = FakerFactory::create();


            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $customer = new Customer(0);

                $customer->setCustomerId($_POST['CUSTOMER_ID']);
                $customer->setCustFirstName($_POST['FIRST_NAME']);
                $customer->setCustLastName($_POST['LAST_NAME']);
                $customer->setCustEmail(!empty($_POST['EMAIL']) ? $_POST['EMAIL'] : null);
                $customer->setPhoneNumbers(!empty($_POST['PHONE_NUMBER']) ? $_POST['PHONE_NUMBER'] : null);
                $customer->setCustPostalCode(!empty($_POST['POSTAL_CODE']) ? $_POST['POSTAL_CODE'] : null);
                $customer->setCustState(!empty($_POST['STATE']) ? $_POST['STATE'] : null);
                $customer->setCustCity(!empty($_POST['CITY']) ? $_POST['CITY'] : null);
                $customer->setCustStreetAddress(!empty($_POST['ADDRESS']) ? $_POST['ADDRESS'] : null);
                $customer->setCustCountry(!empty($_POST['COUNTRY']) ? (float) $_POST['COUNTRY'] : null);
                $customer->setNlsLanguage(!empty($_POST['LANGUAGE']) ? (float) $_POST['LANGUAGE'] : null);
                $customer->setNlsTerritory(!empty($_POST['TERRITORY']) ? (int) $_POST['TERRITORY'] : null);
                $customer->setCreditLimit(!empty($_POST['CREDIT']) ? (int) $_POST['CREDIT'] : null);
                $customer->setAccountMgrId(!empty($_POST['MGR_ID']) ? (int) $_POST['MGR_ID'] : null);
                $customer->setCustGeoLocation(!empty($_POST['LOCATION']) ? (int) $_POST['LOCATION'] : null);
                $customer->setDateOfBirth(!empty($_POST['BIRTH']) ? (int) $_POST['BIRTH'] : null);
                $customer->setMaritalStatus(!empty($_POST['MARITAL_STATUS']) ? (int) $_POST['MARITAL_STATUS'] : null);
                $customer->setGender(!empty($_POST['GENDER']) ? (int) $_POST['GENDER'] : null);
                $customer->setIncomeLevel(!empty($_POST['INCOME_LEVEL']) ? (int) $_POST['INCOME_LEVEL'] : null);

                // Guardar (insertar o actualizar)
                $customer->save();

            }

            if (isset($_GET['id'])) {
                $id = (int) $_GET['id'];

                // Buscar empleado por ID
                $customer = Customer::findById($id);

                if ($customer === null) {
                    echo "<script>alert('No se pudo encontrar el cliente.');</script>";
                }
            } else {
                // Obtener el último id de la base de datos y asignar el nuevo id ( +1)
                $lastCustomerId = Customer::getLastCustomerId();
                $lastCustomerId += 1;
                $customer = new Customer($lastCustomerId);
            }

            ?>

            <form method="post" class="form">
                <fieldset>
                    <legend>Customer Information</legend>
                    //TODO

                    <label class="form__label" for="employee_id">Employee ID:</label>
                    <input class="form__input" type="number" name="employee_id"
                        value="<?php echo $employee->getEmployeeId(); ?>" readonly>

                    <label class="form__label" for="first_name">First Name:</label>
                    <input class="form__input" type="text" name="first_name" id="first_name"
                        value="<?= $employee->getFirstName() ? $employee->getFirstName() : $faker->firstName() ?>"
                        required maxlength="20" pattern="[a-zA-Z]{1,20}">

                    <label class="form__label" for="last_name">Last Name:</label>
                    <input class="form__input" type="text" name="last_name" id="last_name"
                        value="<?= $employee->getLastName() ? $employee->getLastName() : $faker->lastName() ?>" required
                        maxlength="25" pattern="[a-zA-Z]{1,20}">

                    <label class="form__label" for="job_id">Job ID:</label>
                    <select class="form__input" name="job_id" id="job_id" required>
                        <option value="AC_ACCOUNT" <?= $employee->getJobId() === 'AC_ACCOUNT' ? 'selected' : '' ?>
                            >AC_ACCOUNT</option>
                        <option value="AC_MGR" <?= $employee->getJobId() === 'AC_MGR' ? 'selected' : '' ?>>AC_MGR</option>
                        <option value="AD_ASST" <?= $employee->getJobId() === 'AD_ASST' ? 'selected' : '' ?>>AD_ASST
                        </option>
                        <option value="AD_PRES" <?= $employee->getJobId() === 'AD_PRES' ? 'selected' : '' ?>>AD_PRES
                        </option>
                    </select>

                    <label class="form__label" for="email">Email:</label>
                    <input class="form__input" type="email" name="email" id="email"
                        value="<?= $employee->getEmail() ?? null ?>" maxlength="25">


                    <label class="form__label" for="manager_id">Manager ID:</label>
                    <select class="form__input" name="manager_id" id="manager_id">
                        <option value="">No Manager</option>
                        <option value="100" <?= $employee->getManagerId() == 100 ? 'selected' : '' ?>>King Steven</option>
                        <option value="101" <?= $employee->getManagerId() == 101 ? 'selected' : '' ?>>Kochhar Neena
                        </option>

                    </select>



                    <button class="form__button" type="submit">Guardar</button>
                </fieldset>
            </form>



        </div>
    </div>

    <div id="footer">
        <p>(c) IES Emili Darder - 2024</p>
    </div>
</body>

</html>