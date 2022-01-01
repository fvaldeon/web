<?php
include("./conf/upload_conf.php");
include("./conf/db_conf.php");
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}
//vuelvo a mostrar el listado de la entrega actual
if(isset($_SESSION['entrega-listada'])){
    unset($_SESSION['entrega-listada']);
}

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$message2 = '';
if (mysqli_connect_errno()) {
    $message2 = 'Failed to connect to MySQL: ' . mysqli_connect_error();
} else {
    //Si creo nueva entrega no realizo otros cambios
    if (isset($_POST['chk-nueva-entrega'])) {
        if(is_numeric($_POST['max_size'])) {
            //1º crear el directorio para las entregas
            $nombreDirectorio = preg_replace("([^a-zA-Z0-9._\-])", '', $_POST['nombre_entrega']);
            $rutaDirectorio = DIRECTORIO_BASE_UPLOADS . $nombreDirectorio . "/";
            if (mkdir($rutaDirectorio,0777, true)) {
                if ($stmt = $con->prepare('CALL nueva_entrega(?, ?, ?)')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt->bind_param('ssd', $_POST['nombre_entrega'], $rutaDirectorio, $_POST['max_size']);
                    $stmt->execute();
                    $stmt->close();

                    $message2 = "Entrega creada: " . $_POST['nombre_entrega'];
                } else {
                    $message2 = "Error: entrega no creada en base de datos." .
                        "<br/>" . $con->error;
                    if (file_exists($rutaDirectorio)) {
                        rmdir($rutaDirectorio);
                    }
                }
            } else {
                $message2 = "Error: No se pudo crear el directorio.";
            }
        } else {
            $message2 = "Error: tamaño de fichero nueva entrega no numérico.";
        }
    } else {
        //Otras modificaciones

        //Envios activados
        isset($_POST['chk-envio']) ? $enviosActivados = 1 : $enviosActivados = 0;
        if ($stmt = $con->prepare('CALL set_envios_activados(?)')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('i', $enviosActivados);
            $stmt->execute();
            $stmt->close();

            $message2 = "Configuración: " . ($enviosActivados == 1 ? "Envíos activados" : "Envíos desactivados");
        } else {
            $message2 = "Error: activar/desactivar envios. " . $con->error;
        }

        //Control de envio unico por entrega y alumnos
        isset($_POST['chk-envio-unico']) ? $envioUnicoActivado = 1 : $envioUnicoActivado = 0;
        if ($stmt = $con->prepare('CALL set_entrega_unica(?)')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('i', $envioUnicoActivado);
            $stmt->execute();
            $stmt->close();

            $message2 = $message2 . " - " . ($envioUnicoActivado == 1 ? "Envío único activado" : "Envío único desactivado");
        } else {
            $message2 = $message2 . " - Error: envío único. " . $con->error;
        }
        //Control de codigo de alumnos
        isset($_POST['chk-control-alumno']) ? $controlAlumnoActivado = 1 : $controlAlumnoActivado = 0;
        if ($stmt = $con->prepare('CALL set_control_alumnos(?)')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('i', $controlAlumnoActivado);
            $stmt->execute();
            $stmt->close();

            $message2 = $message2 . " - " . ($controlAlumnoActivado == 1 ? "Control Alumno activado" : "Control Alumno desactivado");
        } else {
            $message2 = $message2 . " - Error: control alumnos. " . $con->error;
        }

        //Si está activado el cbox de extensiones, las modifico
        if (isset($_POST['chk-extensiones'])) {
            if (count(explode(",", $_POST['lista-extensiones'])) < 2) {
                $_POST['lista-extensiones'] = "txt,pdf";
            }
            if ($stmt = $con->prepare('CALL set_extensiones_permitidas(?)')) {
                // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                $stmt->bind_param('s', $_POST['lista-extensiones']);
                $stmt->execute();
                $stmt->close();

                $message2 = $message2 . " - Extensiones permitidas actualizadas.";
            } else {
                $message2 = $message2 . " - Error: actualizar extensiones. " . $con->error;
            }
        }

        //Modificar tamano fichero entrega actual
        if (isset($_POST['chk-tamano-archivo'])) {
            if (is_numeric($_POST['max-size-actual'])) {
                if ($stmt = $con->prepare('CALL set_maxsize_entrega_actual(?)')) {
                    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                    $stmt->bind_param('d', $_POST['max-size-actual']);
                    $stmt->execute();
                    $stmt->close();

                    $message2 = $message2 . " - Tamaño fichero actualizado.";
                } else {
                    $message2 = $message2 . " - Error: actualizar tamño fichero. " . $con->error;
                }
            } else {
                $message2 = $message2 . " - Error: tamaño de fichero no es numérico.";
            }
        }
    }
    $con->close();
}

$_SESSION['message2'] = $message2;
header("Location: configuracion.php");
