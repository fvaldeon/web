<!DOCTYPE html>
<html>

<head>
    <title>Pruebas php</title>
    <meta charset="UTF-8"/>
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="stylesheet" href="css/pruebas-php.css" />
</head>


<body>
    <?php
        include ("bbdd/db_conf.php");
        $conexion = new mysqli(DB_HOST,DB_USUARIO, DB_PASSWORD, DB_NOMBRE);
        if(mysqli_connect_errno()){
            printf("Error de conexion: %s\n", mysqli_connect_error());
        }
        if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["nombre"])) {

            $sql = "INSERT INTO gimnasios(nombre, mixto, fecha_creacion) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $mixto = isset($_POST["mixto"]);
            $stmt->bind_param('sss', $_POST["nombre"], $mixto, $_POST["fecha"]);
            $stmt->execute();
            $stmt->close();
            unset($_POST["nombre"]);
        }
    ?>
    <img class="imagen-centrada" src="images/users.jpg">

    <div>
        <div style="float: left;width: 50%">
            <h2>Usuarios</h2>
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
                $resultado->free_result();
                ?>

            </table>
        </div>
        <div style="float: right;width: 50%;">
            <h2>Gimnasios</h2>
            <table class="centrada">

                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Mixto</th>
                    <th>Fecha Apertura</th>
                </tr>
                <?php
                $sql = "SELECT * FROM gimnasios";
                $resultado = $conexion->query($sql);
                while($fila = $resultado->fetch_row()){
                    echo '<tr>';
                    echo '<td>' . $fila[0] . '</td>';
                    echo '<td>' . $fila[1] . '</td>';
                    echo '<td>';
                    if($fila[2] == '0')
                        echo '<input type="checkbox" disabled/>';
                    //echo 'no';
                    else
                        echo '<input type="checkbox" checked disabled/>';
                    //echo 'si';
                    echo '</td>';
                    $date = strtotime($fila[3]);
                    echo '<td>' . date("d-m-Y" , $date) . '</td>';
                    echo '</tr>';
                }
                $resultado->free_result();
                ?>
            </table>
        </div>
    </div>


    <hr>
    <div >
        <h2> Insertar Gimnasio</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>#gimnasios" method="post">
            <fieldset>
                <legend>Insertar Gimnasio</legend>
                Nombre <input type="text" name="nombre" required/><br>
                Mixto <input type="checkbox" name="mixto" /><br>
                Fecha Creación <input type="date" name="fecha" required><br>
                <input type="submit" value="Insertar" name="boton">
            </fieldset>
        </form>

    </div>
    <hr>
    <h2>Modificar gimnasio</h2>
    <hr>
    <iframe src="https://player.vimeo.com/video/365250686" width="640" height="385"
            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>

    <div class="list-group" style="text-align: center">
        <a href="pruebas-html5.html" class="list-group-item" target="_blank">HTML5</a>
        <a href="pruebas-css.html" class="list-group-item" target="_blank">CSS3</a>
        <a href="pruebas-php.php" class="list-group-item" target="_blank">PHP7</a>
        <a href="pruebas-javascript.html" class="list-group-item" target="_blank">JavaScript</a>
    </div>
</body>

</html>


