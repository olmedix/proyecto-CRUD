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
                        <li><a href="./CustomerList.php">Customers</a></li>
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
                $customer->setDateOfBirth(!empty($_POST['BIRTH']) ? $_POST['BIRTH'] : null);
                $customer->setMaritalStatus(!empty($_POST['MARITAL_STATUS']) ? $_POST['MARITAL_STATUS'] : null);
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

                    <label class="form__label" for="CUSTOMER_ID">Customer ID:</label>
                    <input class="form__input" type="number" name="CUSTOMER_ID"
                        value="<?php echo $customer->getCustomerId(); ?>" readonly>

                    <label class="form__label" for="FIRST_NAME">First Name:</label>
                    <input class="form__input" type="text" name="FIRST_NAME" id="FIRST_NAME"
                        value="<?= $customer->getCustFirstName() ? $customer->getCustFirstName() : $faker->firstName() ?>"
                        required maxlength="20" pattern="[a-zA-Z]{1,20}">

                    <label class="form__label" for="LAST_NAME">Last Name:</label>
                    <input class="form__input" type="text" name="LAST_NAME" id="LAST_NAME"
                        value="<?= $customer->getCustLastName() ? $customer->getCustLastName() : $faker->lastName() ?>"
                        required maxlength="20" pattern="[a-zA-Z]{1,20}">

                    <label class="form__label" for="EMAIL">Email:</label>
                    <input class="form__input" type="email" name="EMAIL" id="EMAIL"
                        value="<?= $customer->getCustEmail() ?? $faker->email() ?>" maxlength="30">

                    <label class="form__label" for="PHONE_NUMBER">Phone Number:</label>
                    <input class="form__input" type="text" name="PHONE_NUMBER" id="PHONE_NUMBER"
                        value="<?= $postalCode ?? null ?>" maxlength="100">

                    <?php
                    // Trunca el código postal a 10 caracteres
                    $postalCode = substr($customer->getCustPostalCode(), 0, 10);
                    echo "<script>console.log('Postal Code: " . $postalCode . "');</script>";
                    ?>

                    <label class="form__label" for="POSTAL_CODE">Postal code:</label>
                    <input class="form__input" type="text" name="POSTAL_CODE" id="POSTAL_CODE"
                        value="<?= $customer->getCustPostalCode() ?? $faker->postcode() ?>" maxlength="10">

                    <label class="form__label" for="STATE">State:</label>
                    <input class="form__input" type="text" name="STATE" id="STATE"
                        value="<?= $customer->getCustState() ?? null ?>" maxlength="20">

                    <label class="form__label" for="CITY">City:</label>
                    <input class="form__input" type="text" name="CITY" id="CITY"
                        value="<?= $customer->getCustCity() ?? null ?>" maxlength="20">

                    <label class="form__label" for="ADDRESS">Street adress:</label>
                    <input class="form__input" type="text" name="ADDRESS" id="ADDRESS"
                        value="<?= $customer->getCustStreetAddress() ?? $faker->address() ?>" maxlength="100" required>

                    <label class="form__label" for="COUNTRY">Country:</label>
                    <input class="form__input" type="text" name="COUNTRY" id="COUNTRY"
                        value="<?= $customer->getCustCountry() ?? null ?>" maxlength="20">

                    <label class="form__label" for="LANGUAGE">NLS language:</label>
                    <input class="form__input" type="text" name="LANGUAGE" id="LANGUAGE"
                        value="<?= $customer->getNlsLanguage() ?? null ?>" maxlength="3">

                    <label class="form__label" for="TERRITORY">NLS territory:</label>
                    <input class="form__input" type="text" name="TERRITORY" id="TERRITORY"
                        value="<?= $customer->getNlsTerritory() ?? null ?>" maxlength="30">

                    <label class="form__label" for="CREDIT">Credit Limit:</label>
                    <input type="number" id="CREDIT" name="CREDIT" step="0.1"
                        value="<?= $customer->getCreditLimit() != 0.00 ? $customer->getCreditLimit() : '0.00' ?>">

                    <label class="form__label" for="MGR_ID">Employee ID:</label>
                    <input class="form__input" type="number" name="MGR_ID" id="MGR_ID"
                        value="<?= $customer->getAccountMgrId() ?? null ?> ">

                    <!------------------------------------------------------------------------------>
                    <label class="form__label" for="latitude">Latitud:</label>
                    <input class="form__input" type="number" name="latitude" id="latitude"
                        placeholder="Ingresa la latitud: min:-90 max:90" step="0.000001" min="-90" max="90">

                    <label class="form__label" for="longitude">Longitud:</label>
                    <input class="form__input" type="number" name="longitude" id="longitude"
                        placeholder="Ingresa la longitud: min:-180 max:180" step="0.000001" min="-180" max="180">

                    <label class="form__label" for="LOCATION">Geo location:</label>
                    <input type="text" name="LOCATION" id="LOCATION"
                        value="<?php echo $customer->getCustGeoLocation(); ?>" readonly>

                    <script>
                        document.getElementById('latitude').addEventListener('input', updateLocation);
                        document.getElementById('longitude').addEventListener('input', updateLocation);

                        function updateLocation() {
                            const latitude = document.getElementById('latitude').value;
                            const longitude = document.getElementById('longitude').value;
                            document.getElementById('LOCATION').value = latitude && longitude ? `${latitude}, ${longitude}` : '';
                        }
                    </script>
                    <!------------------------------------------------------------------------------>

                    <label class="form__label" for="BIRTH">Date of birth:</label>
                    <input class="form__input" type="date" name="BIRTH" id="BIRTH"
                        value="<?= $customer->getDateOfBirth() ?? null ?>">

                    <label class="form__label" for="MARITAL_STATUS">Marital status:</label>
                    <select class="form__input" name="MARITAL_STATUS" id="MARITAL_STATUS">
                        <option value="single" <?= $customer->getMaritalStatus() == "single" ? 'selected' : '' ?>>Single
                        </option>
                        <option value="married" <?= $customer->getMaritalStatus() == "married" ? 'selected' : '' ?>>Married
                        </option>
                    </select>

                    <label class="form__label" for="GENDER">Gender:</label>
                    <select name="GENDER" id="GENDER">
                        <option value="M" <?= $customer->getGender() == "M" ? 'selected' : '' ?>>Male</option>
                        <option value="F" <?= $customer->getGender() == "F" ? 'selected' : '' ?>>Female</option>
                    </select>

                    <label class="form__label" for="INCOME_LEVEL">Income level:</label>
                    <input class="form__input" type="text" name="INCOME_LEVEL" id="INCOME_LEVEL" maxlength="20"
                        value=" <?= $customer->getIncomeLevel() ? $customer->getIncomeLevel() : null ?> ">


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