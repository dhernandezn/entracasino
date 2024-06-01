<?php 
require_once("database.php");

$json_guardado = "json/datos.json";

$json = file_get_contents($json_guardado, "r");



$ae_rut=0;
$ae_nombre=0;
$ae_prim_nombre=0;
$ae_apellidop=0;
$ae_apellidom=0;
$ae_email=0;
$ae_tel=0;
$ae_t_mov=0;
$ae_h_a=0;
$ae_h_aia=0;
$ap_nombre=0;
$ap_prim_nombre=0;
$ap_apellidop=0;
$ap_apellidom=0;
$ap_email=0;
$ap_tel=0;
$ap_t_mov=0;


/*$unJson = '[{"run":"09287071-5","name":"Claudio Jacobo Tebache Retamal","first_name":"Claudio Jacobo ","last_name":"Tebache ","second_last_name":"Retamal","email":"ctebache@gmail.com","phone":"994524307","mobile_phone":"","has_assignee":true,"has_assignee_accepted":true,"assignee":{"name":"Marion Fredna Baier Nualart","first_name":"Marion Fredna ","last_name":"Baier ","second_last_name":"Nualart","email":"marionfredna@gmail.com","phone":"92188909","mobile_phone":""}}]';*/



	$dbh = Database::getInstance();

	$consulta2 = $dbh -> prepare("TRUNCATE TABLE autoexcluidos_scj");
	$consulta2->execute();

	
		$consulta1 = $dbh -> prepare("INSERT INTO autoexcluidos_scj(ae_rut,ae_nombre,ae_prim_nombre,ae_apellido_p,ae_apellido_m,ae_email,ae_tel,ae_tele_movil,ae_has_assignee,ae_has_ignee_acepted,ap_nombre,ap_prim_nombre,ap_apellido_p,ap_apellido_m,ap_email,ap_tel,ap_tele_movil)VALUES(:v_r,:v_n,:v_f_n,:v_l_n,:v_sl_n,:v_em,:v_te,:v_tm,:v_h_a,:v_h_aia,:v_ap_n,:v_ap_f_n,:v_ap_l_n,:v_ap_sl_n,:v_ap_em,:v_ap_te,:v_ap_tm)");
    

    //$consulta1 -> execute();
	$array = json_decode($json, true);



 	foreach ($array as $key => $nombre) {
            	
                $ae_rut = $nombre['run'];
                $ae_nombre = $nombre['name'];
            	$ae_prim_nombre = $nombre['first_name'];
            	$ae_apellidop = $nombre['last_name'];
            	$ae_apellidom = $nombre['second_last_name'];
            	$ae_email = $nombre['email'];
            	$ae_tel = $nombre['phone'];
            	$ae_t_mov = $nombre['mobile_phone'];
            	$ae_h_a = $nombre['has_assignee'];
            	$ae_h_aia = $nombre['has_assignee_accepted'];
            	$ap_nombre = $nombre['assignee']['name'];
                $ap_prim_nombre = $nombre['assignee']['first_name'];
                $ap_apellidop = $nombre['assignee']['last_name'];
                $ap_apellidom = $nombre['assignee']['second_last_name'];
                $ap_email = $nombre['assignee']['email'];
                $ap_tel = $nombre['assignee']['phone'];
                $ap_t_mov = $nombre['assignee']['mobile_phone'];

                $consulta1 -> bindValue(':v_r', $ae_rut);
			    $consulta1 -> bindValue(':v_n', $ae_nombre);
			    $consulta1 -> bindValue(':v_f_n', $ae_prim_nombre);
			    $consulta1 -> bindValue(':v_l_n', $ae_apellidop);
			    $consulta1 -> bindValue(':v_sl_n', $ae_apellidom);
			    $consulta1 -> bindValue(':v_em', $ae_email);
			    $consulta1 -> bindValue(':v_te', $ae_tel);
			    $consulta1 -> bindValue(':v_tm', $ae_t_mov);
			    $consulta1 -> bindValue(':v_h_a', $ae_h_a);
			    $consulta1 -> bindValue(':v_h_aia', $ae_h_aia);
			    $consulta1 -> bindValue(':v_ap_n', $ap_nombre);
			    $consulta1 -> bindValue(':v_ap_f_n', $ap_prim_nombre);
			    $consulta1 -> bindValue(':v_ap_l_n', $ap_apellidop);
			    $consulta1 -> bindValue(':v_ap_sl_n', $ap_apellidom);
			    $consulta1 -> bindValue(':v_ap_em', $ap_email);
			    $consulta1 -> bindValue(':v_ap_te', $ap_tel);
			    $consulta1 -> bindValue(':v_ap_tm', $ap_t_mov);


                $consulta1 -> execute();
               
	       }
	

    





 ?>