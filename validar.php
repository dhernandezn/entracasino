<?php 

	require_once("database.php");
	//require_once("consultas.php");
	error_reporting(0);
	
class Consultas
{
	public $hoy;
	public $mensaje;
	public $mensaje2;
	public $mensaje3;
	public $mensajeP;
	public $mensajep;
	public $busqueda;
	public $hora;
	public $fecha;
	public $fecha_hora;
	public $fecha_horaR;
	public $rut_cli;
	public $promocheck;
	public $id_log;
	
	// public function __construct(){
	
	// 	//$this -> mensaje = "";
	// }
	
	public function form_cont(){

	//revisar que no se envíe el formulario vacío

	$val = $_POST["rut"];
	$fecha = $_POST["fecha"];
	$this-> fecha_hora = $_POST["fecha_hora"];
	// echo $val;
	// echo $fecha;exit();
	$val = strtolower($val);
	$listar10 = $this -> revisarDobleIngreso();
	//print_r($listar10);
	//echo "\t";exit();
	foreach($listar10 as $rutLista){
		if($rutLista["rut_ingre"] === $val && $rutLista["fecha"] === $fecha){
			return $this -> mensaje = "<input type='hidden' name='val666' value='5' id='val1'> Cliente Ya Registrado <br><br> 
								<img src='img/check.png' id='chk'> ";
			// echo "RUT YA ENTRÖ";
			// exit();
		}

	}
	// print_r($listar10);
	// exit();
	$dbh = Database::getInstance();

	$val2 = $_POST["rut"];
	//echo $val2;

	$array = explode("-",$val);
	//print_r($array);

	$contador = count($array);
	//echo "contador: ".$contador;
	if ($contador > 1) {
		
		if($val == null){
		//echo "No ah ingresado RUT";
		$this -> mensaje = "No ah ingresado RUT";
		}else{
			if (empty($val2)) {
				//echo "El rut está vacío";
				$this-> mensaje = "Rut Inválido <br><br> <img src='img/cruz.png' id='chk'>";
				# code...
			}else{
				$tam_rut=strlen($val2);
				//1111111-1
				if ($tam_rut< 7) {
					//echo "Rut incompleto";
					$this->mensaje="Rut Incompleto o Inválido <br><br> <img src='img/cruz.png' id='chk'>";
				}else{
					/////////////////////////// validar Rut /////////////////////////////////

					$val2 = preg_replace('/[^k0-9]/i', '', $val2);
					//echo "valor del rut: ".$val2;
					$dv  = substr($val2, -1);
					$numero = substr($val2, 0, strlen($val2)-1);
					$i = 2;
					$suma = 0;
					foreach(array_reverse(str_split($numero)) as $v){
						if($i==8)
							$i = 2;
						$suma += $v * $i;
						++$i;
					}
					$dvr = 11 - ($suma % 11);
					
					if($dvr == 11)
						$dvr = 0;
					if($dvr == 10)
						$dvr = 'K';
					if($dvr == strtoupper($dv)){
				
						$rut_new=$numero."-".$dvr;
				
			
						$val = 0;

						$consulta = $dbh -> prepare("INSERT INTO log(rut_ingre,hora,fecha,autoexc,estado_ticket,fecha_hora,fecha_hora_)VALUES(:v_r,:v_h,:v_f,:v_ae,:v_et,:v_fh,:v_fhr)");
						$consulta -> bindValue(':v_r', $_POST['rut']);
						$consulta -> bindValue(':v_h', $_POST['hora']);
						$consulta -> bindValue(':v_f', $_POST['fecha']);
						$consulta -> bindValue(':v_fh', $_POST['fecha_hora']);
						$consulta -> bindValue(':v_fhr', $_POST['fecha_horaR']);
						$consulta -> bindValue(':v_ae',0);
						$consulta -> bindValue(':v_et',0);
						$consulta -> execute();

						
						//Consulto si es Autoexcluido
						$dbh = Database::getInstance();
						$consulta1 = $dbh -> prepare("SELECT autoexcluidos_scj.ae_nombre as nombre
							FROM autoexcluidos_scj
							WHERE autoexcluidos_scj.ae_rut = :v_n");
						$consulta1 -> bindValue(':v_n', $_POST['rut']);
						$consulta1 -> execute();
						$resultado = $consulta1 -> fetch(PDO::FETCH_ASSOC);
						$nombre = $resultado['nombre'];
						$email = $resultado['email'];
						$tel = $resultado['tel'];
						$tel2 = $resultado['telmo'];
				
						if($resultado){
							$consulta3 = $dbh -> prepare("UPDATE log SET autoexc = :v_2, n_entrada = :v_n_e, cli_pep = :v_nopep,
							 cli_prohibido = :v_pro, cli_sospechoso = :v_sos WHERE rut_ingre = :v_n");
							$consulta3 -> bindValue(':v_n', $_POST['rut']);
							$consulta3 -> bindValue(':v_2', $auto = "si");
							$consulta3 -> bindValue(':v_n_e', $ent = 0);
							$consulta3 -> bindValue(':v_nopep', $ent = 'no');
							$consulta3 -> bindValue(':v_pro', $ent = 'no');
							$consulta3 -> bindValue(':v_sos', $ent = 'no');
							$consulta3 -> execute();
							// Agregar estados de cli_prohibido y cli_sospechoso cuando no lo es
							//echo "Atención!!! Se reporta la presencia de ". $resp ." Cliente Auto excluido";
							$this -> mensaje = "<input type='hidden' name='val1' value='1' id='val1'> Cliente Autoexcluido
							
							<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar.php'>
									<input type='hidden' name='nombre' value='$nombre'>
									<input type='hidden' name='email' value='$email'>
									<input type='hidden' name='fono2' value='$tel2'>
									<input type='hidden' name='fono' value='$tel'>

							</form>";
							
						}
						else{ //preguntare si es pep
							$rut_Api = str_replace("-","",$_POST['rut']);
							$consultaApi = $this->consultarPEP($rut_Api);
							// if(isset($consultaApi)){
							// 	//echo " Tengo un array ";
							// }else{
							// 	//echo " NO tengo un array ";
							// }
							if(is_array($consultaApi)){ 
								echo "buscando con (API)";
								$myjson = json_encode($consultaApi);
								$datos = json_decode($myjson,true);
								//print_r($datos['listas']['pepChile']['info']);
								// if(empty($datos['listas']['pepChile']['info'])){
								// 	echo " PEP pero sin datos";
								// }else{
								// 	print_r($datos['listas']['pepChile']['info']);
								// }
								// Comprueba que contenga un array (Conexión API PEP)
								if(empty($consultaApi)||empty($datos['listas']['pepChile']['info'])){
									echo " Cliente no es PEP (API)";//Si array está vacio buscó pero no encontró rut
									$this->mensajeP = '<label for="">asdads</label>';
									// $this->mensaje2="<input type='hidden' name='val1' value='0' id='val1'> Cliente Puede Ingresar <br><br> <img src='img/check.png' id='chk'>";
									$consulta4 = $dbh -> prepare("UPDATE log SET autoexc = :v_2, cli_pep = :v_3, estado_ticket = :v_4, cli_sospechoso = :v_5 WHERE rut_ingre = :v_n");
									$consulta4 -> bindValue(':v_n', $_POST['rut']);
									$consulta4 -> bindValue(':v_2', $auto = "no");
									$consulta4 -> bindValue(':v_3', $auto = "no");
									$consulta4 -> bindValue(':v_4', $auto = 1);
									$consulta4 -> bindValue(':v_5',$auto = "no");
									$consulta4 -> execute();
									
									//echo " Cliente puede ingresar ";
									$buscarid = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
									$buscarid -> bindValue(':v_rut',$_POST['rut']);
									$buscarid -> execute();
									$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
									$id_en = $idencontrada["id_log"];
									
									$this -> mensaje = "<input type='hidden' name='val1' value='3' id='val1'> Cliente Puede Ingresar <br><br> 
									<img src='img/check.png' id='chk'>
									<input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
									<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'> ";
								}else{
									echo " Cliente es PEP (API)";
									//echo "CLIENTE PEP"; //Encuentra rut como PEP
									$myjson = json_encode($consultaApi);
									$datos = json_decode($myjson,true);
									print_r($datos['listas']['pepChile']['info']);

									//exit();
									// $datos = json_decode($myjson,true);
									// $nombre = $datos['listas']['pepChile']['info']['name'];
									// $apellidoP = $datos['listas']['pepChile']['info']['fatherName'];
									// $apellidoM = $datos['listas']['pepChile']['info']['motherName'];
									// $posicion = $datos['listas']['pepChile']['info']['position'];
									// $datosArray = array(
									// 	"nombre" => $nombre,
									// 	"apellidoP" => $apellidoP,
									// 	"apellidoM" => $apellidoM,
									// 	"posicion" => $posicion
									// );
									// print_r($datosArray);
									$buscarid = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
									$buscarid -> bindValue(':v_rut',$_POST["rut"]);
									$buscarid -> execute();
									$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
									$id_en = $idencontrada["id_log"];
									
									$nombre = $resultado['nombre'];
									$rut = $resultado['rut'];
									$this -> mensaje="<input type='hidden' name='val1' value='2' id='val1'> Cliente PEPA <br> <span class='material-icons'>
									privacy_tip
									</span><input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
											<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'>					
														<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar_pep.php'>
																<input type='hidden' name='nombre' value='$nombre'>
																<input type='hidden' name='rut' value='$rut'>
														</form>";

								$consulta3 = $dbh -> prepare("UPDATE log SET cli_pep = :v_2, estado_ticket = :v_4 WHERE rut_ingre = :v_n");
								$consulta3 -> bindValue(':v_n', $_POST['rut']);
								$consulta3 -> bindValue(':v_2', $auto = "si");
								$consulta3 -> bindValue(':v_4', 1);
								$consulta3 -> execute();
								}
							}else{
								echo " BUSCANDO BD LOCAL ";
								$consultaPepLocal = $this->consultarPEP_local($_POST['rut']);
								
									if(!empty($consultaPepLocal)){
										echo "CLIENTE PEP (LOCAL) ";
									$buscarid = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
									$buscarid -> bindValue(':v_rut',$_POST["rut"]);
									$buscarid -> execute();
									$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
									$id_en = $idencontrada["id_log"];
	
									$nombre = $resultado['nombre'];
									$rut = $resultado['rut'];
									$this -> mensaje="<input type='hidden' name='val1' value='2' id='val1'> Cliente PEP <br> <span class='material-icons'>
									privacy_tip
									</span><input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
											<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'>					
														<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar_pep.php'>
																<input type='hidden' name='nombre' value='$nombre'>
																<input type='hidden' name='rut' value='$rut'>
														</form>";
	
									$consulta3 = $dbh -> prepare("UPDATE log SET cli_pep = :v_2, estado_ticket = :v_4 WHERE rut_ingre = :v_n");
									$consulta3 -> bindValue(':v_n', $_POST['rut']);
									$consulta3 -> bindValue(':v_2', $auto = "si");
									$consulta3 -> bindValue(':v_4', 1);
									$consulta3 -> execute();
									
									
								}else{
									echo " Cliente no es PEP (LOCAL)";
									$this->mensajeP = '<label for="">asdads</label>';
									// $this->mensaje2="<input type='hidden' name='val1' value='0' id='val1'> Cliente Puede Ingresar <br><br> <img src='img/check.png' id='chk'>";
								$consulta4 = $dbh -> prepare("UPDATE log SET autoexc = :v_2, cli_pep = :v_3, estado_ticket = :v_4, cli_sospechoso = :v_5 WHERE rut_ingre = :v_n");
								$consulta4 -> bindValue(':v_n', $_POST['rut']);
								$consulta4 -> bindValue(':v_2', $auto = "no");
								$consulta4 -> bindValue(':v_3', $auto = "no");
								$consulta4 -> bindValue(':v_4', $auto = 1);
								$consulta4 -> bindValue(':v_5',$auto = "no");
								$consulta4 -> execute();
								
								//echo " Cliente puede ingresar ";
								$buscarid = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
								$buscarid -> bindValue(':v_rut',$_POST['rut']);
								$buscarid -> execute();
								$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
								$id_en = $idencontrada["id_log"];
								
								$this -> mensaje = "<input type='hidden' name='val1' value='3' id='val1'> Cliente Puede Ingresar <br><br> 
								<img src='img/check.png' id='chk'>
								<input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
								<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'> ";
								}
							}
							//exit();
							//busco si es pep
							
							// busco el id de registro del rut como pep

							// Consulto si es cliente prohibido
							$consulta_proh = $dbh -> prepare ("SELECT nombre, rut FROM prohibidos WHERE rut = :v_n");
							$consulta_proh -> bindValue(':v_n', $_POST['rut']);
							$consulta_proh -> execute();
							$resultado_proh = $consulta_proh -> fetch(PDO::FETCH_ASSOC);


							if($resultado_proh){
								//echo "ENCONTRE PROHIBIDO";
							$buscarid = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut  ORDER BY id_log Desc LIMIT 1");
							$buscarid -> bindValue(':v_rut',$_POST["rut"]);
							$buscarid -> execute();
							$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
							$id_en = $idencontrada["id_log"];

								$this -> mensaje="<input type='hidden' name='val1' value='4' id='val1'> Cliente Prohibido <br> <span class='material-icons'>
								privacy_tip
								</span><input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
									<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'>";
									$consulta_proh = $dbh -> prepare("UPDATE log SET cli_prohibido = :v_1, autoexc = :v_2, cli_pep = :v_3 WHERE rut_ingre = :v_n");
									$consulta_proh -> bindValue(':v_n', $_POST['rut']);
									$consulta_proh -> bindValue(':v_1', $auto = "si");
									$consulta_proh -> bindValue(':v_2', $auto = "no");
									$consulta_proh -> bindValue(':v_3', $auto = "no");
									$consulta_proh -> execute();
							}
								
							//busco si es sospechoso
							$consulta_sosp = $dbh -> prepare ("SELECT nombre, rut FROM sospechosos WHERE rut = :v_n");
							$consulta_sosp -> bindValue(':v_n', $_POST['rut']);
							$consulta_sosp -> execute();
							$resultado_sosp = $consulta_sosp -> fetch(PDO::FETCH_ASSOC);
							// busco el id de registro del rut como pep
							$buscarid = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
							$buscarid -> bindValue(':v_rut',$_POST["rut"]);
							$buscarid -> execute();
							$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
							$id_en = $idencontrada["id_log"];

							$nombre = $resultado_sosp['nombre'];
							$rut = $resultado_sosp['rut'];

							if ($resultado_sosp) {
								//si encuentra el rut como sospechoso se crea envía formulario con los datos del cliente pep para luego
								//almacenarlos en el log
								// $this -> mensaje2="<input type='hidden' name='val1' value='2' id='val1'> Cliente SOSPECHOSO 666<br><br>";
								$this -> mensaje="<input type='hidden' name='val1' value='6' id='val1'> Cliente SOSPECHOSO <br> <span class='material-icons'>
								privacy_tip
								</span><input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
										<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'>						
													<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar_pep.php'>
															<input type='hidden' name='nombre' value='$nombre'>
															<input type='hidden' name='rut' value='$rut'>
													</form>";

								$consulta3 = $dbh -> prepare("UPDATE log SET cli_sospechoso = :v_2, estado_ticket = :v_4 WHERE rut_ingre = :v_n");
								$consulta3 -> bindValue(':v_n', $_POST['rut']);
								$consulta3 -> bindValue(':v_2', $auto = "si");
								$consulta3 -> bindValue(':v_4', 1);
								$consulta3 -> execute();
							}
							// else{
							// 	$this->mensajeP = '<label for="">asdads</label>';
							// 		// $this->mensaje2="<input type='hidden' name='val1' value='0' id='val1'> Cliente Puede Ingresar <br><br> <img src='img/check.png' id='chk'>";
							// 	$consulta5 = $dbh -> prepare("UPDATE log SET autoexc = :v_2, cli_sospechoso = :v_3, estado_ticket = :v_4 WHERE rut_ingre = :v_n");
							// 	$consulta5 -> bindValue(':v_n', $_POST['rut']);
							// 	$consulta5 -> bindValue(':v_2', $auto = "no");
							// 	$consulta5 -> bindValue(':v_3', $auto = "no");
							// 	$consulta5 -> bindValue(':v_4', $auto = 1);
							// 	$consulta5 -> execute();
								
								
							// 	//echo " Cliente puede ingresar ";
							// 	$buscaridp = $dbh -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
							// 	$buscaridp -> bindValue(':v_rut',$_POST['rut']);
							// 	$buscaridp -> execute();
							// 	$idencontrada = $buscaridp -> fetch(PDO::FETCH_ASSOC);
							// 	$id_en = $idencontrada["id_log"];
								
							// 	$this -> mensaje = "<input type='hidden' name='val1' value='3' id='val1'> Cliente Puede Ingresar 666<br><br> 
							// 	<img src='img/check.png' id='chk'>
							// 	<input type='hidden' name='id_cli' value='".$id_en."' id='id_cli'>
							// 	<input type='hidden' name='id_cliente' value='".$id_en."' id='id_cliente'> ";
							// }	
						
						
						}

					}
					else{
						
						$this->mensaje="El rut ingresado es incorrecto, intente nuevamente. <br><br> <img src='img/cruz.png' id='chk'>";
					
					}
				}
			
			}
		}

	}else{
		$this->mensaje="Ingrese el Rut usando guión! <br><br> <img src='img/cruz.png' id='chk'>";
	}

}


	public function ingresar_entrada(){
	
	// ACÁ DEBO IMPLEMENTAR LA PROMO!!

	$rut = $_POST["rut_cli"];
	$entrada = $_POST["n_entrada"];
	//Obtengo el Último ID del log
	$cbd = Database::getInstance();
	$busqueda_id = $cbd -> prepare("SELECT MAX(id_log) AS id FROM log WHERE rut_ingre = :v_rr");
	$busqueda_id -> bindValue(':v_rr',$rut);
	$busqueda_id->execute();
	$res = $busqueda_id -> fetch(PDO::FETCH_ASSOC);
	$id_ult_b=$res["id"];
	  //echo "ultimo ID ".$id_ult_b."<br>";
	
	
	// Último rut en base al ID anterior
	$last_rut = $cbd -> prepare("SELECT rut_ingre AS lrut FROM log WHERE id_log = :v_mid");
	$last_rut -> bindValue(':v_mid',$id_ult_b);
	$last_rut -> execute();
	$u_rut = $last_rut->fetch(PDO::FETCH_ASSOC);
	$lrut = $u_rut["lrut"];
	 //echo "Ultimo rut ".$lrut;
	// mostrar ultimo rut

	$rev_pep = $cbd -> prepare("SELECT * FROM pep WHERE pep_rut = :v_r");
	$rev_pep -> bindValue(':v_r',$lrut);
	$rev_pep -> execute();
	$res_pep = $rev_pep->fetch(PDO::FETCH_ASSOC);

	if ($res_pep) {
		$modal = '2';
		// echo $modal;
	}else{
		$modal = '3';
		// echo $modal;
	}

	$buscar_entr = $cbd -> prepare("SELECT * FROM log WHERE n_entrada = :v_ne");
	$buscar_entr -> bindValue(':v_ne', $entrada);
	$buscar_entr -> execute();
	$respuesta = $buscar_entr -> fetch (PDO::FETCH_ASSOC);

	// echo "ID del registro con la entrada ".$respuesta["id_log"];
	//echo "ult rut".$u_rut;
	
	if ($respuesta){
		$buscarid = $cbd -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
		$buscarid -> bindValue(':v_rut',$lrut);
		$buscarid -> execute();
		$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
		$id_en = $idencontrada["id_log"];

		$this -> mensaje2 ="<input type='hidden' name='val1' value='$modal' id='val1'> Entrada ya Ingresada
		<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar.php'>
									<input type='hidden' id='rut_cli' name='rut_cli' value='$lrut'>
									";
		$this -> mensaje3 = "<spam>ENTRADA YA INGRESADA!!</spam><input type='hidden' name='id_cli' value='$id_en' id='id_cli'> ";
		
	}else{
		$rut = $_POST["rut_cli"];
		$chb = $_POST["promocheck"];
		if (isset($_POST['promocheck']) && $_POST['promocheck'] =='1'){
			$fhoy = (new DateTime('now'))->format('Y-m-d');
		$ultdiam = date('t');
		$fvenc = date("$ultdiam-m-Y");
		// Primer dia del mes siguiente
		$frenuev = (new DateTime("$fvenc + 1 day"))->format('Y-m-d');
		
		$fechapcal = strtotime($fhoy);
		$fechavpcal = strtotime($frenuev);

		$findrutpromo = $cbd -> prepare("SELECT * FROM promo WHERE rut = :v_r ORDER BY cod Desc LIMIT 1");
		$findrutpromo -> bindValue(':v_r',$lrut);
		$findrutpromo->execute();
		$buscrutpromo = $findrutpromo -> fetch(PDO::FETCH_ASSOC);
		//echo "</br>";

		$buscarid = $cbd -> prepare("SELECT * FROM `log` WHERE rut_ingre = :v_rut ORDER BY id_log Desc LIMIT 1");
		$buscarid -> bindValue(':v_rut',$lrut);
		$buscarid -> execute();
		$idencontrada = $buscarid -> fetch(PDO::FETCH_ASSOC);
		$id_en = $idencontrada["id_log"];
		if ($buscrutpromo!="") {
			// echo "Encontrado";
			// echo "</br>";
			$fecharen =  strtotime($buscrutpromo['f_renuevo']);
			if($fecharen <= $fechapcal){
				//echo "Puede usar nuevamente el beneficio";
				$ingrpromo = $cbd -> prepare("INSERT INTO promo(rut,f_entrada,f_renuevo)VALUES(:v_r,:v_fe,:v_fr)");
				$ingrpromo -> bindValue(':v_r',$lrut);
				$ingrpromo -> bindValue(':v_fe',$fhoy);
				$ingrpromo -> bindValue(':v_fr',$frenuev);
				$ingrpromo -> execute();
			}else{
				$disp = (new DateTime($buscrutpromo['f_renuevo']))->format('d-m-Y');
				// echo "Ya usó el beneficio este mes";
				// echo "</br>";
				// echo "Fecha de renov promo: ".$disp;
				$this -> mensaje2 ="<input type='hidden' name='val1' value='$modal' id='val1'> Beneficio ya usado
				<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar.php'>
											<input type='hidden' id='rut_cli' name='rut_cli' value='$lrut'>
											";
						$this -> mensajep = "<spam class='aviso'>EL CLIENTE YA USÓ EL 2 X 1!!</spam></br><spam>DISPONIBLE NUEVAMENTE EL: $disp</spam><input type='hidden' name='id_cli' value='$id_en' id='id_cli'> ";
				//echo "aqui";
			}
			
		}else{
			//echo "NO Encontrado";
			$ingrpromo = $cbd -> prepare("INSERT INTO promo(rut,f_entrada,f_renuevo)VALUES(:v_r,:v_fe,:v_fr)");
			$ingrpromo -> bindValue(':v_r',$lrut);
			$ingrpromo -> bindValue(':v_fe',$fhoy);
			$ingrpromo -> bindValue(':v_fr',$frenuev);
			$ingrpromo -> execute();
		}

			$buscarPromo = $cbd -> prepare("INSERT INTO promo(rut,f_entrada,f_renuevo)VALUES(:v_r,:v_fe,:v_fr)");
			$buscarPromo -> bindValue(':v_r',$lrut);
			$buscarPromo -> bindValue(':v_fe',$fhoy);
			// $buscarPromo -> bindValue(':v_fr',$frenuev);
		//exit();

		}
		else {
			$this -> mensaje3 = "<strong>Entrada válida!</strong>";
		//exit();
		$actualizar_d = $cbd -> prepare("UPDATE log SET n_entrada = :v_en, estado_ticket = :v_et  WHERE id_log=:v_id ");
		$actualizar_d -> bindValue(':v_en', $entrada);
		$actualizar_d -> bindValue(':v_id', $id_ult_b);
		$actualizar_d -> bindValue(':v_et', 2);
		$actualizar_d -> execute();
		header("location: index.php");
		}
		
		
		}

	}

	public function editar_ticket(){
	
	$id = $_POST["id_cli"];
	$id_ed = $_POST["id_"];
	$entrada = $_POST["n_entrada"];

	//Último ID log
	$cbd = Database::getInstance();
	$busqueda_id = $cbd -> prepare("SELECT MAX(id_log) AS id FROM log;");
	$busqueda_id->execute();
	$res = $busqueda_id -> fetch(PDO::FETCH_ASSOC);
	$id_ult_b=$res["id"];
	// echo "ultimo ID ".$id_ult_b."<br>";
	//$this -> mensaje2 = "<spam style='color:red;'>Esta entrada ya fué Ingresada!</spam><input type='hidden' id='rut_cli' value=".$respuesta["id_log"].">";

	// Último rut en base al ID anterior
	$last_rut = $cbd -> prepare("SELECT rut_ingre AS lrut FROM log WHERE id_log = :v_mid");
	$last_rut -> bindValue(':v_mid',$id_ult_b);
	$last_rut -> execute();
	$u_rut = $last_rut -> fetch(PDO::FETCH_ASSOC);
	$lrut = $u_rut["lrut"];
	// echo "Ultimo rut ".$lrut;
	// mostrar ultimo rut

	$rev_pep = $cbd -> prepare("SELECT * FROM pep WHERE pep_rut = :v_r");
	$rev_pep -> bindValue(':v_r',$lrut);
	$rev_pep -> execute();
	$res_pep = $rev_pep->fetch(PDO::FETCH_ASSOC);

	$buscar_entr = $cbd -> prepare("SELECT * FROM log WHERE n_entrada = :v_ne");
	$buscar_entr -> bindValue(':v_ne', $entrada);
	$buscar_entr -> execute();
	$respuesta = $buscar_entr -> fetch (PDO::FETCH_ASSOC);
	
	// echo "ID del registro con la entrada ".$respuesta["id_log"];
	//echo "ult rut".$u_rut;

	if ($respuesta){
		$this -> mensaje2 ="<input type='hidden' name='val1' value='1' id='val1'> Entrada ya Ingresada
		<form method='post' id='Frm2' onsubmit='return tomarmsj();' class='form_ocultos' enctype='multipart/form-data' action='enviar.php'>
									<input type='hidden' id='rut_cli' name='rut_cli' value='$lrut'>
									";
		$this -> mensaje3 = "<spam>ENTRADA YA INGRESADA!!</spam><input type='hidden' name='id_cli' value='$id' id='id_cli'>";

	}else{
		$this -> mensaje3 = "<strong>Entrada válida</strong>";
		$actualizar_d = $cbd -> prepare("UPDATE log SET n_entrada = :v_en, estado_ticket = :v_e  WHERE id_log=:v_id ");
		$actualizar_d -> bindValue(':v_en', $entrada);
		$actualizar_d -> bindValue(':v_e', 3);
		$actualizar_d -> bindValue(':v_id', $id_ed);
		$actualizar_d -> execute();
		header('Location:tickets.php');
		}

	
	}
	public function pausar_entrada(){
	$id1 = $_POST["id_log2"];
	$id2 = $_POST["id_log"];

	if($id1 != null){
		$id = $id1;
	}else{
		$id = $id2;
	}
	$cbd = Database::getInstance();
	$pausar = $cbd -> prepare("UPDATE log SET estado_ticket = :v_e WHERE id_log = :v_id");
	$pausar -> bindValue(':v_e', 1);
	$pausar -> bindValue(':v_id',$id);
	$pausar -> execute();
	header("location: index.php");

	
		$this -> mensaje2 = "<strong>Entrada por ingresar</strong>";
	

}
	public function ingresar_entrada_pasp(){
	$pasp = $_POST["pasp_cli"];
	$hora = $_POST["hora"];
	$fecha = $_POST["fecha"];
	$entrada = $_POST["n_entrada"];
	$fecha_hora = $_POST["fecha_hora"];
	echo $pasp;
	echo $hora;
	echo $fecha;
	echo $entrada;
	echo $fecha_hora;
	//exit();
	try {
		$cbd = Database::getInstance();
		$ingre_pasp = $cbd -> prepare("INSERT INTO log(rut_ingre,hora,fecha,autoexc,cli_pep,n_entrada,estado_ticket,fecha_hora)VALUES(:v_r,:v_h,:v_f,:v_ae,:v_pp,:v_t,:v_et,:v_fh)");
		$ingre_pasp -> bindValue(':v_r',$_POST["pasp_cli"]);
		$ingre_pasp -> bindValue(':v_h',$_POST["hora"]);
		$ingre_pasp -> bindValue(':v_f',$_POST["fecha"]);
		$ingre_pasp -> bindValue(':v_fh', $_POST['fecha_hora']);
		$ingre_pasp -> bindValue(':v_ae',"no");
		$ingre_pasp -> bindValue(':v_pp',"no");
		$ingre_pasp -> bindValue(':v_t',$_POST["n_entrada"]);
		$ingre_pasp -> bindValue(':v_et',2);
		$ingre_pasp -> execute();
		header("location: index.php");
	}catch (PDOException $e) {
		echo "ERROR: " . $e->getMessage();
   		}
	//$this -> mensaje2 = "<strong>".$pasp."</strong>";
	}
	public function entrada_pasp_espera(){
		try {
			$cbd = Database::getInstance();
			$pausar_ent_pasp = $cbd -> prepare("INSERT INTO log(rut_ingre,hora,fecha,autoexc,cli_pep,n_entrada,estado_ticket)VALUES(:v_r,:v_h,:v_f,:v_ae,:v_pp,:v_t,:v_et)");
			$pausar_ent_pasp -> bindValue(':v_r',$_POST["pasp_cli_h"]);
			$pausar_ent_pasp -> bindValue(':v_h',$_POST["hora"]);
			$pausar_ent_pasp -> bindValue(':v_f',$_POST["fecha"]);
			$pausar_ent_pasp -> bindValue(':v_ae',"no");
			$pausar_ent_pasp -> bindValue(':v_pp',"no");
			$pausar_ent_pasp -> bindValue(':v_t', "");
			$pausar_ent_pasp -> bindValue(':v_et',1);
			$pausar_ent_pasp -> execute();
			header("location: index.php");
		} catch (PDOException $e) {
			echo "ERROR: " . $e->getMessage();
		}
	}
	public function modificar_ticket(){
		try {
			$cbd = Database::getInstance();
			$mod_entr = $cbd -> prepare("UPDATE log SET n_entrada = :v_e WHERE id_log = :v_i");
			$mod_entr -> bindValue(':v_i',$_POST["id_"]);
			$mod_entr -> bindValue(':v_e',$_POST["n_entrada"]);
			$mod_entr -> execute();
			header("location: show_all.php");
		} catch (PDOException $e) {
			echo "ERROR: " . $e->getMessage();
		}
	}
	public function sub_pep(){
	try {
		$tipo		= $_FILES["csv_pep"]["type"];
		$tamano		= $_FILES["csv_pep"]["size"];
		$archtmp	= $_FILES["csv_pep"]["tmp_name"];
		$lineas		= file($archtmp);

		echo $tipo;echo "</br>";
		echo $tamano;echo "</br>";
		echo $lineas;echo "</br>";

		if($tamano != 0){
			echo "Tiene peso";

		}else{
			echo "No pesa nada";
		}
	exit();	
		$i=1;

		foreach($lineas as $linea){
			$cant_reg = count($lineas);
			$cant_reg_agr = ($cant_reg - 1);
			//echo $i;
			if($i != 0){
				$datos = explode(";",$linea);
				$id_pep = $datos[0];
				$id_pep = intval($id_pep);
				$rut_pep = $datos[1];
				$nom_pep = utf8_encode($datos[2]);
				// echo $id_pep." - ";
				// echo $rut_pep;
				// echo $nom_pep;
				// echo "</br>";

				$cbd = Database::getInstance();
				$agrego = $cbd -> prepare("INSERT INTO pep(pep_id,pep_rut,pep_nombre) VALUES (:v_id,:v_rt,:v_nom)");
				$agrego -> bindValue(':v_id',$id_pep);
				$agrego -> bindValue(':v_rt',$rut_pep);
				$agrego -> bindValue(':v_nom',$nom_pep);
				$agrego->execute();
			}
			$i++;
		}
		if(isset($agrego)){
			echo "Se han importado correctamente $cant_reg registros.";
		}
		else{
			echo "Nada para importar";
		}
		// echo "Lineas: ".$cant_reg;
		// echo "Datos: ".$datos;

		} catch (PDOException $e) {
		echo "ERROR: " . $e->getMessage();
   	}

}
	public function buscar_rut()
	{
		$cod = $_POST["cod"];

		$rut = $_POST["rut"];
		
		// echo $rut;
		$this -> mensaje2 = "<strong>$rut</strong>";
	}

	public function rev_pase()
	{
		$cod = $_POST["cod_pas"];
		
		echo $cod;
		echo "</br>";
		$cod_limpio = utf8_encode($cod);
		echo $cod_limpio;
		$this -> mensaje3 = "<strong>$cod_limpio</strong>";
	}
	public function agregarProhibido(){
		$dbc = Database::getInstance();
		$insertar = $dbc -> prepare("INSERT INTO prohibidos (rut,nombre,fecha_inicio,fecha_fin) 
		VALUES (:_idn,:_nb,:_fini,:_fin)");
		$insertar -> bindValue(':_idn',$_POST['rut']);
		$insertar -> bindValue(':_nb',$_POST['nombre']);
		$insertar -> bindValue(':_fini',$_POST['datepickerDesde']);
		$insertar -> bindValue(':_fin',$_POST['datepickerHasta']);
		$insertar -> execute();
		if($insertar){
			header("location: prohib.php");
		}
		else{
			header("location: prohib.php");
		}
	}
	public function agregarPep(){
		$dbc = Database::getInstance();
		$insertar = $dbc -> prepare("INSERT INTO pep (pep_rut,pep_nombre) 
		VALUES (:_idn,:_nb)");
		$insertar -> bindValue(':_idn',$_POST['rut']);
		$insertar -> bindValue(':_nb',$_POST['nombre']);
		$insertar -> execute();
		if($insertar){
			header("location: pep.php");
		}
		else{
			header("location: pep.php");
		}
	}
	public function eliminarProhibidos($id){
		$dbc = Database::getInstance();
		$eliminar = $dbc -> prepare("DELETE FROM prohibidos WHERE prohibidos.id = :_i");
		$eliminar -> bindValue(':_i',$id);
		$eliminar -> execute();
		if($eliminar){
			header("location: prohib.php");
		}
		else{
			header("location: prohib.php");
		}
	}
	public function eliminarPep($id){
		$dbc = Database::getInstance();
		$eliminar = $dbc -> prepare("DELETE FROM pep WHERE pep.pep_id = :_i");
		$eliminar -> bindValue(':_i',$id);
		$eliminar -> execute();
		if($eliminar){
			header("location: pep.php");
		}
		else{
			header("location: pep.php");
		}
	}
	public function contarEntradas(){
		$hoy = date ("d-m-Y",time());
		$dbh = Database::getInstance();
		$cuenta_personas = $dbh -> prepare("SELECT COUNT(id_log) as peoples
		FROM log
		WHERE (fecha = :v_h) AND (estado_ticket = 1 OR estado_ticket = 2 OR estado_ticket = 3)");
		$cuenta_personas->bindValue(':v_h',$hoy);
		$cuenta_personas->execute();
		$cli_ingre = $cuenta_personas->fetch(PDO::FETCH_ASSOC);
		$cli_ingre = $cli_ingre["peoples"];
		return $cli_ingre;
	}
	public function revisarDobleIngreso(){
		//Revisión del rut en los últimos 10 rut ingresados
		//buscar ultimos 10 rut
		$dbh = Database::getInstance();
		$listar10 = $dbh -> prepare("SELECT rut_ingre, fecha from log ORDER BY id_log DESC LIMIT 10");
		$listar10 -> execute();
		$ultimos = $listar10->fetchAll(PDO::FETCH_ASSOC);
		return $ultimos;
	}
	public function obtenerJornada($inicioJornada,$finJornada,$fechaActual){
		
		echo "Fecha y hora actual $fechaActual <br>";
		$fechaYhoraIj = date("d-m-Y",strtotime($fechaActual))." ".$inicioJornada;
		echo "Fecha y Hora Inicio jornada $fechaYhoraIj <br>";
		$fechaYhoraFj = date("d-m-Y",strtotime("+1 day",strtotime($fechaActual)))." ".$finJornada;
		echo "Fecha y hora Fin jornada $fechaYhoraFj <br>"; 
		if($fechaActual >= $fechaYhoraIj && $fechaActual <= $fechaYhoraFj){
			return "Dentro de jornada ".$fechaActual = date("d-m-Y",strtotime($fechaActual));
		}else{
			return "Fuera de Jornada";
		}
		echo "ss";
		// if($fechaActual >= $fechaYhoraIj && $fechaActual > $fechaYhoraFj){
		// 	echo "+1 dia". date("d-m-Y",strtotime("+1 day",strtotime($fechaActual)));
		// }
	}
	public function contarEntradasHoy($fecha,$horaInicioJ,$horaFinJ){
		$fechaIniReves = date("Y-m-d",strtotime($fecha))." ".$horaInicioJ;
		//$fechaYhoraFj = date("d-m-Y",strtotime("+1 day",strtotime($fecha)))." ".$horaFinJ;
		$fechaFinReves = date("Y-m-d",strtotime("+1 day",strtotime($fecha)))." ".$horaFinJ;
		 //echo "Inicio: ".$fechaYhoraIj;
		//  echo "<br>"; 
		//  echo "Fecha Revés ".$fechaIniReves."<br>";
		 //echo "FIN: ".$fechaYhoraFj."<br>";
		//  echo "Fecha Revés ".$fechaFinReves."<br>";
		//  echo "<br>";  exit();
		$dbh = Database::getInstance();
		$buscar = $dbh -> prepare("SELECT COUNT(id_log) as entradas FROM log WHERE fecha_hora_ BETWEEN :v_ij and :v_fj");
		$buscar->bindValue(':v_ij',$fechaIniReves);
		$buscar->bindValue(':v_fj',$fechaFinReves);
		$buscar ->execute();
		$cantidad = $buscar->fetch(PDO::FETCH_ASSOC);
		$resultado = $cantidad["entradas"];
		return $resultado;
	}

	public function buscarEntradasxJornada($fecha,$horaInicioJ,$horaFinJ){
		 //echo "Fecha ingresada: ".$fecha."<br>";
		//$fechaYhoraIj = $fecha." ".$horaInicioJ;
		$fechaIniReves = date("Y-m-d",strtotime($fecha))." ".$horaInicioJ;
		//$fechaYhoraFj = date("d-m-Y",strtotime("+1 day",strtotime($fecha)))." ".$horaFinJ;
		$fechaFinReves = date("Y-m-d",strtotime("+1 day",strtotime($fecha)))." ".$horaFinJ;
		 //echo "Inicio: ".$fechaYhoraIj;
		//  echo "<br>"; 
		//  echo "Fecha Revés ".$fechaIniReves."<br>";
		 //echo "FIN: ".$fechaYhoraFj."<br>";
		//  echo "Fecha Revés ".$fechaFinReves."<br>";
		//  echo "<br>";  exit();
		$dbh = Database::getInstance();
		$buscar = $dbh -> prepare("SELECT COUNT(id_log) as entradas FROM log WHERE fecha_hora_ BETWEEN :v_ij and :v_fj");
		$buscar->bindValue(':v_ij',$fechaIniReves);
		$buscar->bindValue(':v_fj',$fechaFinReves);
		$buscar ->execute();
		$cantidad = $buscar->fetch(PDO::FETCH_ASSOC);
		$resultado = $cantidad["entradas"];
		return $resultado;
	}
	public function consultarAe($rut){
		$dbh = Database::getInstance();
		$consulta1 = $dbh -> prepare("SELECT autoexcluidos_scj.ae_nombre as nombre
			FROM autoexcluidos_scj
			WHERE autoexcluidos_scj.ae_rut = :v_n");
		$consulta1 -> bindValue(':v_n', $_POST['rut']);
		$consulta1 -> execute();
		$resultado = $consulta1 -> fetch(PDO::FETCH_ASSOC);
		$nombre = $resultado['nombre'];
		$email = $resultado['email'];
		$tel = $resultado['tel'];
		$tel2 = $resultado['telmo'];
	}
	function consultarPEP_local($rut){
		$dbh = Database::getInstance();
		$consulta_pep = $dbh -> prepare ("SELECT pep_nombre as nombre, pep_rut as rut FROM pep WHERE pep_rut = :v_n");
		$consulta_pep -> bindValue(':v_n', $_POST['rut']);
		$consulta_pep -> execute();
		$resultado = $consulta_pep -> fetch(PDO::FETCH_ASSOC);
		//echo $resultado;
		return $resultado;
	}
	function consultarPEP($dni) {
		$baseUrl = "https://external-api.regcheq.com/record/{dni}/{{API_KEY_REGCHEQ}}";
		$apiKey = "A4CF182C007DB3F9009B9666"; 
		$urlDni = str_replace('{dni}',$dni, $baseUrl);
		$url = str_replace('{{API_KEY_REGCHEQ}}', $apiKey, $urlDni);
		// echo $url;exit();
		// $conexionOK = $this->comprobarConexion($url);
	
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json"
		));
	
		$response = curl_exec($ch);
		
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		} else {
			$responseData = json_decode($response, true);
	
			return $responseData;
		}
	
		curl_close($ch);
	}
}
