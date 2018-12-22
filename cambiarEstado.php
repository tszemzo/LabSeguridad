<?php
include "ParametrosDB0.php";

$mysqli = mysqli_connect($server, $user, $pass, $basededatos);
if (!$mysqli){
    die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}
$queryEstado = "SELECT Estado FROM usuarios WHERE Direccion = '$_POST[correo]'";
$result = mysqli_query($mysqli, $queryEstado);
$row = mysqli_fetch_assoc($result);
$estado =  $row['Estado'];
if($estado == 'INACTIVO'){
$query = "UPDATE usuarios SET Estado = 'ACTIVO' WHERE Direccion = '$_POST[correo]'";
}
else{
	$query = "UPDATE usuarios SET Estado = 'INACTIVO' WHERE Direccion = '$_POST[correo]'";
}
if (!mysqli_query($mysqli ,$query))
{
die('Error en la consulta');
}
mysqli_close($mysqli);
?>
