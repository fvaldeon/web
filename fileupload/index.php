<?php
session_start();

include("restricted/conf/db_conf.php");

$max_size = 2;
$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
$result = $con->query("SELECT maxsize_entrega_actual() AS max_size");
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $max_size = $row['max_size'];
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="ee" >
<head>
  <meta charset="UTF-8">
  <title>Entregas - abrilCode</title>
  <!-- Librerias de fuentes y normalizacion, se pueden borrar -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:400,300,700'>
  <link rel="stylesheet" href="css_styles/index_style.css" type="text/css">
    <link rel="icon" href="favicon.ico">

</head>
<body>

<h2>Entrega de Archivos</h2>
<form method="POST" action="restricted/upload.php" enctype="multipart/form-data">
  <div class="accept-area">
    <div class="codigo-alumno">
      <span class="fake-field">Código de Alumno</span>
        <input class="field" type="text" title="Código de Alumno" name="codigoAlumno" size=22 maxlength=10 autocomplete="off" required />
    </div>
  </div>
  <div class="file-drop-area">
     <span class="fake-btn">Selecciona un fichero</span>
     <span class="file-msg" id="file-msg">o suelta el fichero aquí...</span>
     <input class="file-input" type="file" name="uploadedFile" id="file-input" required onchange="validateSize()">
  </div>
  <div class="accept-area">
    <span class="fake-btn">Enviar</span>
      <input class="file-input" type="submit" name="uploadBtn" value="Upload" >
  </div>
</form>
<div id="mensaje-error">
    <?php
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
        printf('<b>%s</b>', $_SESSION['message']);
        unset($_SESSION['message']);
    }
    ?>
</div>
<footer>
    <a href="restricted/login.php">login -</a>

    <a href="https://abrilcode.com" class="link-abrilcode"> abrilcode.com</a>
</footer>
<!-- emplezar js al final para evitar problemas -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script  src="js_scripts/index_script.js"></script>
<script>
    //Controlar tamaó de ficheros
    function validateSize() {
        const fileSize = document.getElementById("file-input").files[0].size / 1048500;
        const maxsize = <?=$max_size?>;
        if (fileSize > <?=$max_size?>) {
            document.getElementById("mensaje-error").innerHTML = "Tamaño máximo permitido: " + maxsize + " Mb."
            document.getElementById("file-input").value = '';
        } else {
            document.getElementById("mensaje-error").innerHTML = '';
        }

    }
</script>

</body>
</html>
