<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Descargas - abrilcode</title>
    <link rel="stylesheet" href="css_styles/styles.css">
    <link rel="icon" href="favicon.ico">
</head>
<body>
<div class="container">
    <h1>Descarga de Archivos</h1>
    <form action="restricted/download.php" method="post" >
        <label for="code">Introduce el código de archivo:</label>

        <input type="text" id="code" name="codigoArchivo" autocomplete="off" placeholder="" required>

        <button type="submit" name="downloadBtn" value= "Download" onclick="borrar_mensaje()">Descargar Archivo </button>
    </form>

</div>
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
<script type="text/javascript">
    function borrar_mensaje() {
        let label = document.getElementById("mensaje-error");
        label.parentNode.removeChild(label);

    }
    function clear_codigo_archivo(){
        //if(!isEmpty(document.getElementById("code").value)) {
            document.getElementById("code").value = "";
        //}
    }

    function isEmpty(str) {
        return !str.trim().length;
    }
</script>

</body>
</html>

