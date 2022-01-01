<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}
//Recordar curso seleccionado
if(isset($_POST['curso_seleccionado'])){
    $_SESSION['curso_seleccionado'] = $_POST['curso_seleccionado'];
}
include("./conf/db_conf.php");

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Envíos Eliminados</title>
    <link rel="stylesheet" href="../css_styles/configuracion_style.css" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loggedin">
    <nav class="navtop">
        <div>
            <h1>Página de Configuracion</h1>
            <a href="configuracion.php"><i class="fas fa-tools"></i>Configuración de Entregas</a>
            <a href="entregas-eliminadas.php"><i class="fas fa-trash-alt"></i>Entregas Eliminadas</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
    <div class="content">
        <h2>Listar Alumnos Registrados</h2>
        <?php
        if (isset($_SESSION['message5']) && $_SESSION['message5'])
        {
            printf('<b>%s</b>', $_SESSION['message5']);
            unset($_SESSION['message5']);
        }
        ?>
        <div>
            <form method="POST">
                <table>
                    <tr>
                        <td>Seleccionar curso:</td>
                        <td>
                            <select name="curso_seleccionado" onchange="this.form.submit()">
                                <option value=""></option>
                                <?php
                                if($stmt = $con->prepare('SELECT DISTINCT(curso) FROM alumnos')) {
                                    $stmt->execute();
                                    $stmt->bind_result($opcionCurso);

                                    while ($stmt->fetch()) {
                                        if($_SESSION['curso_seleccionado'] == $opcionCurso){
                                            echo '<option value="' . $opcionCurso . '" selected>' . $opcionCurso . '</option>';
                                        } else {
                                            echo '<option value="' . $opcionCurso . '">' . $opcionCurso . '</option>';
                                        }
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" value="listar-alumnos" hidden>
            </form>
            <br/>
            <form action="gestion-alumnos-bbdd.php" method="POST">
            <?php
            //Listar Alumnos por curso
            if(isset($_SESSION['curso_seleccionado'])) {
                if ($stmt = $con->prepare('SELECT id, codigo, nombre, apellidos, 
                                                curso, entregas_realizadas
                                        FROM alumnos WHERE curso = ?')) {

                    $stmt->bind_param("s", $_SESSION['curso_seleccionado']);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($idAlumnoListar, $codigo, $nombre, $apellidos, $curso, $nEntregas);

                        echo "<table><tr><td>Curso Seleccionado:</td>";
                        echo "<td>" . $_SESSION['curso_seleccionado'] . "</td></tr></table><br/>";

                        echo "<table class='tabla_resultado'>";
                        echo "<tr><th><input type='checkbox' id='selectAll' name='selectAll' value='1' onclick='seleccionAll(this)'></th>";
                        echo "<th>Número</th><th>Codigo</th><th>Nombre</th><th>Apellidos</th>";
                        echo "<th>Curso</th><th>Nº Entregas</th><th>id BD</th></tr>";
                        $contador = 1;
                        while ($stmt->fetch()) {
                            echo "<tr><td><input type='checkbox' name='seleccionar[]' value='" . $idAlumnoListar ."'onclick='deseleccionarChkAll(this)'></td>";
                            echo "<td>" . $contador++ . "</td><td>" . $codigo . "</td>";
                            echo "<td>" . $nombre . "</td><td>" . $apellidos . "</td>";
                            echo "<td>" . $curso . "</td><td>" . $nEntregas . "</td><td>" . $idAlumnoListar . "</td></tr>";
                        }
                        echo "</table><br/>";
                        echo "<input type='submit' name='eliminar-alumnos' value='Eliminar Alumnos' class='delete'>";
                    }

                    $stmt->close();
                } else {
                    echo $con->error;
                }
            }
            ?>
            </form>
        </div>
    </div>

    <div class="content">
        <h2>Alta de Alumnos</h2>
        <div>
            <form action="gestion-alumnos-bbdd.php" method="POST">
                <table>
                    <tr>
                        <td>
                            Nuevo Alumno:
                            <input type="checkbox" name="chk-nuevo-alumno" value="1" onclick="mostrarCamposNuevoAlumno()">
                        </td>
                    </tr>
                </table>
                <table id="tabla-nuevo-alumno" hidden>
                    <tr>
                        <td>Codigo:</td>
                        <td>
                            <input type="text" name="codigoAlumno" autocomplete="off" required>
                        </td>

                    </tr>
                    <tr>
                        <td>Nombre:</td>
                        <td>
                            <input type="text" name="nombreAlumno" autocomplete="off" required>
                        </td>

                    </tr>
                    <tr>
                        <td>Apellidos:</td>
                        <td>
                            <input type="text" name="apellidosAlumno"  autocomplete="off" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Curso:</td>
                        <td>
                            <input type="text" name="cursoAlumno" autocomplete="off" required>
                        </td>
                    </tr>
                </table>
                <input id="submit-nuevo-alumno" name="nuevo-alumno" type="submit" value="Alta Alumno" hidden>
            </form>
        </div>
    </div>
    <div class="content">
        <h2>Modificar</h2>
        <div>
            <form action="gestion-alumnos-bbdd.php" method="POST">
                <table>
                    <tr>
                        <td>
                            Modificar Alumno:
                            <input type="checkbox" id="chk-datos-alumno" name="chk-datos-alumno" value="1" onchange="mostrarModificarDatosAlumno()">
                        </td>
                    </tr>
                </table>
                <table id="tabla-modificar-alumno" hidden>
                    <tr>
                        <td></td>
                        <td>Curso seleccionado: <b><?=$_SESSION['curso_seleccionado']?></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><label for="select-alumno" >Seleccionar Alumno:</label></td>
                        <td>
                            <select id="select-alumno" name="alumno_seleccionado" onchange="mostrarDatosAlumnoAjax(this.value)" required>
                                    <option value="nula"></option>
                            <?php

                                    if($stmt = $con->prepare('SELECT id, codigo, nombre, apellidos FROM alumnos
                                                                    WHERE curso = ?')) {
                                        $stmt->bind_param("s", $_SESSION['curso_seleccionado']);
                                        $stmt->execute();
                                        $stmt->bind_result($idAlumnoModificar, $codigo, $nombre, $apellidos);

                                        while ($stmt->fetch()) {
                                            $opcionAlumno = $codigo . " - " . $nombre . " " . $apellidos;
                                            echo "<option value=" . $idAlumnoModificar . ">" . $opcionAlumno . "</option>";
                                        }
                                        $stmt->close();
                                    }
                                ?>
                                </select>
                            </td>
                    </tr>
                    <tr id="ajax-query">
                        <!-- Codigo generado mediante AJAX -->
                    </tr>
                </table>
                <input type="submit" id="submit-modificar-alumno" name="modificar-alumno" value="Modificar Alumno" hidden>
            </form>
            <form action="gestion-alumnos-bbdd.php" method="POST">
                <table>
                    <tr>
                        <td>
                            Modificar Códigos de Alumno:
                            <input type="checkbox" name="chk-codigos" value="1" onclick="mostrarModificarCodigos()">
                        </td>
                    </tr>
                </table>
                <table id="tabla-modificar-codigos" hidden>
                    <tr>
                        <td></td>
                        <td>
                            Prefijo:
                            <input type="text" name="prefijo" autocomplete="off" size="3">
                        </td>
                        <td>
                            Inicio cuenta:
                            <input type="number" min="0" value="0" name="inicio" required>
                        </td>
                        <td>
                            Paso:
                            <input type="number" min="1" max="20" value="1" name="paso" required>
                        </td>
                        <td>
                            Curso:
                        </td>
                        <td>
                            <select name="curso-codigos">
                                <option value="todos">Todos</option>
                                <?php
                                if($stmt = $con->prepare('SELECT DISTINCT(curso) FROM alumnos')) {
                                    $stmt->execute();
                                    $stmt->bind_result($opcionCurso);

                                    while ($stmt->fetch()) {
                                        echo '<option value="' . $opcionCurso . '">' . $opcionCurso . '</option>';
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" id="modificar-codigos" name="modificar-codigos" value="Modificar Códigos" hidden>
            </form>
        </div>
    </div>
    <div class="content">
        <h2>Gestion Administradores</h2>
        <div>
            <form action="gestion-alumnos-bbdd.php" method="POST">
                <table>
                    <tr>
                        <td>
                            Nuevo Admin:
                            <input type="checkbox" name="chk-nuevo-admin" value="1" id="chk-nuevo-admin" onclick="mostrarCamposNuevoAdminExclusivo()">
                        </td>
                    </tr>
                </table>
                <table id="tabla-nuevo-admin" hidden>
                    <tr>
                        <td></td>
                        <td>Login:</td>
                        <td><input type="text" name="login-admin" autocomplete="off"<required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Password:</td>
                        <td><input type="password" name="password-admin" required></td>
                    </tr>
                </table>
                <input type="submit" id="submit-nuevo-admin" name="nuevo-admin" value="Alta Administrador" hidden>
            </form>
            <form action="gestion-alumnos-bbdd.php" method="POST">
                <table>
                    <tr>
                        <td>
                            Borrar Admin:
                            <input type="checkbox" name="chk-borrar-admin" id="chk-borrar-admin" value="1" onclick="mostrarBorrarAdminExclusivo()">
                        </td>
                    </tr>
                </table>
                <table id="tabla-borrar-admin" hidden>
                    <tr>
                        <td></td>
                        <td>Selecciona Administrador:</td>
                        <td>
                            <select name="admin-seleccionado">
                                <option value="nula"></option>
                                <?php
                                if($stmt = $con->prepare('SELECT id, login FROM admins')) {
                                    $stmt->execute();
                                    $stmt->bind_result($idAdmin, $login);
                                    $stmt->store_result();
                                    if ($stmt->num_rows > 1) {
                                        while ($stmt->fetch()) {
                                            echo '<option value="' . $idAdmin . '">' . $login . '</option>';
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
                <input type="submit" id="submit-borrar-admin" class="delete"  name="borrar-admin" value="Borrar Administrador"  hidden>
            </form>
        </div>
    </div>
    <div class="content">
    <h2>Importar Alumnos</h2>
    <div>
        <form action="gestion-alumnos-bbdd.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        Importar fichero:
                        <input type="checkbox" name="chk-importar-fichero" value="1" id="chk-importar-alumnos" onclick="mostrarImportarAlumnos()">
                    </td>
                </tr>
            </table>
            <table id="tabla-importar-alumnos" hidden>
                <tr>
                    <td></td>
                    <td>
                        Fichero:
                        <input type="file" name="fichero" required>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        Curso:
                        <input type="text" name="curso" autocomplete="off" size="10" required>
                    </td>
                </tr>
            </table>
            <div id="tip-formato-fichero" hidden><br/><b>¡Atención!</b><br/> Formato de fichero de alumnos: Cada linea representa los
                datos de un alumno. Cada fila contiene el nombre separado por una coma ' , ' de los apellidos.<br/>
                Para generar el código se usan los 2 primeros caracteres del curso y un índice numérico aleatorio.<br/>
                Si los 2 primeros caracteres coinciden con otro curso, existe riesgo de fallo en la importación.<br/><br/></div>

            <input type="submit" id="submit-importar-alumnos" name="importar-fichero" value="Importar Alumnos" hidden>
        </form>
    </div>
    </div>

<script>
    function mostrarImportarAlumnos(){
        document.getElementById("tabla-importar-alumnos").toggleAttribute("hidden");
        document.getElementById("submit-importar-alumnos").toggleAttribute("hidden");
        document.getElementById("tip-formato-fichero").toggleAttribute("hidden");
    };

    function mostrarCamposNuevoAlumno(){
        document.getElementById("tabla-nuevo-alumno").toggleAttribute("hidden");
        document.getElementById("submit-nuevo-alumno").toggleAttribute("hidden");
    };

    function mostrarModificarDatosAlumno(){
        document.getElementById("tabla-modificar-alumno").toggleAttribute("hidden");
        document.getElementById("submit-modificar-alumno").toggleAttribute("hidden");
        if(document.getElementById("tabla-modificar-alumno").hidden == true){
            document.getElementById("submit-modificar-alumno").hidden = true;
        }
        if(document.getElementById("select-alumno").value == "nula"){
            document.getElementById("submit-modificar-alumno").hidden = true;
        }
    };

    function mostrarModificarCodigos(){
        document.getElementById("tabla-modificar-codigos").toggleAttribute("hidden");
        document.getElementById("modificar-codigos").toggleAttribute("hidden");
    };

    function mostrarCamposNuevoAdminExclusivo(){
        if(document.getElementById("chk-borrar-admin").checked){
            mostrarBorrarAdmin();
            document.getElementById("chk-borrar-admin").checked = false;
        }
            document.getElementById("tabla-nuevo-admin").toggleAttribute("hidden");
            document.getElementById("submit-nuevo-admin").toggleAttribute("hidden");
    };
    function mostrarCamposNuevoAdmin(){
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

    function seleccionAll(source) {
        checkboxes = document.getElementsByName('seleccionar[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = source.checked;
        }
    };

    function deseleccionarChkAll(source){
        if(!source.checked){
            document.getElementById("selectAll").checked = false;
        }
    }

    function mostrarDatosAlumnoAjax(value) {
        var xhttp;
        if (value == "nula") {
            document.getElementById("ajax-query").innerHTML = "";
            document.getElementById("submit-modificar-alumno").hidden = true;
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("ajax-query").innerHTML = this.responseText;
                document.getElementById("submit-modificar-alumno").hidden = false;
            }
        };
        xhttp.open("GET", "ajax-datos-alumno.php?id="+value, true);
        xhttp.send();
    }
</script>

</body>
</html>
<?php
$con->close();
?>

