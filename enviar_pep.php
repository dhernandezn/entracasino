<?php 
	
	
	//echo $digo;
	//
	//
	$nombre1 = $_POST['nombre'];
	$rut = $_POST['rut'];

	$digo="";

	date_default_timezone_set("America/Santiago");
	echo date ("H:i",time());
	//echo $nombre1;

	try {
		
		$digo .= 'El Cliente '.$nombre1.' es un cliente PEP y a ingresado a nuestra unidad, favor tomar las medidas correspondientes.
			
			Nombre Cliente: '.$nombre1.'
			RUT: '.$rut.'
			 
			 ';

		$destino = "d.hernaranjo@gmail.com"; //revisar correo del esteban
		$desde = "From: "."EmailHN";
		$asunto = "Alerta Cliente PEP";
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

