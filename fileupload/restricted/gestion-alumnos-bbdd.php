<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

include("./conf/db_conf.php");
include("./conf/funciones.php");

$message5 = '';
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

if (mysqli_connect_errno()) {
    $message5 = 'Failed to connect to MySQL: ' . mysqli_connect_error();
} else {
    //Alta nuevo alumno
    if (isset($_POST['nuevo-alumno']) && $_POST['chk-nuevo-alumno'] == 1) {
        if ($stmt = $con->prepare(" CALL nuevo_alumno(?, ?, ?, ?)")) {
            $stmt->bind_param("ssss", $_POST['codigoAlumno'], $_POST['nombreAlumno'],
                                            $_POST['apellidosAlumno'], $_POST['cursoAlumno']);
            $stmt->execute();
            $stmt->close();
        } else {
            $message5 = "Error: nuevo alumno";
        }
        //Alta nuevo admin
    } else if (isset($_POST['nuevo-admin']) && $_POST['chk-nuevo-admin'] == 1) {
        if ($stmt = $con->prepare("CALL nuevo_admin(?, ?)")) {
            $pass = hashear($_POST['password-admin']);
            $stmt->bind_param("ss", $_POST['login-admin'], $pass);
            $stmt->execute();
            $stmt->close();
        } else {
            $message5 = "Error: nuevo admin";
        }
        //Borrar admin
    } else if (isset($_POST['borrar-admin']) && $_POST['chk-borrar-admin'] == 1) {
        if ($stmt = $con->prepare("CALL borrar_admin(?)")) {
            $stmt->bind_param("i", $_POST['admin-seleccionado']);
            $stmt->execute();
            $stmt->close();
        } else {
            $message5 = "Error: borrar admin";
        }
        //Modificar datos alumno
    } else if (isset($_POST['modificar-alumno']) && $_POST['chk-datos-alumno'] == 1) {
        if ($stmt = $con->prepare('CALL modificar_alumno(?, ?, ?, ?, ?)')) {
            $stmt->bind_param("ssssi", $_POST['codigoAlumno'], $_POST['nombreAlumno'],
                $_POST['apellidosAlumno'], $_POST['cursoAlumno'], $_POST['alumno_seleccionado']);
            $stmt->execute();
            $stmt->close();
        } else {
            $message5 = "Error: actualizar datos alumno. " . $con->error;
        }
        //Eliminar alumnos seleccionados
    } else if (isset($_POST['seleccionar']) && isset($_POST['eliminar-alumnos'])) {
        //Obtengo arrays con los id's de checkbox seleccionados
        $checks = implode("','", $_POST['seleccionar']);
        if (!$con->query("DELETE FROM alumnos WHERE id IN ('$checks')")) {
            $message5 = "Error: Fallo al borrar alumnos. " .
                "<br/>" . $con->error;
        }
        //Modificar codigos
    } else if (isset($_POST['modificar-codigos']) && $_POST['chk-codigos'] == 1) {
        //Debo comprobar si es para todos o para un curso solamente
        //Debo llamar a execute() tantas veces como alumnos deba actualizar
        if ($_POST['curso-codigos'] === 'todos') {
            $ok = ($stmt = $con->prepare('SELECT id FROM alumnos'));
        } else {
            $ok = ($stmt = $con->prepare('SELECT id FROM alumnos WHERE curso = ?'));
            if ($ok) {
                $stmt->bind_param("s", $_POST['curso-codigos']);
            }
        }
        if ($ok) {
            $stmt->execute();
            $resultados = $stmt->store_result();

            if ($stmt2 = $con->prepare("UPDATE alumnos SET codigo = ? WHERE id = ?")) {

                $contador = $_POST['inicio'];
                $paso = $_POST['paso'];
                $prefijo = strtolower($_POST['prefijo']);

                $stmt->bind_result($id);
                while ($stmt->fetch()) {

                    $codigoGenerado = $prefijo . $contador;
                    $stmt2->bind_param("si", $codigoGenerado, $id);
                    $stmt2->execute();
                    $contador += $paso;
                }
                $stmt->close();
                $stmt2->close();
            } else {
                $message5 = "Error al actualizar códigos: " . $con->error;
            }
        } else {
            $message5 = "Error al obtener alumnos para actualizar codigo: " . $con->error;
        }
        //Importar fichero alumnos
    } else if (isset($_FILES['fichero']) && $_FILES['fichero']['error'] === UPLOAD_ERR_OK
        && $_POST['chk-importar-fichero'] == 1 && isset($_POST['curso'])) {

        $fileTmpPath = $_FILES['fichero']['tmp_name'];
        $curso = str_replace(' ', '', strtolower($_POST['curso']));

        if (strlen($curso) > 2) {
            $prefijoCodigo = substr($curso, 0, 2);
            $contador = random_int(100, 200);
            $paso = 7;

            $lector = fopen($fileTmpPath, "r");
            if ($lector) {
                //Preparo la consulta de alta de alumnos
                if ($stmt = $con->prepare("CALL nuevo_alumno(?, ?, ?, ?)")) {

                    //bucle lectura fichero
                    while (($line = fgets($lector)) !== false) {
                        $campos = explode(",", $line);
                        if (count($campos) == 2) {
                            $nombre = $campos[0];
                            $apellidos = $campos[1];
                            $codigo = $prefijoCodigo . $contador;
                            $contador += $paso;
                            if (strlen($nombre) > 2 && strlen($apellidos) > 2) {
                                $stmt->bind_param("ssss", $codigo, $nombre, $apellidos, $curso);
                                $stmt->execute();
                            }
                        }
                    }
                    $stmt->close();
                } else {
                    $message5 = "Error: No se pueden guardar los alumnos en la bbdd. " . $con->error;
                }
                fclose($lector);
            } else {
                $message5 = "Error al leer el fichero";
            }
        } else {
            $message5 = "Error: el nombre de curso debe tener al menos 3 caracteres del abecedario.";
        }
        //Si el fichero existe, al terminar se borra
        if (file_exists($fileTmpPath)) {
            unlink($fileTmpPath);
        }
    } else {
        $message5 = "No se ha seleccionado ninguna acción";
    }
    $con->close();
}

echo $message5;
$_SESSION['message5'] = $message5;
header("Location: alumnos-usuarios.php");
?>