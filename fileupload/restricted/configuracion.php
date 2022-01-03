<?php
include("./conf/db_conf.php");
include("./conf/upload_conf.php");
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

//Borrar variable de Seccion Gestión Alumnos
if(isset($_SESSION['curso_seleccionado'])){
    unset($_SESSION['curso_seleccionado']);
}

//borro todos los ficheros descargados
$zipFiles = glob(DIRECTORIO_BASE_UPLOADS . '*.zip');
foreach($zipFiles as $file){
    unlink($file);
}

$con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if($stmt = $con->prepare('SELECT envios_activados(), nombre_entrega_actual(), ruta_entrega_actual(), 
                        control_alumnos_activado(), entrega_unica_activada(), extensiones_permitidas(), 
                        maxsize_entrega_actual()')) {
    $stmt->execute();
    $stmt->bind_result($isEnviosActivados, $nombreEntregaActiva, $rutaEntregaActiva,
        $isControlAlumnos, $isEntregaUnica, $extensiones, $tamanoEntregaActual);
    $stmt->fetch();
    $stmt->close();
}

//Guardo cual es el listado actual
$nombreEntregaListada = $nombreEntregaActiva;
if(isset($_POST['entrega_seleccionada'])){
    $nombreEntregaListada = $_SESSION['entrega-listada'] = $_POST['entrega_seleccionada'];
} else if(isset($_SESSION['entrega-listada'])){
    $nombreEntregaListada = $_SESSION['entrega-listada'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Configuracion</title>
    <link rel="stylesheet" href="../css_styles/configuracion_style.css" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Página de Configuracion</h1>
        <a href="alumnos-usuarios.php"><i class="fas fa-users"></i>Gestión de Alumnos</a>
        <a href="entregas-eliminadas.php"><i class="fas fa-trash-alt"></i>Entregas Eliminadas</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout-> <?=$_SESSION['name']?></a>
    </div>
</nav>
<div class="content">

    <h2>Configuración de entregas</h2>
    <div>
        <form action="guardar-configuracion.php" method="POST">
        <table>
            <tr>
                <td>Nombre entrega actual:</td>
                <td class="titulo-entrega"><?=$nombreEntregaActiva?></td>
            </tr>
            <tr>
                <td>Envios Activados:</td>
                <td><input type="checkbox" name="chk-envio" <?php if($isEnviosActivados == 1){echo "checked";} ?> value="1" ></td>
            </tr>
            <tr>
                <td>Ruta de entrega actual:</td>
                <td><?=$rutaEntregaActiva?></td>
            </tr>
            <tr>
                <td>Solo un envio por entrega:</td>
                <td><input type="checkbox" name="chk-envio-unico" <?php if($isEntregaUnica == 1){echo "checked";} ?> value="1" ></td>
            </tr>
            <tr>
                <td>Modificar tipos permitidos:</td>
                <td>
                    <input type="checkbox" name="chk-extensiones" value="1" onclick="mostrarExtensiones()">
                </td>
                <td>
                    <input type="text" name="lista-extensiones" size="30" value="<?=$extensiones?>" id="lista-extensiones" autocomplete="off" hidden>
                    <label for="quantity" id="label-extensiones" hidden>Indicar extensiones sin espacios separadas por ' , '</label>
                </td>
            </tr>
            <tr>
                <td>Control de Alumnos:</td>
                <td><input type="checkbox" name="chk-control-alumno" <?php if($isControlAlumnos == 1){echo "checked";} ?> value="1" ></td>
            </tr>
            <tr>
                <td>Tamaño de archivo:</td>
                <td><input type="checkbox" name="chk-tamano-archivo" onclick="modificarTamanoFichero()"></td>
                <td><input type="number" id="spinner-tamano-actual" name="max-size-actual" value="<?=$tamanoEntregaActual?>" min="0.1" max="5" step="0.1" hidden></td>
            </tr>
            <tr>
                <td>¿Crear nueva entrega?</td>
                <td>
                    <input type="checkbox" name="chk-nueva-entrega" value="1" onclick="mostrarNuevaEntrega()">
                </td>
                <td>
                    <input type="text" name="nombre_entrega" id="nombre_entrega" autocomplete="off" hidden placeholder="Nombre nueva entrega">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <label for="quantity" id="label_tamano_entrega" hidden>Tamaño máximo (Mb):</label>
                </td>
                <td>
                    <input type="number" id="spinner_tamano_entrega" name="max_size" value="1.0" min="0.1" max="5" step="0.1" hidden>
                </td>
            </tr>
        </table>
            <br/>
            <input type="submit" value="Guardar">
        </form>
        <br/>
        <?php
        if (isset($_SESSION['message2']) && $_SESSION['message2'])
        {
            printf('<b>%s</b>', $_SESSION['message2']);
            unset($_SESSION['message2']);
        }
        ?>
    </div>
</div>

<div class="content">

    <h2>Mostrar envíos</h2>
    <div>
        <form method="POST">
            <table>
                <tr>
                    <td>Seleccionar entrega:</td>
                    <td>
                        <select name="entrega_seleccionada" onchange="this.form.submit()">
                            <option value="nula"></option>
                            <?php
                                if($stmt = $con->prepare('SELECT nombre FROM entregas 
                                                                ORDER BY id DESC')) {
                                    $stmt->execute();
                                    $stmt->bind_result($opcion);

                                    while ($stmt->fetch()) {
                                        echo '<option value="' . $opcion . '">' . $opcion . '</option>';
                                    }
                                    $stmt->close();
                                }
                            ?>
                        </select>
                    </td>
                </tr>

            </table>
            <input type="submit" value="listar" hidden>
        </form>

    </div>
</div>

<div class="content">
    <div>
        <?php
        //Mostrar mensajes de borrados
        if (isset($_SESSION['message3']) && $_SESSION['message3']){
            echo "<div class='mensajes-borrado'>";
            printf('<b>%s</b>', $_SESSION['message3']);
            unset($_SESSION['message3']);
            echo "</div><br/>";
        }
        ?>
        <form method="POST" action="gestion-archivos.php">
        <?php
        $rutaEntregaListada = '';
        if($stmt = $con->prepare('SELECT ruta_directorio, max_size FROM entregas 
                                        WHERE nombre = ?')) {
            $stmt->bind_param("s", $nombreEntregaListada);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($rutaEntregaListada, $size);
            $stmt->fetch();
            $stmt->close();

            echo "<table ><tr><td>Nombre entrega:</td><td>" . $nombreEntregaListada ."</td></tr>";
            echo "<tr><td>Ruta directorio:</td><td>" . $rutaEntregaListada ."</td></tr>";
            echo "<tr><td>Max size:</td><td>" . $size ."</td></tr></table>";

            //echo "<table><tr><th>Nombre entrega</th><th>Ruta directorio</th><th>Max size</th></tr>";
            //echo "<tr><td>" . $nombre . "</td><td>" . $ruta ."</td><td>" . $size . "</td></tr></table><br/>";

        } else {
            echo "Falló la consulta: (" . $con->errno . ") " . $con->error;
        }
    //Muestro los datos del upload y el nombre del alumno, en caso de que esté registrado
    if($stmt = $con->prepare("SELECT u.id, u.nombre_fichero, DATE_FORMAT(u.fecha_envio,'%d-%m-%Y %H:%i:%s'), 
                                    u.codigo_alumno, CASE WHEN a.nombre IS NULL THEN
                                    'unreg' ELSE CONCAT(a.nombre, ' ' , a.apellidos) END
                                    FROM uploads u LEFT JOIN alumnos a ON u.codigo_alumno = a.codigo
                                    WHERE id_entrega = (SELECT id 
                                                        FROM entregas
                                                        WHERE nombre = ?)")) {

    $stmt->bind_param("s", $nombreEntregaListada);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $archivo, $fecha, $codigo, $alumno);
    ?>

            <input type="submit" name="descargarEnvios" value="Descargar Selección"><br/><br/>

            <table class="tabla_resultado">

            <tr>
                <th><input type="checkbox" id="selectAll" name="selectAll" value="1"  onclick="seleccionAll(this)" ></th>
                <th>Nº</th>
                <th>Archivo</th>
                <th>Fecha</th>
                <th>Código</th>
                <th>Alumno</th>
            </tr>
<?php
            $contEnvio = 0;
            while ($stmt->fetch()) {
?>
                <tr>
                    <td><input type='checkbox' name="seleccionar[]" value="<?=$id?>" onclick="deseleccionarChkAll(this)"></td>
                    <td><?=++$contEnvio?></td>
                    <td><?=$archivo?></td>
                    <td><?=$fecha?></td>
                    <td><?=$codigo?></td>
                    <?php
                        if($alumno === "unreg"){
                            echo "<td class='unreg'>" . $alumno . "</td>";
                        }else{
                            echo "<td>" . $alumno . "</td>";
                        }
                    ?>

                </tr>
<?php
            }
?>
            </table>
        <br/>
        <input type="submit" class="delete" name="eliminarEnvios" value="Eliminar Selección" onclick="return confirm('¿Desea eliminar los elementos seleccionados?')">

<?php
    }
        //En caso de que no haya ningun envio listado, si tampoco hay entrega deshabilito el botón
    if(!empty($nombreEntregaListada)){
?>
            <input type="hidden" name="nombre_entrega" value="<?=$nombreEntregaListada?>">
            <input type="hidden" name="ruta_entrega" value="<?=$rutaEntregaListada?>">
            <input type="submit" style="float: right;" class="delete" name="eliminarEntrega" value="Eliminar Entrega" onclick="return confirm('ATENCIÓN: ¿eliminar entrega completa?')">
            <br/>
    <?php
    }
    $stmt->close();
}
    ?>
        </form>
    </div>
</div>

<!-- Emplazar js al final para evitar errores -->
<script>
    //Al pulser el checkbox ejecuto esta funcion
    function mostrarNuevaEntrega() {
        //hace visibles unos elementos y requerido nombre de entrega
        document.getElementById("nombre_entrega").toggleAttribute("hidden");
        document.getElementById("nombre_entrega").toggleAttribute("required");
        document.getElementById("label_tamano_entrega").toggleAttribute("hidden");
        document.getElementById("spinner_tamano_entrega").toggleAttribute("hidden");
    };

    //Seleccionar/deseleccionar todos los checkboxes
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

    function mostrarExtensiones(){
        document.getElementById("lista-extensiones").toggleAttribute("hidden");
        document.getElementById("label-extensiones").toggleAttribute("hidden");
    };

    function modificarTamanoFichero(){
        document.getElementById("spinner-tamano-actual").toggleAttribute("hidden");
    }

</script>

</body>
</html>
<?php
$con->close();
?>
