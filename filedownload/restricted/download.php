<?php
session_start();

include("./conf/db_conf.php");
include("./conf/filespath_conf.php");
include("./conf/funciones.php");

$conn = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$message = '';

if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    $message = 'Error: No se puede conectar al servidor MySQL: ' . mysqli_connect_error();
    //echo $message;
} else if (empty($_POST['codigoArchivo'])) {
    $message = "Error: Se debe introducir un código";
    echo $message;
} else {
    //Control descarga
    if (isset($_POST['downloadBtn']) && $_POST['downloadBtn'] == 'Download') {

        $codigoArchivo = $_POST['codigoArchivo'];
        $sql = "SELECT filename FROM files WHERE code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $codigoArchivo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($filename);
            $stmt->fetch();
            $stmt->close();

            if ($filename) {
                $filepath = DIRECTORIO_BASE_FILES . $filename;
                //$_SESSION['descargado'] = 'YES';
                descargarFichero($filepath);

            } else {
                $message = "Error: ruta de fichero incorrecta.";
                echo $message . " :" . $filename;
            }
        } else {
            $message = "Error: Código de archivo incorrecto.";
            echo $message;
        }
    }
}
$conn->close();
$_SESSION['message'] = $message;
header("Location: ../index.php");
?>
