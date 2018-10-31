<?php 
	if(isset( $_GET['email'])){
	$username = $_GET['email'];
	}
?>
<html>
  <head>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>
    <link rel='stylesheet' type='text/css' href='estilos/style.css' />
	<link rel='stylesheet'
		   type='text/css'
		   media='only screen and (min-width: 530px) and (min-device-width: 481px)'
		   href='estilos/wide.css' />
	<link rel='stylesheet'
		   type='text/css'
		   media='only screen and (max-width: 480px)'
		   href='estilos/smartphone.css' />
  </head>
  <body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <div id='page-wrap' height = "1000px">
	<header class='main' id='h1'>
		<span class="right" ><a href="layout.php" id = "url" >Logout</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
    </header>
	   <nav class='main' id='n1' role='navigation'>
	    <span><a href='layout.php?email=<?php echo $username; ?>'>Inicio</a></spam>
		<span><a href='preguntas.php?email=<?php echo $username; ?>'>Insertar Pregunta</a></spam>
	    <span><a href='creditos.php?email=<?php echo $username; ?>'>Creditos</a></spam>
	   </nav>

    <section class="main" id="s1">
        <div>
</html>
<script>
	$(document).ready(function(){
		$('#url').click(function(){
			alert("Agur!")});
	});
</script>
<?php
//phpinfo();
include "ParametrosDB0.php";
$mysqli = mysqli_connect($server, $user, $pass, $basededatos);
if (!$mysqli)
{
die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}

$correo = $_POST['nombreDirCorreo'];
$pregunta = $_POST['nombrePregunta'];
$respuesta =$_POST['nombreRespCorrecta'];
$respuestaInc1= $_POST['nombreRespIncorrecta1'];
$respuestaInc2= $_POST['nombreRespIncorrecta2'];
$respuestaInc3= $_POST['nombreRespIncorrecta3'];
$complejidad=$_POST['nombreComplejidad'];
$tema= $_POST['nombreTema'];

// Validamos desde el lado del servidor que no haya campos obligatorios vacios,
// asi como tambien el correo y la complejidad.
if ($correo != "" && $pregunta != "" && $respuesta !="" && $respuestaInc1 != "" && $respuestaInc2 != ""
&& $respuestaInc3 != "" && $complejidad != "" && $tema!= ""){

	if (!preg_match("/^([a-zA-Z_.+-])+[0-9]{3}\@ikasle.ehu.eus+$/",$correo)) {
		$correoError = "El correo no cumple el formato requerido";
		die ( $correoError . mysqli_connect_error());
	}
	if (!preg_match("/^[0-5]$/",$complejidad)) {
		$complejidadError = "La complejidad debe estar entre 0 y 5";
		die ( $complejidadError . mysqli_connect_error());
	}
}
else{
	 die ( "Algun campo obligatorio vacio" . mysqli_connect_error());
}

$sql="INSERT INTO preguntas(Direccion, Pregunta, RespCorrecta, RespIncorrecta1,
RespIncorrecta2, RespIncorrecta3, Complejidad, Tema) VALUES
('$_POST[nombreDirCorreo]','$_POST[nombrePregunta]','$_POST[nombreRespCorrecta]',
'$_POST[nombreRespIncorrecta1]', '$_POST[nombreRespIncorrecta2]','$_POST[nombreRespIncorrecta3]'
,'$_POST[nombreComplejidad]','$_POST[nombreTema]')";

//echo $sql;

if (!mysqli_query($mysqli ,$sql))
{
die('Error en el almacenamiento de la pregunta: ' . mysqli_error($mysqli));
}

echo "<strong>Pregunta almacenada correctamente.</strong>";
?>
<html>
<p> <a href='verDatos.php?email=<?php echo $username; ?>'> Ver preguntas </a>
</html>
<?php
mysqli_close($mysqli);
echo '</div> </section> </div> </body> </html>'
?>
