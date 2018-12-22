<?php
	session_start ();
	if(isset( $_SESSION['user'])){
	$username = $_SESSION['user'];
	}
	else{
		header("Location: layout.php");
		exit();
	}

include "ParametrosDB0.php";
$mysqli = mysqli_connect($server, $user, $pass, $basededatos);
if (!$mysqli)
{
die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}

?>
<script type="text/javascript">

var run = setInterval(function ActualizarDatos(){
	<?php
$resultado = mysqli_query($mysqli, "SELECT COUNT(*) as cant FROM preguntas;" );
$resultado=mysqli_fetch_assoc($resultado);
$cantidad=$resultado['cant'];
$resultadoUsuario = mysqli_query($mysqli, "SELECT COUNT(*) as cant2 FROM preguntas WHERE Direccion ='$username';" );
$resultadoUsuario=mysqli_fetch_assoc($resultadoUsuario);
$cantidadUsuario=$resultadoUsuario['cant2'];
mysqli_close($mysqli);
?>
 $( "#totalPreguntas" ).load(window.location.href + " #totalPreguntas" );
}, 3000);
</script>
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
		<span><a href='GestionPreguntas.php?email=<?php echo $username; ?>'>Gestionar Preguntas</a></span>
		<span><a href='creditos.php?email=<?php echo $username; ?>'>Creditos</a></span>
	</nav>

    <section class="main" id="s1">
    <div>
        <form id='fpreguntas' name='fpreguntas' action='InsertarPregunta.php?email=<?php echo $username; ?>' method = 'post'>
        	<p> Dirección de correo del autor de la pregunta <strong>(*)</strong></p>
        	<input id="dirCorreo" style="width:300px" type="text" name="nombreDirCorreo" autocomplete="off" value=<?php echo $username; ?> readonly />

        	<p> Enunciado de la pregunta <strong>(*)</strong></p>
        	<input id="pregunta" style="width:300px" type="text" name="nombrePregunta" autocomplete="off" placeholder="Ej: Elemento HTML"/>

        	<p>Respuesta correcta <strong>(*)</strong></p>
        	<input id="respCorrecta" style="width:300px" type="text" name="nombreRespCorrecta" autocomplete="off" placeholder="Ej: <TABLE>"/>

            <p>Respuesta incorrecta 1 <strong>(*)</strong></p>
        	<input id="respIncorrecta1" style="width:300px" type="text" name="nombreRespIncorrecta1" autocomplete="off" placeholder="Introducir una respuesta incorrecta"/>

            <p>Respuesta incorrecta 2 <strong>(*)</strong></p>
        	<input id="respIncorrecta2" style="width:300px" type="text" name="nombreRespIncorrecta2" autocomplete="off" placeholder="Introducir una respuesta incorrecta"/>

            <p>Respuesta incorrecta 3 <strong>(*)</strong></p>
        	<input id="respIncorrecta3" style="width:300px" type="text" name="nombreRespIncorrecta3" autocomplete="off" placeholder="Introducir una respuesta incorrecta"/>

            <p>Complejidad de la pregunta entre 0 y 5 <strong>(*)</strong></p>
        	<input id="complejidad" style="width:300px" type="text" name="nombreComplejidad" autocomplete="off" placeholder="Ej: 5"/>

            <p>Tema de la pregunta <strong>(*)</strong></p>
            <input id="tema" style="width:300px" type="text" name="nombreTema" autocomplete="off" placeholder="Ej: HTML"/>
			<br><br>
            <input type ="button" id="botonEnviar" value ="Enviar" onclick="insertarPreg()">Enviar pregunta</input>
			<br><br>
			<input type ="button" id="botonVerPreguntas" value ="Ver Preguntas" onclick="pedirDatos()">Ver Preguntas</input>
			<div align ="center" id = "totalPreguntas">
				<br><p>Total de preguntas: <?php echo $cantidad; ?> </p>
				<p>Total de preguntas realizadas por ti: <?php echo $cantidadUsuario; ?> </p>

			</div>
			<div align="center" id = "preguntasXML" >

			</div>

        </form>

	</div>
    </section>

	<footer  class='main' id='f1'>
		<a href='https://github.com/tszemzo/proyectoSW'>Link GITHUB</a>
	</footer>
    </div>
</body>
</html>
<script language = "javascript" defer="defer">
	XMLHttpRequestObject = new XMLHttpRequest();
	XMLHttpRequestObject.onreadystatechange = function()
	{
		if (XMLHttpRequestObject.readyState==4)
		{
			var obj = document.getElementById('preguntasXML');
			var respuesta=XMLHttpRequestObject.responseXML;
			var totalRespuestas = respuesta.getElementsByTagName('assessmentItem').length;
			var cabecera =
			'<table border=1 > <tr> <th> Autor </th> <th> Enunciado </th> <th> Respuesta correcta </th></tr>';
			var tabla = '<tr>';
			var hayDatos = false;

			for(var i = 0; i< totalRespuestas; i++){
				if(respuesta.getElementsByTagName("assessmentItem")[i].getAttribute("author") == document.getElementById("dirCorreo").value)
				{
					var autor =respuesta.getElementsByTagName('assessmentItem')[i].getAttribute("author");
					var pregunta = respuesta.getElementsByTagName('p')[i].childNodes[0].nodeValue;
					var respuestaCorrecta = get_firstchild(respuesta.getElementsByTagName('correctResponse')[i]);
					respuestaCorrecta= respuestaCorrecta.childNodes[0].nodeValue;
					tabla = tabla  +
							'<td>' + autor + '</td>' +
							'<td>' + pregunta + '</td>' +
							'<td>' + respuestaCorrecta + '</td>' + '</td></tr>';
					hayDatos = true;
				}

			}

			var Tablafinal = cabecera + tabla + '</td></table>';
			if(hayDatos == true){
				obj.innerHTML =Tablafinal;
			}
			else{
				obj.innerHTML = "No hay preguntas insertadas por tu usuario";
			}
		}
	}
	function pedirDatos()
	{
		XMLHttpRequestObject.open("GET","preguntas.xml?q="+ new Date().getTime());
		XMLHttpRequestObject.send(null);
	}

	function get_firstchild(n) {
    var x = n.firstChild;
    while (x.nodeType != 1) {
        x = x.nextSibling;
    }
    return x;
}


	function insertarEnTabla(autor, tema, pregunta, respuestaCorrecta, respuestaInorrecta1, respuestaInorrecta2, respuestaInorrecta3)
	{

		var tabla = tabla +'<tr>' +
						'<td>' + autor + '</td>'
						'<td>' + tema + '</td>'
						'<td>' + pregunta + '</td>'
						'<td>' + respuestaCorrecta + '</td>'
						'<td>' + respuestaInorrecta1 + '</td>'
						'<td>' + respuestaInorrecta2 + '</td>'
						'<td>' + respuestaInorrecta3 + '</td></tr>';
		return tabla;

	}
	function insertarPreg(){
		var data = $("#fpreguntas").serialize();
		var correo = document.getElementById("dirCorreo").value;
		$.ajax({
			data: data,
			type: "POST",
			url: "InsertarPregunta.php?email="+correo,
			cache : false,
			success: function(data) {
				//nuevo
				var bien = validarDatos();
				if(bien == true){
					pedirDatos();
					document.getElementById("fpreguntas").reset();
				}
				else{
					alert("No se guardo");
					return false;
				}

			},
			error: function() {
				alert('error handling here');
			}
		});
	}

	function validarDatos(){
        // valida campos nulos
        var form = $('#fpreguntas');
		var mensaje = "";
		if(validarNulos(form)) {
			mensaje ="\nAlgun campo obligatorio vacio";
            alert(mensaje);
			return false;
        }
        // valida el correo con el formato correspondiente
        var correoRegex = /^([a-zA-Z_.+-])+[0-9]{3}\@ikasle.ehu.eus+$/;
        var correo = $("#dirCorreo");
        if (!validarRegex(correo,correoRegex,"Correo en formato erroneo" + mensaje)){
			return false;
		}
		else {
		    $('#dirCorreo').removeClass("error");
		}
        // valida la pregunta con el largo correspondiente
		var pregunta = $("#pregunta");
        var preguntaRegex = /^([a-zA-Z_.?+-¨ ¨]){10,}$/;
        if(!validarRegex(pregunta,preguntaRegex,"La pregunta debe tener al menos 10 caracteres!" + mensaje)) {
			return false;
		}
		else {
		    $('#pregunta').removeClass("error");
		}
        // valida la complejidad que debe estar en el intervalo [0-5]
        var complejidad = $("#complejidad");
        var complejidadRegex = /^[0-5]$/;
        if (!validarRegex(complejidad,complejidadRegex,"La complejidad debe estar entre 0 y 5!" + mensaje)){
		    errorCampo($('#complejidad'));
		    return false;
		}
		alert("Pregunta enviada correctamente.");
		return true;
	}

    function validarNulos(obj){
        //Función para comprobar los campos de texto
        algunoVacio = false;
        obj.find("input").each(function() {
            var $this = $(this);
            if( $this.val().length <= 0 )
			{
				algunoVacio = true;
				errorCampo($this);
			}
			else{
				($this).removeClass("error");
			}
        });
        return algunoVacio;
    }

    function validarRegex(unInput,expresion,mensaje){
        // funcion que recibe un input, una expresion regular y un mensaje,
        // y chequea que el input cumpla la expresion, en caso de que no sea
        // asi, lanza el mensaje de error introducido.
		if(!expresion.test(unInput.val())){
			alert(mensaje);
			errorCampo(unInput);
            return false;
		}
        return true;
    }
	function errorCampo(campoConError){
		campoConError.addClass('error');
	}
	function ActualizarDatos(){


	}


	$('#url').click(function(){
	alert("Agur!")});

</script>
