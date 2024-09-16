<?php
session_start();

include("./conf/db_conf.php");
include("./conf/filespath_conf.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

$conn = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="../css_styles/styles2.css">
</head>
<body>
<div class="container">
    <h1>Panel de Control</h1>

    <!-- Botón de Cerrar Sesión -->
    <form action="logout.php" method="post" style="text-align: right; ">
        <button type="submit" style="background: #7d62ad;">Cerrar sesión</button>
    </form>

    <!-- Formulario para subir archivos -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="code">Código de archivo:</label>
        <input type="text" id="code" name="codigoArchivo" autocomplete="off" required>
        <br>
        <label for="file">Selecciona el archivo:</label>
        <input type="file" id="file" name="file" required>
        <br>
        <button type="submit" name="uploadBtn" value="Upload">Subir Archivo</button>
        <div id="lbl-file-uploaded">
            <?php

            if (isset($_SESSION['message-upload']) && $_SESSION['message-upload']) {
                printf('<br><b>%s</b>', $_SESSION['message-upload']);
                unset($_SESSION['message-upload']);
            }
            ?>
        </div>
    </form>

    <h2>Archivos Subidos</h2>
    <table>
        <thead>
        <tr>
            <th>Código</th>
            <th>Nombre del Archivo</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_SESSION['message-delete']) && $_SESSION['message-delete']) {
            echo "<tr><td id='mensaje-file-deleted' colspan='3'>" . $_SESSION['message-delete'] . "</td></tr>";
            unset($_SESSION['message-delete']);
        }

        // Consulta para obtener la lista de archivos
        $sql = "SELECT id, code, filename FROM files";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                                <td>{$row['code']}</td>
                                <td>{$row['filename']}</td>
                                <td>
                                    <form action='delete_file.php' method='post'>
                                        <input type='hidden' name='delete_id' value='{$row['id']}'>
                                        <button type='submit' name='deleteBtn' value='Eliminar'>Eliminar</button>
                                    </form>
                                </td>
                              </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay archivos subidos.</td></tr>";
        }


        ?>
        </tbody>
    </table>


</div>

<h2>Gestion Administradores</h2>
<div class="container">
    <?php

    if (isset($_SESSION['message1']) && $_SESSION['message1']) {
        printf('<b>%s</b>', $_SESSION['message1']);
        unset($_SESSION['message1']);
    }
    ?>
    <form action="gestion_admins.php" method="POST">
        <table>
            <tr>
                <td>
                    Nuevo Admin:
                    <input type="checkbox" name="chk-nuevo-admin" value="1" id="chk-nuevo-admin"
                           onclick="mostrarCamposNuevoAdminExclusivo()">
                </td>
            </tr>
        </table>
        <table id="tabla-nuevo-admin" hidden>
            <tr>
                <td></td>
                <td>Username:</td>
                <td><input type="text" name="login-admin" autocomplete="off"                     required>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Password:</td>
                <td><input type="password" name="password-admin" autocomplete="off" required></td>
            </tr>
        </table>
        <input type="submit" id="submit-nuevo-admin" name="nuevo-admin" value="Alta Administrador" hidden>
    </form>

    <?php
    if (isset($_SESSION['message2']) && $_SESSION['message2']) {
        printf('<b>%s</b>', $_SESSION['message2']);
        unset($_SESSION['message2']);
    }
    ?>
    <form action="gestion_admins.php" method="POST">
        <table>
            <tr>
                <td>
                    Borrar Admin:
                    <input type="checkbox" name="chk-borrar-admin" id="chk-borrar-admin" value="1"
                           onclick="mostrarBorrarAdminExclusivo()">
                </td>
            </tr>
        </table>
        <table id="tabla-borrar-admin" hidden>
            <tr>
                <td></td>
                <td>Seleccionar:</td>
                <td>
                    <select name="admin-seleccionado">
                        <option value="nula"></option>
                        <?php
                        if ($stmt = $conn->prepare('SELECT id, username FROM admins')) {
                            $stmt->execute();
                            $stmt->bind_result($idAdmin, $username);
                            $stmt->store_result();
                            if ($stmt->num_rows > 1) {
                                while ($stmt->fetch()) {
                                    echo '<option value="' . $idAdmin . '">' . $username . '</option>';
                                }
                            } else {
                            $stmt->close();
                        ?>
                    </select>
                </td>
                <?php
                echo "<td><b>Atención!: </b>No se puede borrar el último administrador</td>";
                }
                }
                ?>
            </tr>

        </table>
        <input type="submit" id="submit-borrar-admin" class="delete" name="borrar-admin" value="Borrar Administrador"
               hidden>
    </form>
</div>
<script>

    function mostrarCamposNuevoAdminExclusivo() {
        if (document.getElementById("chk-borrar-admin").checked) {
            mostrarBorrarAdmin();
            document.getElementById("chk-borrar-admin").checked = false;
        }
        document.getElementById("tabla-nuevo-admin").toggleAttribute("hidden");
        document.getElementById("submit-nuevo-admin").toggleAttribute("hidden");
    };

    function mostrarCamposNuevoAdmin() {
        document.getElementById("tabla-nuevo-admin").toggleAttribute("hidden");
        document.getElementById("submit-nuevo-admin").toggleAttribute("hidden");
    };

    function mostrarBorrarAdminExclusivo() {
        if (document.getElementById("chk-nuevo-admin").checked) {
            mostrarCamposNuevoAdmin();
            document.getElementById("chk-nuevo-admin").checked = false;
        }
        document.getElementById("tabla-borrar-admin").toggleAttribute("hidden");
        document.getElementById("submit-borrar-admin").toggleAttribute("hidden");
    };

    function mostrarBorrarAdmin() {
        document.getElementById("tabla-borrar-admin").toggleAttribute("hidden");
        document.getElementById("submit-borrar-admin").toggleAttribute("hidden");
    };


</script>
</body>
</html>
<?php
$conn->close();
?>
