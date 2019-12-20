<!DOCTYPE html>
<html>

<head>
    <title>Pruebas php</title>
    <meta charset="UTF-8"/>
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="stylesheet" href="plugins/pruebas-php.css" />
</head>


<body>
    <img class="imagen-centrada" src="images/users.jpg">
    <h2 style="text-align: center">Gimnasios</h2>
    <table class="centrada">
        <tr>
            <th>ID</th>
            <th>NIF</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Telefono</th>
            <th>Taquilla</th>
        </tr>
        <?php
            include ("bbdd/db_conf.php");
            $conexion = new mysqli(DB_HOST,DB_USUARIO, DB_PASSWORD, DB_NOMBRE);
            if(mysqli_connect_errno()){
                printf("Error de conexion: %s\n", mysqli_connect_error());
            }
            $sql = "SELECT * FROM usuarios";

            $resultado = $conexion->query($sql);

            while($fila = $resultado->fetch_assoc()){
                echo '<tr>';
                echo '<td>' . $fila['id'] . '</td>';
                echo '<td>' . $fila['nif'] . '</td>';
                echo '<td>' . $fila['nombre'] . '</td>';
                echo '<td>' . $fila['apellidos'] . '</td>';
                echo '<td>' . $fila['telefono'] . '</td>';
                echo '<td>' . $fila['id_taquilla'] . '</td>';
                echo '</tr>';
            }
        ?>
    </table>

    <hr>

    <hr>
    <iframe src="https://player.vimeo.com/video/365250686" width="640" height="385"
            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>


</body>

</html>


