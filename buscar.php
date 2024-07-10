<?php 
	require_once("database.php");

	$consultaBusqueda = $_POST['valorBusqueda'];

	//Filtro anti-XSS
	$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
	$caracteres_buenos = array("&lt;", "&gt;", "&quot;", "&#x27;", "&#x2F;", "&#060;", "&#062;", "&#039;", "&#047;");
	$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

	$mensaje = "";

	if (isset($consultaBusqueda)) {
		$dbh = Database::getInstance();

		$consulta = $dbh -> prepare("SELECT * FROM autoexcluidos_scj WHERE ae_rut = :v_r");
		//$consulta->bindValue(':v_r',"55555555-5");
		$consulta->bindValue(':v_r',$consultaBusqueda);
		$consulta->execute();

		$resultado = $consulta->fetch(PDO::FETCH_ASSOC);

		if ($resultado) {

			$nombre = $resultado["ae_nombre"];
			$apellido = $resultado["ae_apellido_m"];
			//$domicilio = $resultado["ae_dom_part"];
			$email = $resultado["ae_email"];
			$fono = $resultado["ae_tel"];

			$mensaje .= '
			<p>
			<strong id=info>Cliente Autoexcluido</strong><br>
			<form action="enviar.php" method="post">
			<input type="hidden" name="nombre" value="'.$nombre.'"">
			<strong>Nombre:  </strong>' . $nombre . ' <br>
			<input type="hidden" name="apellido" value="'.$apellido.'"">
			<strong>Apellido: </strong> ' . $apellido . '<br>
			<input type="hidden" name="domicilio" value="'.$domicilio.'"">
			<strong>Direccion: </strong> ' . $domicilio . '<br>
			<input type="hidden" name="email" value="'.$email.'"">
			<strong>Email: </strong> ' . $email . '<br>
			<input type="hidden" name="fono" value="'.$fono.'"">
			<strong>Teléfono: </strong> ' . $fono . '<br><br>
			<input type="submit" value="Enviar Correo" name="" id="btn-enviarC">
			</form>
			</p>';
			//$this->mensaje="Ingresado Exitosamente!!!";
			
		}
		else{

			$mensaje = "<p id=infoOk>Cliente habilitado</p>";
		}
		
	}
	echo $mensaje;
 ?>