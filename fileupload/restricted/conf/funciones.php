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
        exit;
    }
}

?>