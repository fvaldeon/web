<html>
<head>
    <title>Datos Modificados</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="plugins/modificado.css"/>


</head>
<body>

<?php
    include("bbdd/db_conf.php");
    // Conectar al servidor local con mi usuario root
    $conexion = new mysqli(DB_HOST, DB_USUARIO, DB_PASSWORD, DB_NOMBRE);
    if($conexion->connect_error) {
        die("Error: No se pudo conectar");
    }

    if($_POST["boton"] == "Alta"){
        $sql = "INSERT INTO usuarios (nif ,nombre, apellidos, telefono, id_taquilla) 
           VALUES (?, ?, ?, ?, ?)";
	   
        $nif = $_POST["nif"];
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $telefono = $_POST["telefono"];
        $taquilla = $_POST["taquilla"];

        $sentencia = $conexion->prepare($sql);
        $sentencia->bind_param('ssssi', $nif, $nombre, $apellidos, $telefono, $taquilla);

        if($sentencia->execute()){
            echo "Se ha insertado el usuario: " . $nif . " ". $nombre . " " . $apellidos . " " . $telefono . " " . $taquilla;
        } else {
            echo "Error: No se ha podido insertar el usuario con dni: ";
        }

        $sentencia->close();
        $conexion->close();

    } else if($_POST["boton"] == "Eliminar"){

        $nif = $_POST["usuarios"];

        $sql = "DELETE FROM usuarios WHERE nif = ?";

        $sentencia = $conexion->prepare($sql);
        $sentencia->bind_param('s', $nif);

        if($sentencia->execute()){
            echo "Se ha eliminado al usuario con dni: " . $nif;
        } else {
            echo "Error: No se ha podido eliminar al usuario con dni: " . $nif;
        }

        $sentencia->close();
        $conexion->close();
    } else if($_POST["boton"] == "Modificar"){

        //Obtengo el id para hacer el update
        $nifViejo = $_POST["usuarios"];
        $sql = "SELECT id FROM usuarios WHERE nif = '$nifViejo' limit 1";
        $resultado = $conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        $id = $datos["id"];

        $nifnuevo = $_POST["nifnuevo"];
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $telefono = $_POST["telefono"];
        $taquilla = $_POST["taquilla"];


        $sql = "UPDATE usuarios SET nif = ?, nombre = ?, apellidos = ?, telefono = ?, 
        id_taquilla = ? WHERE id = ?";

        $sentencia = $conexion->prepare($sql);
        $sentencia->bind_param('ssssii', $nifnuevo, $nombre, $apellidos, $telefono, $taquilla, $id);


        if($sentencia->execute()){
            echo "Usuario actualizado correctamente";
        } else{
            echo "Error: usuario no actualizado";
        }

        $resultado->free_result();
        $conexion->close();

    }

?>

    <form action="index.php">
    <br><br><br><br><br><br><br>
    Volver a la página principal
    <br><br><br>
        <input type="submit" value="Volver">
      </fieldset>
    </form>


</body>
</html> 
