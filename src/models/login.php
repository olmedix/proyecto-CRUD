<?php

session_start();


//Usuario válido almacenado
$valid_user = 'user1';
$valid_password = ['12345678', '1'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Validación de credenciales
    if ($username === $valid_user && in_array($password, $valid_password)) {
        $_SESSION['username'] = $username;  // Inclou dades de l'usuari a '$_SESSION'
        $_SESSION['loggedin'] = true;
        $_SESSION['message'] = "¡Welcome, $username!";
        header("Location: ../../index.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: incorrect username or password.";
        header("Location: ../../index.php");
        exit();
    }

}