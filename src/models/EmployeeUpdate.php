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
            <h3>Employee</h3>

            <!----------------------------------------------------------------------->
            <?php
            require '../../vendor/autoload.php';

            use models\Employee;
            use Faker\Factory as FakerFactory;

            $faker = FakerFactory::create();


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
                    echo "<script>alert('No se pudo encontrar el empleado.');</script>";
                }
            } else {
                // Obtener el último id de la base de datos y asignar el nuevo id ( +1)
                $lastEmployeeId = Employee::getLastEmployeeId();
                $lastEmployeeId += 1;
                $employee = new Employee($lastEmployeeId);
            }

            ?>

            <form method="post" class="form">
                <fieldset>
                    <legend>Employee Information</legend>

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
                        <option value="AD_VP" <?= $employee->getJobId() === 'AD_VP' ? 'selected' : '' ?>>AD_VP</option>
                        <option value="FI_ACCOUNT" <?= $employee->getJobId() === 'FI_ACCOUNT' ? 'selected' : '' ?>
                            >FI_ACCOUNT</option>
                        <option value="FI_MGR" <?= $employee->getJobId() === 'FI_MGR' ? 'selected' : '' ?>>FI_MGR</option>
                        <option value="HR_REP" <?= $employee->getJobId() === 'HR_REP' ? 'selected' : '' ?>>HR_REP</option>
                        <option value="IT_PROG" <?= $employee->getJobId() === 'IT_PROG' ? 'selected' : '' ?>>IT_PROG
                        </option>
                        <option value="MK_MAN" <?= $employee->getJobId() === 'MK_MAN' ? 'selected' : '' ?>>MK_MAN</option>
                        <option value="MK_REP" <?= $employee->getJobId() === 'MK_REP' ? 'selected' : '' ?>>MK_REP</option>
                        <option value="PR_REP" <?= $employee->getJobId() === 'PR_REP' ? 'selected' : '' ?>>PR_REP</option>
                        <option value="PU_CLERK" <?= $employee->getJobId() === 'PU_CLERK' ? 'selected' : '' ?>>PU_CLERK
                        </option>
                        <option value="PU_MAN" <?= $employee->getJobId() === 'PU_MAN' ? 'selected' : '' ?>>PU_MAN</option>
                        <option value="SA_MAN" <?= $employee->getJobId() === 'SA_MAN' ? 'selected' : '' ?>>SA_MAN</option>
                        <option value="SA_REP" <?= $employee->getJobId() === 'SA_REP' ? 'selected' : '' ?>>SA_REP</option>
                        <option value="SH_CLERK" <?= $employee->getJobId() === 'SH_CLERK' ? 'selected' : '' ?>>SH_CLERK
                        </option>
                        <option value="ST_CLERK" <?= $employee->getJobId() === 'ST_CLERK' ? 'selected' : '' ?>>ST_CLERK
                        </option>
                        <option value="ST_MAN" <?= $employee->getJobId() === 'ST_MAN' ? 'selected' : '' ?>>ST_MAN</option>
                    </select>

                    <label class="form__label" for="email">Email:</label>
                    <input class="form__input" type="email" name="email" id="email"
                        value="<?= $employee->getEmail() ?? null ?>" maxlength="25">

                    <label class="form__label" for="phone_number">Phone Number:</label>
                    <input class="form__input" type="text" name="phone_number" id="phone_number"
                        value="<?= $employee->getPhoneNumber() ?? null ?>" maxlength="20">

                    <label class="form__label" for="hire_date">Hire Date:</label>
                    <input class="form__input" type="date" name="hire_date" id="hire_date"
                        value="<?= $employee->getHireDate() ?? null ?>">

                    <label class="form__label" for="salary">Salary:</label>
                    <input class="form__input" type="text" name="salary" id="salary"
                        value="<?= $employee->getSalary() ?? null ?>" pattern="^\d{1,6}(\.\d{1,2})?$"
                        title="Introduce un número de hasta 2 decimales.">

                    <label class="form__label" for="commission_pct">Commission Percentage:</label>
                    <input class="form__input" type="number" step="0.01" name="commission_pct" id="commission_pct"
                        value="<?= $employee->getCommissionPct() ?? null ?>" pattern="^\d{1,2}(\.\d{1,2})?$"
                        title="Introduce un número de hasta 2 dígitos enteros y hasta 2 decimales.">


                    <label class="form__label" for="manager_id">Manager ID:</label>
                    <select class="form__input" name="manager_id" id="manager_id">
                        <option value="">No Manager</option>
                        <option value="100" <?= $employee->getManagerId() == 100 ? 'selected' : '' ?>>King Steven</option>
                        <option value="101" <?= $employee->getManagerId() == 101 ? 'selected' : '' ?>>Kochhar Neena
                        </option>
                        <option value="102" <?= $employee->getManagerId() == 102 ? 'selected' : '' ?>>De Haan Lex</option>
                        <option value="103" <?= $employee->getManagerId() == 103 ? 'selected' : '' ?>>Hunold Alexander
                        </option>
                        <option value="108" <?= $employee->getManagerId() == 108 ? 'selected' : '' ?>>Greenberg Nancy
                        </option>
                        <option value="114" <?= $employee->getManagerId() == 114 ? 'selected' : '' ?>>Raphaely Den</option>
                        <option value="120" <?= $employee->getManagerId() == 120 ? 'selected' : '' ?>>Weiss Matthew
                        </option>
                        <option value="121" <?= $employee->getManagerId() == 121 ? 'selected' : '' ?>>Fripp Adam</option>
                        <option value="122" <?= $employee->getManagerId() == 122 ? 'selected' : '' ?>>Kaufling Payam
                        </option>
                        <option value="123" <?= $employee->getManagerId() == 123 ? 'selected' : '' ?>>Vollman Shanta
                        </option>
                        <option value="124" <?= $employee->getManagerId() == 124 ? 'selected' : '' ?>>Mourgos Kevin
                        </option>
                        <option value="145" <?= $employee->getManagerId() == 145 ? 'selected' : '' ?>>Russell John</option>
                        <option value="146" <?= $employee->getManagerId() == 146 ? 'selected' : '' ?>>Partners Karen
                        </option>
                        <option value="147" <?= $employee->getManagerId() == 147 ? 'selected' : '' ?>>Errazuriz Alberto
                        </option>
                        <option value="148" <?= $employee->getManagerId() == 148 ? 'selected' : '' ?>>Cambrault Gerald
                        </option>
                        <option value="149" <?= $employee->getManagerId() == 149 ? 'selected' : '' ?>>Zlotkey Eleni
                        </option>
                        <option value="201" <?= $employee->getManagerId() == 201 ? 'selected' : '' ?>>Hartstein Michael
                        </option>
                        <option value="205" <?= $employee->getManagerId() == 205 ? 'selected' : '' ?>>Higgins Shelley
                        </option>
                    </select>



                    <label class="form__label" for="department_id">Department ID:</label>
                    <select class="form__input" name="department_id" id="department_id">
                        <option value="10" <?= $employee->getDepartmentId() == 10 ? 'selected' : '' ?>>Administration
                        </option>
                        <option value="20" <?= $employee->getDepartmentId() == 20 ? 'selected' : '' ?>>Marketing</option>
                        <option value="30" <?= $employee->getDepartmentId() == 30 ? 'selected' : '' ?>>Purchasing</option>
                        <option value="40" <?= $employee->getDepartmentId() == 40 ? 'selected' : '' ?>>Human Resources
                        </option>
                        <option value="50" <?= $employee->getDepartmentId() == 50 ? 'selected' : '' ?>>Shipping</option>
                        <option value="60" <?= $employee->getDepartmentId() == 60 ? 'selected' : '' ?>>IT</option>
                        <option value="70" <?= $employee->getDepartmentId() == 70 ? 'selected' : '' ?>>Public Relations
                        </option>
                        <option value="80" <?= $employee->getDepartmentId() == 80 ? 'selected' : '' ?>>Sales</option>
                        <option value="90" <?= $employee->getDepartmentId() == 90 ? 'selected' : '' ?>>Executive</option>
                        <option value="100" <?= $employee->getDepartmentId() == 100 ? 'selected' : '' ?>>Finance</option>
                        <option value="110" <?= $employee->getDepartmentId() == 110 ? 'selected' : '' ?>>Accounting
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