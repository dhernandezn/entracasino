<?php 
require_once("database.php");


if ($_FILES['arch_carga']['size'] > 0) {
		$csv = $_FILES['arch_carga']['tmp_name'];
		$handle = fopen($csv,'r');
		while ($data = fgetcsv($handle,1000,";",",")) {
			if ($data[0]) {
				$dbh = Database::getInstance();
			    $consulta = $dbh -> prepare("INSERT INTO autoexcluidos_scj(ae_rut,ae_nombre,ae_apellido_m,ae_apellido_p,ae_email,ae_tel,ae_tel_mov,ap_apellido_m,ap_apellido_p,ap_email,ap_tel,ap_tel_mov)VALUES('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."')");
			    //$consulta -> bindValue(':v_n', $csv);
			    $consulta -> execute();
			}
			
		}
		echo "OK";
	}




 ?>