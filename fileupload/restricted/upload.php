<?php
session_start();

include("./conf/db_conf.php");

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$message = '';

if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    $message = 'Error: No se puede conectar al servidor MySQL: ' . mysqli_connect_error();
} else if(empty($_POST['codigoAlumno'])){
    $message = "Error: debe introducir un código";
} else{
//Comprobar si están activos los envios
    if ($stmt = $con->prepare('SELECT envios_activados(), ruta_entrega_actual(), maxsize_entrega_actual(), 
                                control_alumnos_activado(), entrega_unica_activada(), extensiones_permitidas(),
                                codigo_ya_entregado(?), existe_codigo_alumno(?)')) {
        //Busco si el alumno ya tiene entregas
        $stmt->bind_param("ss", $_POST['codigoAlumno'],$_POST['codigoAlumno']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($isEnviosActivos, $rutaDirectorioDestino, $fileMaxSize , $isControlAlumnos,
                                    $isEntregaUnica,$extensionesString, $isCodigoYaUsado, $existeCodigo);
            $stmt->fetch();
            //Envios estan activados ?
            if ($isEnviosActivos === 1) {
                //Control de usuarios (Si está activado, el codigo debe existir en la bbdd
                if($isControlAlumnos === 0 || ($isControlAlumnos === 1 && $existeCodigo === 1)) {
                    //Control de unico envio (Si está activado, no puede estar ya usado
                    if ($isEntregaUnica === 0 || ($isEntregaUnica === 1 && $isCodigoYaUsado === 0)) {
                        if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
                            if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
                                // get details of the uploaded file
                                $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
                                $fileName = basename($_FILES['uploadedFile']['name']);
                                $fileSize = filesize($fileTmpPath);
                                //$fileType = $_FILES['uploadedFile']['type']; //No necesario
                                $codigoAlumno = $_POST['codigoAlumno'];

                                //Comprobar tamaño de fichero en bytes
                                if ($fileSize < ($fileMaxSize * 1048500)) {

                                    // check if file has one of the following extensions
                                    $fileNameCmps = explode(".", $fileName);
                                    $fileExtension = strtolower(end($fileNameCmps));
                                    //$allowedfileExtensions = array('sql', 'zip', 'rar', 'txt', 'docx', 'pdf', 'png', 'css');
                                    $allowedfileExtensions = explode(",", $extensionesString);

                                    if (in_array($fileExtension, $allowedfileExtensions) && count($fileNameCmps) > 1) {
                                        // directory in which the uploaded file will be moved
                                        if (!file_exists($rutaDirectorioDestino)) {
                                            mkdir($rutaDirectorioDestino, 0777, true);
                                        }
                                        // sanear file-name
                                        $newFileName = date("d-m-Y_H-i-s_") . sanear_nombre_fichero($fileName);
                                        //Ruta de envios obtenida de la bbdd junto con el nombre
                                        $dest_path = $rutaDirectorioDestino . $newFileName;

                                        if (is_uploaded_file($fileTmpPath) && move_uploaded_file($fileTmpPath, $dest_path)) {
                                            //Eliminar el fichero temporal
                                            if (file_exists($fileTmpPath)) {
                                                unlink($fileTmpPath);
                                            }
                                            //Procedimiento para insertar fichero
                                            if ($stmt = $con->prepare('CALL registrar_upload(?, ?, ?)')) {
                                                // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
                                                $timestamp = date('Y-m-d H:i:s');
                                                $stmt->bind_param('sss', $codigoAlumno, $timestamp, $newFileName);
                                                $stmt->execute();
                                                $stmt->close();

                                                $message = 'Fichero enviado correctamente'.$con->error;
                                            } else {
                                                $message = "Error: No se ha guardado el envío en la base de datos. " .
                                                "<br/>" . $stmt->error;
                                            }
                                        } else {
                                            $message = "Error: no se ha podido mover el fichero.";
                                        }
                                    } else {
                                        $message = 'Error. Tipos permitidos: ' . implode(',', $allowedfileExtensions);
                                    }
                                } else {
                                    $message = 'Error: El tamaño máximo es ' . $fileMaxSize . 'Mb.';
                                }
                            } else {
                                $message = 'Hay errores en el envío del fichero.<br>';
                                $message .= 'Error:' . $_FILES['uploadedFile']['error'];
                            }
                        }
                    } else {
                        $message = 'Error: Ya existe una entrega con ese código';
                    }
                } else {
                    $message = 'Error: Código de usuario desconocido';
                }
            } else {
                $message = 'Error: Los envíos están desactivados.';
            }
        } else {
            $message = 'Error: No hay ninguna entrega activa.';
        }
    }
    $stmt->close();
}
$con->close();

$_SESSION['message'] = $message;
header("Location: ../index.php");

//Función para sanear el nombre. Elimina tildes, espacios o barras multiples y caracteres no permitidos
function sanear_nombre_fichero($file){
    $sinAcentos = iconv('UTF-8','ASCII//TRANSLIT', $file);
    $sanearBarras = preg_replace('/_+|-+/', ' ', $sinAcentos);
    $sinEspacios = preg_replace('/\s+/', '-', $sanearBarras);
    return preg_replace( '/[^a-zA-Z0-9._\-()]+/', '',  $sinEspacios);
}
