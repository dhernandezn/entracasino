<?php 
	
	
	//echo $digo;
	//
	//
	$nombre1 = $_POST['nombre'];
	$email = $_POST['email'];
	$fono = $_POST['fono'];
	$fono2 = $_POST['fono2'];
	$digo="";

	date_default_timezone_set("America/Santiago");
	echo date ("H:i",time());
	//echo $nombre1;

	try {
		
		$digo .= 'Atención!!! Se reporta la presencia de un Cliente Auto Excluido.

			 Nombre Autoexcluido: '.$nombre1.'
			 Email: '.$email.'
			 Teléfono: '.$fono.'
			 Teléfono2: '.$fono2.'
			 ';

	
				//echo $digo;

				//if (isset($_POST['asunto'])&& !empty($_POST['asunto']) && isset($_POST['mensaje']) && !empty($_POST['mensaje'])) 
	
		$destino = "d.hernaranjo@gmail.com"; //revisar correo del esteban
		$desde = "From: "."EmailHN";
		$asunto = "Alerta Autoexcluido";
		$mensaje = $digo; //$_POST['mensaje'];
		
		mail($destino,$asunto,$mensaje,$desde);

		echo "Mensaje enviado";
		header("location: index.php");
	} catch (Exception $e) {
		echo "ERROR: " . $e->getMessage();
	}

	
	/*else{
		echo "Problemas en el envio";
	}*/


//ENVIAR INFORMACION DEL CLIENTE AUTO EXC   -_JM?quantity=1
 ?>

