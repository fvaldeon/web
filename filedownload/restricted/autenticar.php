<?php
session_start();
include("./conf/db_conf.php");

$conn = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$message = '';

if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    $message = 'No se puede conectar al servidor MySQL: ' . mysqli_connect_error();
    //exit('No se puede conectar al servidor MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    $message = 'Debes indicar login y password';
    //exit('Debes indicar login y password');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $conn->prepare('SELECT id, password FROM admins WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // La pass obtenida de la bbdd debe estar hasheada. Se puede hashear con ./restricted/funciones.php
        if (password_verify($_POST['password'], $password)) {
            //Sin control de contrasenas
            //if ($_POST['password'] === $password) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $stmt->close();
            $conn->close();

            if (isset($_SESSION['message-login'])){
                unset($_SESSION['message-login']);
            }
            header('Location: panel.php');
            exit;
        } else {
            // Incorrect password
            //echo 'Login y/o password incorrectos';
            $stmt->close();
            $conn->close();
            $message = 'Login y/o password incorrectos';
        }
    } else {
        // Incorrect username
        //echo 'Login y/o password incorrectos';
        $message = 'Usuario o contraseña incorrecto';
    }

    $conn->close();
}

$stmt->close();

$_SESSION['message-login'] = $message;
header('Location: login.php');
exit;
?>

