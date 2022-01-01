<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

//Borrar variable de Seccion Gestión Alumnos
if(isset($_SESSION['curso_seleccionado'])){
    unset($_SESSION['curso_seleccionado']);
}

include("./conf/db_conf.php");

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
} else {

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Envíos Eliminados</title>
    <link rel="stylesheet" href="../css_styles/configuracion_style.css" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Página de Configuracion</h1>
        <a href="configuracion.php"><i class="fas fa-tools"></i>Configuración de Entregas</a>
        <a href="alumnos-usuarios.php"><i class="fas fa-users"></i>Gestión de Alumnos</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Mostrar envíos eliminados</h2>
    <div>
        <form method="POST">
            <table>
                <tr>
                    <td>Seleccionar entrega:</td>
                    <td>
                        <select name="entrega_seleccionada" onchange="this.form.submit()">
                            <option value="nula"></option>
                            <?php
                            if ($stmt = $con->prepare('SELECT DISTINCT(nombre_entrega) FROM uploads_eliminados')) {
                                $stmt->execute();
                                $stmt->bind_result($opcion);

                                while ($stmt->fetch()) {
                                    echo '<option value="' . $opcion . '">' . $opcion . '</option>';
                                }
                                $stmt->close();
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="submit" value="listar" hidden>
        </form>
    </div>
</div>
    <?php
    if (isset($_POST['entrega_seleccionada'])) {
        if ($stmt = $con->prepare('SELECT CASE WHEN alumno IS NULL THEN codigo_alumno ELSE alumno END,
                                                nombre_fichero, fecha_envio, fecha_borrado
                                            FROM uploads_eliminados WHERE nombre_entrega = ?')) {

            $stmt->bind_param("s", $_POST['entrega_seleccionada']);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($alumno, $fichero, $fecha, $fechaBorrado);

                echo "<div class=\"content\"><div><table><tr><td>Entrega:</td>";
                echo "<td>" . $_POST['entrega_seleccionada'] . "</td></tr></table><br/>";

                echo "<table class='tabla_resultado'>";
                echo "<tr><th>Alumno</th><th>Fichero</th>";
                echo "<th>Envío</th><th>Borrado</th></tr>";

                while ($stmt->fetch()) {
                    echo "<tr><td>" . $alumno . "</td><td>" . $fichero . "</td>";
                    echo "<td>" . $fecha . "</td><td>" . $fechaBorrado . "</td></tr>";
                }
                echo "</table></div></div>";
            }

            $stmt->close();
        } else {
            echo $con->error;
        }
    }
    $con->close();
}
?>
</body>
</html>
