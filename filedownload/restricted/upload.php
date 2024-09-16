<?php
session_start();

include("./conf/db_conf.php");
include("./conf/filespath_conf.php");
include("./conf/funciones.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

$conn = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$message = '';

if (mysqli_connect_errno()) {
    $message = 'Error: No se puede conectar al servidor MySQL: ' . mysqli_connect_error();
} else {
    // Manejo de subida de archivos
    if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            if (!empty($_POST['codigoArchivo'])) {

                $codigoArchivo = strtolower($_POST['codigoArchivo']);

                $filename = sanear_nombre_fichero(basename($_FILES['file']['name']));
                $target_dir = DIRECTORIO_BASE_FILES;
                if (!file_exists(DIRECTORIO_BASE_FILES)) {
                    mkdir(DIRECTORIO_BASE_FILES, 0777, true);
                }

                $target_file = $target_dir . $filename;
                $fileTmpPath = $_FILES['file']['tmp_name'];

                if (is_uploaded_file($fileTmpPath) && move_uploaded_file($fileTmpPath, $target_file)) {
                    $sql = "INSERT INTO files (code, filename) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $codigoArchivo, $filename);
                    if ($stmt->execute()) {
                        $message = "Archivo subido correctamente.";
                    } else {
                        $message = "Error al subir el archivo.";
                    }
                    $stmt->close();
                    if (file_exists($fileTmpPath)) {
                        unlink($fileTmpPath);
                    }
                } else {
                    $message = "Error al mover el archivo subido.";
                }
            } else {
                $message = "Error: debe introducir un código";
            }
        } else {
            $message = 'Hay errores en el envío del fichero.<br>' .
                "Error interno (" . $_FILES['file']['error'] . "): " .
                mensajeErrorFiles($_FILES['file']['error']);
        }
    }
}

$conn->close();

$_SESSION['message-upload'] = $message;

// Redirigir de nuevo a panel.php
header("Location: panel.php");
exit;
?>
