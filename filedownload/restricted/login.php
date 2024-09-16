<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Descargas</title>
    <link rel="stylesheet" href="../css_styles/styles.css">
</head>
<body>
<div class="container">
    <h1>Acceso</h1>

    <form action="autenticar.php" method="post">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" placeholder="Username" autocomplete="off" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <br>
        <button type="submit">Login</button>
    </form>

    <label id="lbl-message">
        <?php
        session_start();

        if (isset($_SESSION['message-login']) && $_SESSION['message-login']) {
            printf('<b>%s</b>', $_SESSION['message-login']);
            unset($_SESSION['message-login']);
        }
        ?>
    </label>

</div>
</body>
</html>

