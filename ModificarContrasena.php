<?php
	include "ParametrosDB0.php";

	$mysqli = mysqli_connect($server, $user, $pass, $basededatos);
	if (!$mysqli) {
		die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
	}
	session_start ();
	if(isset( $_POST['Email']) && isset( $_POST['Contrasena'])){
		$username = $_POST['Email'];
		$contrasena = $_POST['Contrasena'];
		$_SESSION['user'] = $username;

		if( $username == "admin@ehu.es") {
			$_SESSION['admin'] = "SI";
		}
		else{
			$_SESSION['admin'] = "NO";
		}
		
$queryEstado = "SELECT * FROM usuarios WHERE Direccion = '$username'";
$result = mysqli_query($mysqli, $queryEstado);
$row = mysqli_fetch_assoc($result);
$contrasena_hashed =  $row['Contrasena'];


$verify = password_verify($contrasena, $row['Contrasena']);
	if($verify){
	$usuarios = mysqli_query($mysqli,"SELECT * FROM usuarios
	WHERE Direccion = '$username'");
	$cont = mysqli_num_rows($usuarios); //Se verifica el total de filas devueltas
	mysqli_close($mysqli); //cierra la conexion
	}
	else {
		$usuarios = mysqli_query($mysqli,"SELECT * FROM usuarios
	WHERE Direccion = '$username' and Contrasena = '$contrasena'");
	$cont = mysqli_num_rows($usuarios); //Se verifica el total de filas devueltas
	mysqli_close($mysqli); //cierra la conexion
		
	}
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
		<?php if(isset($cont) &&($cont==1)){?>
		<span class="right" style="display:none;"><a href="registrar.php">Registrarse</a></span>
      	<span class="right" style="display:none;"><a href="login.php">Login</a></span>
		<span class="right" ><a href="logout.php" id = "url" >Logout</a></span>
		<span class="right"> <font size= "2.5" >Bienvenido: <?php echo $username; ?></font></span>
		<?php }
		else {?>
		<span class="right" ><a href="registrar.php">Registrarse</a></span>
      	<span class="right" ><a href="login.php">Login</a></span>
      	<span class="right" style="display:none;" ><a href="/logout">Logout</a></span>
		<?php } ?>
		<h2>Quiz: Juego de las preguntas</h2>
    </header>
	<nav class='main' id='n1' role='navigation'>
		<?php if(isset($cont) && ($cont==1) && $_SESSION['admin'] == "NO" ){?>
			<span><a href='layout.php?email=<?php echo $username; ?>'>Inicio</a></span>
			<span><a href='GestionPreguntas.php?email=<?php echo $username; ?>'>Gestionar Preguntas</a></span>
			<span><a href='creditos.php?email=<?php echo $username; ?>'>Creditos</a></span>
		<?php }
		else if(isset($cont) && ($cont==1) && $_SESSION['admin'] == "SI" ){?>
			<span><a href='layout.php?email=<?php echo $username; ?>'>Inicio</a></span>
			<span><a href='GestionarCuentas.php?email=<?php echo $username; ?>'>Gestionar Cuentas</a></span>
			<span><a href='creditos.php?email=<?php echo $username; ?>'>Creditos</a></span>
		<?php }
		else { ?>
			<span><a href='layout.php'>Inicio</a></spam>
			<span><a href='creditos.php'>Creditos</a></spam>
		<?php } ?>
	</nav>

    <section class="main" id="s1">
    <div>
        <form id='reset_password' name='reset_password' action='reset_password.php' method = 'post'>
        	<p>Introduce el email con el que te registraste <strong>(*)</strong>:</p>
        	<br><input id="Email" style="width:300px" type="text" name="Email" autocomplete="off" placeholder="Ej: tszemzo001@ikasle.ehu.eus"/>

			<br><br>
            <input type ="submit" id="botonEnviar" value ="Enviar"></input>
        </form>
    </div>
	<?php if(isset( $_POST['Email']) && isset( $_POST['Contrasena'])){?>
		<?php if($cont==1 && $_SESSION['admin'] == "NO" ){?>
			<script>alert('Bienvenido al Sistema: "<?php echo $username; ?>" ')</script>



			<p>Login correcto</p><a href='preguntas.php?email=<?php echo $username; ?>'>Puede insertar preguntas</a>
		<?php }		
		 else if($cont==1 && $_SESSION['admin'] == "SI" ){?>
			<script>alert('Bienvenido al Sistema: "<?php echo $username; ?>" ')</script>

			<p>Login correcto</p><a href='GestionarCuentas.php?email=<?php echo $username; ?>'>Puede gestionar las cuentas</a>
		<?php }
		else { ?>
			<p>Parametros de login incorrectos</p><a href='login.php'>Puede intentarlo de nuevo</a>
	   <?php }?>
   <?php }?>
    </section>
    <footer class='main' id='f1'>
		<a href='https://github.com/tszemzo/proyectoSW'>Link GITHUB</a>
	</footer>
    </body>
</html>
<script>
	$(document).ready(function(){
		$('#url').click(function(){
			alert("Agur!")});
	});
</script>
