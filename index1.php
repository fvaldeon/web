<?xml version="1.0" encoding="iso-8859-1"?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<title>Gestion Gimnasio</title> 
<!-- codigo css para la tabla -->
<style>

body {
  background-color: lightblue;
}

h1 {
  color: blue;
  text-align: center;
}

h3 {
	color: red;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}
tr:nth-child(odd){background-color: #f2C2f2}

th {
  background-color: #4CAF50;
  color: white;
}
</style>
</head> 
<body> 

<h1>Gestion Gimnasio</h1>

<!-- Conexion desde php -->
<?php 
  // Conectar al servidor local con mi usuario fer2
  if(!($iden = mysqli_connect("localhost", "root", "", "enformacion"))) 
    die("Error: No se pudo conectar");
	

	
  // Sentencia SQL: muestra todo el contenido de la tabla "usuarios" 
  $sentencia = "SELECT nif, nombre, apellidos, telefono, id_taquilla FROM usuarios"; 
  // Ejecuta la sentencia SQL 
  $resultado = mysqli_query($iden, $sentencia ); 
  if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
echo '<h3>Usuarios registrados</h3>';	
  //Muestro todo el contenido en una tabla
  echo '<table>'; 
  echo '<tr><th>NIF</th><th>nombre</th><th>apellidos</th><th>telefono</th><th>taquilla</th></tr>';
  while($fila = mysqli_fetch_assoc($resultado)) 
  { 
    echo '<tr>'; 
    echo '<td>' . $fila['nif'] . '</td><td>' . $fila['nombre'] . '</td><td>' . $fila['apellidos'] . '</td><td>' . $fila['telefono'] . '</td><td>' . $fila['id_taquilla'] . '</td>'; 
    echo '</tr>'; 
  } 
  echo '</table>';
  
  // Libera la memoria de los datos consultados
  mysqli_free_result($resultado);
  
  // Cierra la conexion con la base de datos 
  mysqli_close($iden); 

?> 
<br>
<form action="modificado.php" method="post">
  <fieldset>
    <legend>Nuevo usuario:</legend>
    NIF:<br>
    <input type="text" name="nif" ><br>
	Nombre:<br>
    <input type="text" name="nombre" ><br>
	Apellidos:<br>
    <input type="text" name="apellidos" ><br>
	Telefono:<br>
    <input type="text" name="telefono" ><br>
    Taquilla:<br>
    <input type="text" name="taquilla"><br><br>
	
    <input type="submit" name="boton" value="Alta">
  </fieldset>
</form> 



<form action="modificado.php" method="post">
  <fieldset>
    <legend>Eliminar usuario:</legend>
<?php    

if(!($iden = mysqli_connect("localhost", "root", "", "enformacion"))) 
    die("Error: No se pudo conectar");
	
  // Sentencia SQL: muestra todo el contenido de la tabla "usuarios" 
  $sentencia = "SELECT nif, nombre, apellidos From usuarios";
  $resultado = mysqli_query($iden, $sentencia );
  
	
	
  echo '<select name="usuarios">';
  while($fila = mysqli_fetch_assoc($resultado)){
    echo '<option value="'.$fila[nif].'">' . $fila[nif] . " : " . $fila[nombre] . " " . $fila[apellidos] . '</option>';
  }
  
  echo '</select>';
?>
	
    <input type="submit" name="boton" value="Eliminar">
  </fieldset>
</form> 

<form action="modificado.php" method="post">
  <fieldset>
    <legend>Modificar datos usuario:</legend>
	Usuario a modificar: <br>
<?php    
	
	if(!($iden = mysqli_connect("localhost", "root", "", "enformacion"))) 
		die("Error: No se pudo conectar");
	
	// Sentencia SQL: muestra todo el contenido de la tabla "usuarios" 
	$sentencia = "SELECT nif, nombre, apellidos From usuarios";
	$resultado = mysqli_query($iden, $sentencia );
  
	
	
	echo '<select name="usuarios">';
	while($fila = mysqli_fetch_assoc($resultado)){
		echo '<option value="'.$fila[nif].'">' . $fila[nif] . " : " . $fila[nombre] . " " . $fila[apellidos] . '</option>';
	}
  
	echo '</select>';
?>
    <br>
	nuevo NIF:<br>
    <input type="text" name="nifnuevo" ><br>
	nuevo Nombre:<br>
    <input type="text" name="nombre" ><br>
	nuevo Apellidos:<br>
    <input type="text" name="apellidos" ><br>
	nuevo Telefono:<br>
    <input type="text" name="telefono" ><br>
    nueva Taquilla:<br>
    <input type="text" name="taquilla"><br><br>
	
    <input type="submit" name="boton" value="Modificar">
  </fieldset>
</form> 



</body> 
</html> 