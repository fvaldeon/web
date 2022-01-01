<?php
session_start();
include("./conf/upload_conf.php");
include("./conf/db_conf.php");
include ("./conf/funciones.php");

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}
$message3 = '';

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

if (mysqli_connect_errno()) {
    $message3 = 'Error de conexión a MySQL: ' . mysqli_connect_error();
} else {
    //Seccion eliminar entrega
    //compruebo que se ha pulsado el boton y que hay una entrega listada
    if (isset($_POST['eliminarEntrega']) && !empty($_POST['nombre_entrega'])) {
        if ($stmt = $con->prepare('CALL eliminar_entrega(?)')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('s', $_POST['nombre_entrega']);
            $stmt->execute();
            $stmt->close();

            //Elimino fichero y directorios
            array_map('unlink', array_filter((array)array_merge(
                glob($_POST['ruta_entrega'] . "*") ? : [])));
            if(file_exists($_POST['ruta_entrega'])){
                rmdir($_POST['ruta_entrega']);
            }

            $message3 = "Entrega eliminada: " . $_POST['nombre_entrega'];

            //Elimino el valor del listado actual
            if (isset($_SESSION['entrega-listada'])) {
                unset($_SESSION['entrega-listada']);
            }
        } else {
            $message3 = "Error: No se puede eliminar la entrega en la bbdd. " .
            "<br/>" . $con->error;
        }
    } else {
        //Si no he seleccionado nada, vuelvo
        if (isset($_POST['seleccionar'])) {
            //Obtengo arrays con los id's de checkbox seleccionados
            $checks = implode("','", $_POST['seleccionar']);

            if ($result = $con->query("SELECT nombre_fichero, ruta_directorio
                                    FROM uploads u, entregas e 
                                    WHERE u.id_entrega = e.id AND u.id IN ('$checks')")) {
                if ($result->num_rows > 0) {
                    //Seccion descargar
                    if (isset($_POST['descargarEnvios'])) {
                        //Si se ha seleccionado un solo fichero se dercarga directamente
                        if ($result->num_rows == 1) {
                            $row = $result->fetch_assoc();
                            $fichero = $row['ruta_directorio'] . $row['nombre_fichero'];

                            $con->close();
                            descargarFichero($fichero);
                        } else {
                            $zip = new ZipArchive();
                            $rutaZip = DIRECTORIO_BASE_UPLOADS ."descarga" . date("d-m-Y_H-i-s") . ".zip";
                            $zip->open($rutaZip, ZipArchive::CREATE);

                            while ($row = $result->fetch_assoc()) {
                                $rutaFichero = $row['ruta_directorio'] . $row['nombre_fichero'];
                                $nombreFichero = $row['nombre_fichero'];
                                $zip->addFile($rutaFichero, $nombreFichero);
                            }
                            $zip->close();
                            $con->close();
                            descargarFichero($rutaZip);
                        }
                    //Seccion eliminar envios
                    } else if (isset($_POST['eliminarEnvios'])) {
                        //Borro en base de datos
                        if ($con->query("DELETE FROM uploads WHERE id IN ('$checks')") === TRUE) {
                            while ($row = $result->fetch_assoc()) {
                                $rutaFichero = $row['ruta_directorio'] . $row['nombre_fichero'];
                                if(file_exists($rutaFichero)) {
                                    unlink($rutaFichero);
                                }
                            }
                            $message3 = "Entregas eliminadas.";
                        } else {
                            $message3 = "Error: Fallo al borrar en la base de datos. " .
                                "<br/>" . $con->error;
                        }
                    }
                }else{
                    $message3 = "Error: Se han obtenido 0 elementos de la bbdd.";
                }
            } else {
                $message3 = "Error al obtener elementos de la bbdd. " .
                    "<br/>" . $con->error;
            }
        } else {
            $message3 = "No se ha seleccionado elementos";
        }
    }
    $con->close();
}
$_SESSION['message3'] = $message3;
header("Location: configuracion.php");


?>
