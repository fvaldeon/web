<?php
session_start();

include("./conf/db_conf.php");
include("./conf/filespath_conf.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

$conn = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$message = '';

// Manejo de eliminación de archivos
if ($_POST['deleteBtn'] && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Obtener el nombre del archivo antes de eliminar el registro de la base de datos
    $sql = "SELECT filename FROM files WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->bind_result($filename);
    $stmt->fetch();
    $stmt->close();

    if ($filename) {
        // Eliminar el archivo del sistema de archivos
        $filepath = DIRECTORIO_BASE_FILES . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
            $message = "Archivo físico eliminado. ";
        } else {
            $message = "No existe el fichero fisico: " . $filename;
        }
        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM files WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = $message . "<br> Entrada de BD eliminada.";
        } else {
            $message = $message . "<br> Error al eliminar el archivo en BD.";
        }
        $stmt->close();

    }
}
$conn->close();

$_SESSION['message-delete'] = $message;
// Redirigir de nuevo a panel.php
header("Location: panel.php");
exit;
?>
