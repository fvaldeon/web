<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

include("./conf/db_conf.php");

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if($stmt = $con->prepare("SELECT codigo, nombre, apellidos, curso FROM alumnos WHERE id = ?")) {
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($codigo, $nombre, $apellidos, $curso);
    $stmt->fetch();
    $stmt->close();

    echo "<td><input type='text' name='codigoAlumno' required autocomplete='off' value='" . $codigo . "' onClick='this.select()' placeholder='código'></td>";
    echo "<td><input type='text' name='nombreAlumno' required autocomplete='off' value='" . $nombre . "' onClick='this.select()' placeholder='nombre'></td>";
    echo "<td><input type='text' name='apellidosAlumno' autocomplete='off' value='" . $apellidos . "' onClick='this.select()' placeholder='apellidos'></td>";
    echo "<td><input type='text' name='cursoAlumno' required autocomplete='off' value='" . $curso . "' onClick='this.select()' placeholder='curso'></td>";
} else {
    echo "<td> Error: Fallo al obtener datos mediante AJAX";
}
$con->close();
?>