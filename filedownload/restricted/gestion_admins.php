<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

include("./conf/db_conf.php");
include("./conf/funciones.php");

$message1 = '';
$message2 = '';
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

if (mysqli_connect_errno()) {
    $message = 'Failed to connect to MySQL: ' . mysqli_connect_error();
} else {
    //Nuevo admin
    if (isset($_POST['nuevo-admin']) && $_POST['chk-nuevo-admin'] == 1) {
        if ($stmt = $con->prepare("CALL nuevo_admin(?, ?)")) {
            $pass = hashear($_POST['password-admin']);
            $stmt->bind_param("ss", $_POST['login-admin'], $pass);
            $stmt->execute();
            $stmt->close();
            $message1 = "Admin creado";
        } else {
            $message1 = "Error: nuevo admin";
        }
        //Borrar admin
    } else if (isset($_POST['borrar-admin']) && $_POST['chk-borrar-admin'] == 1) {
        if ($stmt = $con->prepare("CALL borrar_admin(?)")) {
            $stmt->bind_param("i", $_POST['admin-seleccionado']);
            $stmt->execute();
            $stmt->close();
            $message2 = "Admin borrado.";
        } else {
            $message2 = "Error: borrar admin";
        }
    }
    $con->close();
}

$_SESSION['message1'] = $message1;
$_SESSION['message2'] = $message2;
header("Location: panel.php");
?>

