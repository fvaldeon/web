<?php
//1-Indicar la password para hashear y ejecutar el script
//2-Almacenar la password hasheada en la bbdd para hacer login
function hashear($plainPassword){

$options = [
    'cost' => 11  
];
    return password_hash($plainPassword, PASSWORD_BCRYPT, $options);
}

//Funcion que genera las cabeceras http para descargar un fichero
function descargarFichero($file){
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        while (@ob_end_clean());
        readfile($file);
        //exit;
    }
}

//Función para sanear el nombre. Elimina tildes, espacios o barras multiples y caracteres no permitidos
function sanear_nombre_fichero($file){
    $sinAcentos = iconv('UTF-8','ASCII//TRANSLIT', $file);
    $sanearBarras = preg_replace('/_+|-+/', ' ', $sinAcentos);
    $sinEspacios = preg_replace('/\s+/', '-', $sanearBarras);
    return preg_replace( '/[^a-zA-Z0-9._\-()]+/', '',  $sinEspacios);
}

function mensajeErrorFiles($codigo){
    $phpFileUploadErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );
    return $phpFileUploadErrors[$codigo];
}

?>