<?php
	session_start ();
	if(isset( $_SESSION['user']) && $_SESSION['user']== "admin@ehu.es"){
	$username = $_SESSION['user'];
	}
	else{
		header("Location: layout.php");
		exit();
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
  <div id='page-wrap' >
	<header class='main' id='h1'>
		<span class="right" style="display:none;"><a href="registrar.php">Registrarse</a></span>
      	<span class="right" style="display:none;"><a href="login.php">Login</a></span>
      	<span class="right" ><a href="logout.php" id="url">Logout</a></span>
		<span class="right"> <font size= "2.5" >Bienvenido: <?php echo $username; ?></font></span>
		<h2>Quiz: Juego de las preguntas</h2>
    </header>
	<nav class='main' id='n1' role='navigation'>
		<span><a href='layout.php?email=<?php echo $username; ?>'>Inicio</a></span>
		<span><a href='GestionarCuentas.php?email=<?php echo $username; ?>'>Gestionar Cuentas</a></span>
		<span><a href='creditos.php?email=<?php echo $username; ?>'>Creditos</a></span>
	</nav>
    <section class="main" id="s1">

    <div id = "tablaActualizada">
		<table id='gestion' border=1> <tr> <th> Correo </th> <th> Contrasena </th>
		<th> Estado </th> <th> Accionar </th></tr>

<?php
include "ParametrosDB0.php";
$mysqli = mysqli_connect($server, $user, $pass, $basededatos);
if (!$mysqli)
{
die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}
$preguntas = mysqli_query($mysqli, "SELECT * FROM usuarios;" );
	while ($row = mysqli_fetch_array( $preguntas )) {
		if ($row[0]=='admin@ehu.es') {
			continue;
		}
		$correo = $row[0];
		echo
		'<tr>
			<td>' . $row[0] . '</td> <td>' . $row[2] .'</td>
			<td>' . $row[4] . '<input type="button" value="Cambiar" id="'. $correo . '"class="change_class" />'.'</td>
			<td> <input type="button" class="delete_class" id="'. $correo . '" value="Eliminar" />'.'</td>
		</tr>';
	}
	echo '</table> </div> </section> </body>';
$preguntas->close();
mysqli_close($mysqli);
?>

</section>
<footer class='main' id='f1'>
	<a href='https://github.com/tszemzo/proyectoSW'>Link GITHUB</a>
</footer>
</div>
</body>
</html>
<script>
	$(document).ready(function(){
		$('#url').click(function(){
			alert("Agur!")
		});
		
		
		$(document).on('click', '.change_class', function(e){
		var chg_email = $(this).attr('id');
		$.ajax({
			type:'POST',
			url:'cambiarEstado.php',
			cache : false,
			data:'correo='+chg_email,
			success: function() {
				$("#tablaActualizada").load(" #tablaActualizada");
			}
		});
	});
		
		$(document).on('click', '.delete_class', function(e){

   			var del_email = $(this).attr('id');
			$.ajax({
				type:'POST',
      			url:'borrar_email.php',
				cache : false,
      			data:'delete_id='+del_email,
      			success: function() {
					$("#tablaActualizada").load(" #tablaActualizada");

        		}
   			});
 		});
	});
</script>
