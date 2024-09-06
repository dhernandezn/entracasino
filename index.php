<?php  
session_start();
require_once("database.php");
require_once("validar.php");


/*
if (isset($_SESSION['rut'])) {
	//echo '<script> window.location="clientes.php";</script>';
	$profile = $_SESSION['rut'];
}
*/

$aperturaCasino = "12:00";
$cierreCasino = "06:00:00";
date_default_timezone_set("America/Santiago");
$tiempoActual = date ("d-m-Y");
$hora_FechaActual = date("d-m-Y H:i:s",Time());
//$tiempoActual = "29-08-2023 11:59:00";
$JornadaInicio = date ("d-m-Y",time())." ".$aperturaCasino;
$jornadaFin = date("d-m-Y",strtotime("+1 day"))." ".$cierreCasino;
$hora = date ("H:i",time());
//echo "Tiempo Actual: $tiempoActual </br>";
//echo "Jornada Inicial: $JornadaInicio </br>";
//echo "Jornada Fin: $jornadaFin </br>";
if($tiempoActual >= $JornadaInicio && $tiempoActual <= $jornadaFin){
	//echo "Dentro de la jornada";
}

//exit();

try {

	
	//$respuesta_cli = "Cantidad de Personas Ingresadas: ".$conteo -> contarEntradas();
	 //echo "fecha: ".$hoy;
	 //echo "Personas Ingresadas: ".$cli_ingre;


if(isset($_POST['actualizar'])){

	$fec = $_POST["fecha_select"];
	$conteo = new Consultas();
	$cli_ingre = $conteo -> buscarEntradasxJornada($fec,"12:00:00","06:00:00");
	
	$respuesta_cli2 = "Total Personas $fec: ".$cli_ingre;

	
}
$mensaje = null;

if (isset($_POST['insertar'])) {
	$model = new Consultas();
	$model -> busqueda = htmlspecialchars($_POST["rut"]);
	$model -> hora = htmlspecialchars($_POST["hora"]);
	$model -> fecha = htmlspecialchars($_POST["fecha"]);
	$model -> fecha_hora = htmlspecialchars($_POST["fecha_hora"]);//fecha_horaR
	$model -> fecha_horaR = htmlspecialchars($_POST["fecha_horaR"]);
	//$model -> 
	$model -> form_cont();
	$mensaje = $model -> mensaje; 

}
if (isset($_POST['insertar2'])) {
	$model2 = new Consultas();
	$model2 -> rut_cli = htmlspecialchars($_POST["rut_cli"]);
	$model2 -> hora = htmlspecialchars($_POST["hora"]);
	$model2 -> fecha = htmlspecialchars($_POST["fecha"]);
	$model2 -> promocheck = htmlspecialchars($_POST["promocheck"]);
	
	
	$model2 -> ingresar_entrada();
	$mensaje2 = $model2 -> mensaje2;
	$mensaje3 = $model2 -> mensaje3;
	$mensajeP = $model2 -> mensajep;
}
if (isset($_POST['en_espera'])) {
	$modele = new Consultas();
	$modele -> id_log = htmlspecialchars($_POST["id_log"]);
	$modele -> pausar_entrada();
	$mensaje2 = $model2 -> mensaje2;

}elseif(isset($_POST['ingresar_pasaporte'])){
	$model = new Consultas();
	$model -> fecha_hora = htmlspecialchars($_POST["fecha_hora"]);
	$model -> ingresar_entrada_pasp();
	$mensaje2 = $model -> mensaje2;
}

$conteo = new Consultas();
	//echo "fecha actual ".$tiempoActual;
	//$jornada2 = $conteo -> obtenerJornada("12:00:00","06:00:00",$hora_FechaActual);
	//$jornada2 = $conteo -> obtenerJornada("12:00:00","06:00:00","13-09-2023 00:59:00");
	echo $jornada2;
	$cli_ingre = $conteo -> contarEntradasHoy($tiempoActual,"12:00:00","06:00:00");
	$respuesta_cli = "Cantidad de Personas Ingresadas Hoy: ".$cli_ingre;
} catch (PDOException $e) {
	 echo "ERROR: " . $e->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>##Revisión De Clientes##</title>
	<link rel="apple-touch-icon" sizes="180x180" href="css/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="css/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="css/favicon/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/jquery-ui.min.css">
	<link rel="stylesheet" href="css/style.css">


	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.4.5.0.js"></script>
	<script src="js/js.js"></script>
	
	<script>
		function pasp_copy(){
			let pasaporte = document.getElementById('pasp_cli').value;
			document.getElementById('pasp_cli_h').value = pasaporte;
		}
	
	</script>
	<script>
	function fecha(){
		var calendario = document.getElementById('datepicker').value;
		console.log(calendario);
	};

	</script>

	<script>
	function mirar(){
		var ob_id = document.getElementById('id_cli').value;
		var send_id = document.getElementById('id_log').value = ob_id;
		console.log(ob_id+"->"+send_id);
		var ob_id_c = document.getElementById('id_cliente').value;
		var send_id_c = document.getElementById('id_log2').value = ob_id_c;
		console.log(ob_id_c+"->"+send_id_c);
	};
	</script>
	<script>

		function obt_rut()
		{
			let codigo = document.getElementById("busqueda").value;
			console.log(codigo);
			let buscar_rut = codigo.indexOf("RUN");
			console.log("Buscar RUN: "+buscar_rut);
			if(buscar_rut > 0)
			{
				buscar_rut = buscar_rut + 4;
			}else{
				buscar_rut = 0;
			}
			let fin_rut = codigo.indexOf("&type");
			console.log(fin_rut);
			if(fin_rut > 0)
			{
				fin_rut = fin_rut
			}else{
				fin_rut = 15
			}
			let rut = codigo.slice(buscar_rut,fin_rut);
			console.log(buscar_rut);
			console.log("rut: "+rut);
			document.f.rut.value = rut;
			//document.getElementById("rut").val = rut;
		}

		function cap(){
			var a = 0;
			a = document.getElementById("val1").value;
			//b = document.getElementById("valu1").value;
			console.log(a);

			switch (a) {
				case '1':
					$("#modal_ae").modal({backdrop:'static',keyboard:false});
				   	console.log("AUTOEX");
					break;
				case '2':
					$("#modal_pep").modal({backdrop:'static',keyboard:false});				
					console.log("PEP");
					mirar();
					break;
				case '3':
					console.log("NORMAL");
					//modal_pr
					$("#modal_entrada").modal({backdrop:'static',keyboard:false});
					mirar();
					break;
				case '4':
					console.log("PROHIBIDO");
					$("#modal_pr").modal({backdrop:'static',keyboard:false});
					mirar();
				case '5':
					//window.alert("Documento ya escaneado!!");
					console.log("YA ESCANEADO");
					$("#modal_dup").modal({backdrop:'static',keyboard:false});
					copiarValor();
					mirar();
				case '6':
					console.log("SOSP");
					$("#modal_sosp").modal({backdrop:'static',keyboard:false});
					mirar();
				break;
		
				default:
					break;
			}

		};
		function copiarValor() {
            // Obtiene el valor del primer input
            let valor = document.getElementById('condicion').value;

            // Asigna ese valor al segundo input
            document.getElementById('condicion_cliente').textContent = valor;
        }
		
		function checkRut(){
			var rut = document.getElementById("rut").value.replace('.','');
			
			var valor1 = rut.indexOf(".");
			
			console.log($valor1);

				while(valor1!= -1){
					//console.log("listo");
					rut = rut.replace('.','');
					//console.log(rut);
					valor1 = rut.indexOf(".");
				}

		
			rut=rut.replace('-','');
			
			var valor2 = rut.indexOf("-");
		

			while(valor2!= -1){
				
				
				valor2 = rut.indexOf("-");
			}

		
			rut=rut.replace('/','');
			var valor3 = rut.indexOf("/");

			while(valor3!= -1){
				rut = rut.replace('/','');
				valor3 = rut.indexOf("/");
			}
		

		
			var cuerpo = rut.slice(0,-1);
			var dv = rut.slice(-1).toUpperCase();
			

			
			rut = cuerpo+"-"+dv;

		
			if(cuerpo.length < 7) {
			
			
			}

			var suma = 0;
			var multiplo = 2;

				for(var i=1;i<=cuerpo.length;i++) {
			
					var index = multiplo * rut.charAt(cuerpo.length - i);
					
					suma = suma + index;
				
						if(multiplo < 7) {
							multiplo = multiplo + 1; 
							
						} else {
							multiplo = 2; 
							
						}

				}
			var dvEsperado = 11 - (suma % 11);
			
			dv = (dv == 'K')?10:dv;
			dv = (dv == 0)?11:dv;
  
			if(dvEsperado != dv) {
				document.getElementById("erroneo").innerHTML = "RUT Inválido";
				document.f.disabled = true;
				console.log("rut inv");
			}else{
				document.f.disabled=false;
			}
		}
	</script>
	
  	<script type="text/javascript">
		function mens2(){
			
			var c = document.getElementById("msj2").value;
			document.getElementById("msj2").innerHTML= "Enviando Correo, por favor espere...";
			document.getElementById("msj2").value= "Enviando Correo, por favor espere...";
			console.log("Enviando Correo, por favor espere...");
			

			
		}
  	</script>
	<script type="text/javascript">
		function tomarmsj(){
			var msj = document.getElementById("msj2").value;

			if (msj=="Enviando Correo, por favor espere...") {
				document.getElementById("downloadButton").disabled=true;
			}
		}
	</script>
  
	<style>
		#progressbar {
			margin-top: 20px;
			position: absolute;
		}
		
		.progress-label {
			font-weight: bold;
			text-shadow: 1px 1px 0 #fff;
		}
		
		.ui-dialog-titlebar-close {
			display: none;
		}

	</style>



	<script type="text/javascript">
		function mostrar(){
		document.getElementById("contenedor_carga").style.display = 'inline';
		console.log("dsdsds");
		}
	</script>
	<!--animacion de cargando-->

	<style type="text/css">

		*,*:after, *:before{
		margin: 0;
		padding: 0;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		}
		#contenedor_carga{
		background-color: rgba(250,240,245,0.9);
		height: 100%;
		width: 100%;
		position: fixed;
		-webkit-transition: all 1s ease;
		-o-transition: all 1s ease;
		transition: all 1s ease;
		z-index: 10000;

		}
		#carga{

		border: 15px solid #ccc;
		border-top-color: #f4266A;
		border-top-style: groove;
		height: 100px;
		width: 100px;
		border-radius: 100%;

		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto;

		-webkit-animation: girar 1.5s linear infinite;
		-o-animation: girar 1.5s linear infinite;
		animation: girar 1.5s linear infinite;
		}
		@keyframes girar{
		from {transform: rotate(0deg);}
		to{transform: rotate(360deg);};
		}
	</style>
	<script>
		window.onload=cap;
	</script>
	<script>
  $( function() {
    $( "#datepicker" ).datepicker({
		dateFormat: "dd-mm-yy"
	});
  } );
  </script>
  <script>
	function dirigirA(){
		window.location="subir.php"
	}
	document.addEventListener("keydown", e =>{
		if(e.ctrlKey && e.altKey && e.key.toLowerCase() === "6"){
			dirigirA()
		}
	})
  </script>
</head>
<body>
	<div class="contenedor">
		<header>
			<span class="titulo"><h2 class="main_title">Consultar Cliente</h2></span>
		</header>
		<div class="cabecera">
			<!-- <a href="tickets.php" class="btn btn-warning" id="btn-entr" >Entradas en Espera</a> -->
			<a href="tickets.php" id="btn-entr"><i class="material-icons" style="font-size:35px" data-toggle="tooltip" title="Clientes sin Entrada">perm_contact_calendar</i></a>
			<div id="info_clientes">
				<p>
					<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						<label for="">Fecha</label>
						<input type="text" id="datepicker" name="fecha_select" value="" required autocomplete="off">
						<input type="hidden" name="actualizar">
						<button onclick="fecha()" class="btn btn-warning">Actualizar</button>
					</form> 
				</p>
				<span>Jornada de Hoy: <?php echo $jornada2;?></span><br>
				<span><?php echo $respuesta_cli;?></span></span><br>
				<span><?php echo $respuesta_cli2;?>
			</div>
			<br><br>
		</div>
		<section>
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" method="post" enctype="multipart/form-data" name="f" autocomplete="off">
				
				<input type="text" name="busqueda" id="busqueda" pattern="[0-9-k-K]{1,11}" value="" placeholder="xxxxxxxx-x" autocomplete="off" required autofocus>
				<input type="hidden" name="rut" id="rut" value="">
				<input type="hidden" name="hora" id="hora" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("H:i",time()); ?>">
				<input type="hidden" name="fecha" id="fecha" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("d-m-Y",time()); ?>">
				<input type="hidden" name="fecha_hora" id="fecha_hora" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("d-m-Y H:i:s",time()); ?>">
				<input type="hidden" name="fecha_horaR" id="fecha_horaR" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("Y-m-d H:i:s",time()); ?>">
				<br><br><br>
				<div class="#">
					<input type="hidden" name="insertar">
					<input type="submit" class="boton_cons" onclick="obt_rut();checkRut();" name="login" value="Consultar"><br><br>
					<!--<strong><?php //echo $mensaje; ?></strong>-->	
				</div>
			</form><br>

			<div id="respuesta">
				
				<strong id="mensajes" value="<?php echo $mensaje; ?>"><?php echo $mensaje; ?></strong>
				<strong id="mensajes"  value="<?php echo $mensaje2; ?>"><?php echo $mensaje2; ?></strong>
				<strong id="mensajes" value="<?php echo $mensaje3; ?>"><?php echo $mensaje3; ?></strong>
			</div>
			<p id="erroneo"></p>
			<div id="resultadoBusqueda"></div>
				<br>
			<strong class="msj_rut">Por favor ingrese el rut del cliente luego presione el botón consultar</strong>
			<br>
			<a href="#" data-toggle="modal" data-target="#modal_pasaporte">-> Cliente con pasaporte <-</a>
		</section>
		<div id="mensaje_act">
			<?php 
			
			$archivo 		= "json/log.txt";
			$filas 			= file($archivo);
			$ul_linea 		= count($filas) -1;
			$ul_linea_escr 	= $filas[$ul_linea];
			
			?>
			
			<p class="msj_actualización"><span id="info_msj" class="material-icons">info</span><?php echo $ul_linea_escr;?></p>
		</div>
	</div>
	
	<!-- Modal Autoexcluidos -->
	<div class="modal fade" id="modal_ae">
		<div class="modal-dialog">
	    	<div class="modal-content">
	        	<div class="modal-header">
	        		<h3 class="tlt-mod">CLIENTE AUTOEXCLUIDO <i class="material-icons">privacy_tip</i></h3>
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
	     	 	</div>
	        	<div class="modal-body">
				<span style="color:green">"Estimado según sistema SCJ el cliente a presentado una autoexclusión voluntaria 
					por lo cual no puedo vender entrada o permitir su ingreso, favor espere al encargado" <br> (Enviar Aviso)</span>				
				</div>
			</div>
	  	</div>
	</div>
		<!-- Modal PEP -->
	<div class="modal fade" id="modal_pep">
		<div class="modal-dialog">
	    	<div class="modal-content">
	        	<div class="modal-header">
	        		<h3 class="tlt-mod">Cliente PEP <i class="material-icons">privacy_tip</i></h3>
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
	     	 	</div>
	        	<div class="modal-body">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						<strong class="text_modal">"Cliente PEP" Vender entrada y enviar aviso</strong><br><br>
						<i class="material-icons" id="warning_ico">warning</i><br>
						<button type="submit" name="pepDetected" class="btn btn-dark">Aceptar</button>
					</form><br><hr>			
				</div>
			</div>
	  	</div>
	</div>
	<!-- Modal SOSPECHOSO -->
	<div class="modal fade" id="modal_sosp">
		<div class="modal-dialog">
	    	<div class="modal-content">
	        	<div class="modal-header">
	        		<h3 class="tlt-mod">RUT SOSPECHOSO <i class="material-icons">privacy_tip</i></h3>
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
	     	 	</div>
	        	<div class="modal-body">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						<strong class="text_modal">El rut ingresado corresponde a un cliente sospechoso, favor dar aviso a CCTV</strong><br><br>
						<i class="material-icons" id="warning_ico">warning</i><br>
						<button type="submit" name="pepDetected" class="btn btn-dark">Aceptar</button>
					</form><br><hr>			
				</div>
			</div>
	  	</div>
	</div>
	<!-- Modal Clientes Prohibidos -->
	<div class="modal fade" id="modal_pr">
		<div class="modal-dialog">
	    	<div class="modal-content">
	        	<div class="modal-header">
	        		<h3 class="tlt-mod">CLIENTE PROHIBIDO <i class="material-icons">privacy_tip</i></h3>
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
	     	 	</div>
	        	<div class="modal-body">
					<span class="text_modal">Estimado cliente usted tiene una suspención de ingreso al casino por circular 108
						 de la Superintendencia de Casinos de Juego, por lo que no puede entrar a nuestro Casino.</span>				
				</div>
			</div>
	  	</div>
	</div>
	<!-- Modal Entrada Cliente Normal -->
	<div class="modal fade" id="modal_pasaporte">
		<div class="modal-dialog">
	    	<div class="modal-content">
	        	<div class="modal-header">
	        		<h3 class="tlt-mod">Ingreso de Cliente con Pasaporte</h3>
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
	     	 	</div>
	        	<div class="modal-body">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						
						<div class="form-group">
							<label for="">Numero de Pasaporte: </label>
							<input type="text" name="pasp_cli" id="pasp_cli" placeholder="xxxxxx-x" class="form-control" oninput="pasp_copy()" autofocus required><br>
							<hr>
							<strong id="mensajes" ><?php echo $mensaje3; ?></strong>
						</div>
						<strong style="display: none" id="mensajes"><?php echo $mensaje2; ?></strong>
						<div class="btn-group-justified">
							<strong id="msj2" name="msj2" value="1" ></strong>
							<input type="hidden" name="hora" id="hora" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("H:i",time()); ?>">
							<input type="hidden" name="ingresar_pasaporte">
							<input type="hidden" name="fecha" id="fecha" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("d-m-Y",time()); ?>">
				<input type="hidden" name="fecha_hora" id="fecha_hora" value="<?php 
				date_default_timezone_set("America/Santiago");
				echo date ("d-m-Y H:i:s",time()); ?>">
							<input type="submit" class="btn btn-ingresar" name="insert" value="Ingresar Entrada">
							<!-- <a href="index.php" role="button" class="btn btn-danger">Cerrar</a> -->
					</form>
					</div>
					<br>
				</div>
	      	</div>
	  	</div>
	</div>
	<!-- MODAL DUPLICIDAD DE DCTO -->
	<div class="modal fade" id="modal_dup">
		<div class="modal-dialog">
	    	<div class="modal-content">
	        	<div class="modal-header">
	        		<h3 class="tlt-mod">DOCUMENTO YA ESCANEADO<i class="material-icons">privacy_tip</i></h3>
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
	     	 	</div>
	        	<div class="modal-body">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						<strong class="text_modal">El documento ah sido escaneado por 2da vez durante la jornada</strong><br><br>
						<strong id="condicion_cliente" style="  color: #dfff00;"></strong><br>
						<i class="material-icons">person_pin</i><br>
						<button type="submit" name="pepDetected" class="btn btn-dark">Aceptar</button>
					</form><br><hr>			
				</div>
			</div>
	  	</div>
	</div>
</body>
</html>
