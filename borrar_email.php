<?php
include "ParametrosDB0.php";

$mysqli = mysqli_connect($server, $user, $pass, $basededatos);
if (!$mysqli){
    die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}
$email = $_POST['delete_id'];
$query = "DELETE FROM usuarios WHERE Direccion = '$_POST[delete_id]'";
if (!mysqli_query($mysqli ,$query))
{
die('Error en la consulta');
}
mysqli_close($mysqli);
?>
