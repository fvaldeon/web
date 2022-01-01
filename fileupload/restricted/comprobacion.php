<?php
session_start();

include("./conf/db_conf.php");

echo hola;
// Try and connect using the info above.
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    echo 'No se puede conectar al servidor MySQL: ' . mysqli_connect_error();
    //exit('No se puede conectar al servidor MySQL: ' . mysqli_connect_error());
}

?>