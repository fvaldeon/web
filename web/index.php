<!DOCTYPE html> 
<html lang="en">
<head>
    <title>Gestión de usuarios</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="plugins/http_maxcdn.bootstrapcdn.com_bootstrap_3.4.1_css_bootstrap.css"/>

    <script src="plugins/http_ajax.googleapis.com_ajax_libs_jquery_3.4.1_jquery.js"></script>
    <script src="plugins/http_maxcdn.bootstrapcdn.com_bootstrap_3.4.1_js_bootstrap.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
        }
        table.center{
            margin: auto;

        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid red;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even){background-color: #e6f7ff}
        tr:nth-child(odd){background-color: #b3e6ff}
        tr:hover{background-color: #e4b9c0}

        body {
            background-color: lightblue;
        }

    </style>
</head>
<body>

<div class="jumbotron text-center" style="text-align: center">
    <img class="img-circle" src="images/users.jpg" width="200" height="200" alt="Logo de la web">

    <h1 title="Proyecto de prueba">Gestión de usuarios</h1>
    <br>
    <p style="text-align:left;margin-left: 35px;margin-right: 20px">Proyecto creado para aprender a usar <b>PHP, HTML y CSS</b>. Conectamos con una base de datos y permitimos realizar las
        operaciones <i>CRUD</i>. Usamos un fichero php con los datos de conexion de la base de datos. Poco a poco iremos
        ampliando el diseño utilizando directivas <strong>CSS y HTML</strong>. </p>
    <p style="text-align:left;margin-left: 35px;margin-right: 20px">Esta web es responsive, creada con una plantilla de <b>BootStrap</b></p>
    <hr>


    <?php
        include("bbdd/db_conf.php");
        // Conectar al servidor local con mi usuario fer2
        if(!($iden = mysqli_connect(DB_HOST, DB_USUARIO, DB_PASSWORD, DB_NOMBRE)))
            die("Error: No se pudo conectar");

        // Sentencia SQL: muestra todo el contenido de la tabla "usuarios"
        $sentencia = "SELECT nif, nombre, apellidos, telefono, id_taquilla FROM usuarios";
        // Ejecuta la sentencia SQL
        $resultado = mysqli_query($iden, $sentencia );
        if(!$resultado)
            die("Error: no se pudo realizar la consulta");
        echo '<h3>Usuarios registrados</h3>';
        //Muestro todo el contenido en una tabla
        echo '<table class="center">';
        echo '<tr><th>NIF</th><th>nombre</th><th>apellidos</th><th>telefono</th><th>taquilla</th></tr>';
        while($fila = mysqli_fetch_assoc($resultado))
        {
            echo '<tr>';
            echo '<td>' . $fila['nif'] . '</td><td>' . $fila['nombre'] . '</td><td>' . $fila['apellidos'] . '</td><td>' . $fila['telefono'] . '</td><td>' . $fila['id_taquilla'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';



    ?>


</div>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <form action="modificado.php" method="post">
                <fieldset>
                    <legend>Nuevo usuario:</legend>
                    NIF:<br>
                    <input type="text" name="nif" ><br>
                    Nombre:<br>
                    <input type="text" name="nombre" ><br>
                    Apellidos:<br>
                    <input type="text" name="apellidos" ><br>
                    Telefono:<br>
                    <input type="text" name="telefono" ><br>
                    Taquilla:<br>
                    <input type="text" name="taquilla"><br><br>

                    <input type="submit" name="boton" value="Alta">
                </fieldset>
            </form>
            <br>
        </div>
        <div class="col-sm-4">
            <form action="modificado.php" method="post">
                <fieldset>
                    <legend>Eliminar usuario:</legend>
                    <?php

                    // Sentencia SQL: muestra todo el contenido de la tabla "usuarios"
                    $sentencia = "SELECT nif, nombre, apellidos From usuarios";
                    $resultado = mysqli_query($iden, $sentencia );

                    echo '<select name="usuarios">';
                    while($fila = mysqli_fetch_assoc($resultado)){
                        echo '<option value="'.$fila[nif].'">' . $fila[nif] . " : " . $fila[nombre] . " " . $fila[apellidos] . '</option>';
                    }

                    echo '</select>';
                    ?>

                    <input type="submit" name="boton" value="Eliminar">
                </fieldset>
            </form>
        </div>
        <div class="col-sm-4">
            <form action="modificado.php" method="post">
                <fieldset>
                    <legend>Modificar datos usuario:</legend>
                    Usuario a modificar: <br>
                    <?php

                    // Sentencia SQL: muestra todo el contenido de la tabla "usuarios"
                    $sentencia = "SELECT nif, nombre, apellidos From usuarios";
                    $resultado = mysqli_query($iden, $sentencia );



                    echo '<select name="usuarios">';
                    while($fila = mysqli_fetch_assoc($resultado)){
                        echo '<option value="'.$fila[nif].'">' . $fila[nif] . " : " . $fila[nombre] . " " . $fila[apellidos] . '</option>';
                    }

                    echo '</select>';

                    // Libera la memoria de los datos consultados
                    mysqli_free_result($resultado);

                    // Cierra la conexion con la base de datos
                    mysqli_close($iden);
                    ?>
                    <input type="submit" name="boton" value="Modificar">
                    <br>
                    nuevo NIF:<br>
                    <input type="text" name="nifnuevo" ><br>
                    nuevo Nombre:<br>
                    <input type="text" name="nombre" ><br>
                    nuevo Apellidos:<br>
                    <input type="text" name="apellidos" ><br>
                    nuevo Telefono:<br>
                    <input type="text" name="telefono" ><br>
                    nueva Taquilla:<br>
                    <input type="text" name="taquilla"><br><br>


                </fieldset>
            </form>
            <br>
        </div>
    </div>
</div>
<hr>
<div class="list-group" style="text-align: center">
    <a href="pruebas-html5.html" class="list-group-item" target="_blank">HTML5</a>
    <a href="pruebas-css.html" class="list-group-item" target="_blank">CSS3</a>
    <a href="pruebas-php.php" class="list-group-item" target="_blank">PHP7</a>
    <a href="pruebas-javascript.html" class="list-group-item" target="_blank">JavaScript</a>
</div>

<div style="text-align: center">
    <a style="font-family:Arial;color:darkred;font-size:140%;" href="https://github.com/fvaldeon/web" target="_blank"> (c) Fernando Valdeón</a>
</div>

<br><br>
</body>
</html>