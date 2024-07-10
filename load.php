<?php 
require_once("database.php");

date_default_timezone_set("America/Santiago");
$hora = date ("d-m-Y H:i",time());


$url = "https://autoexclusion.scj.gob.cl/api/v1/exclusions";
$ch = curl_init();
$accesstoken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjgyLCJpYXQiOjE1NjU1MjQzMzR9.yMFcpG4qjQsc65YHk21C5qt5h5vwAQ9SuyPuLlJ6FWE";
$headr = array();
$headr[] = 'Content-length: 0';
$headr[] = 'Content-type: application/json';
$headr[] = 'Authorization: Bearer '.$accesstoken;

curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result=curl_exec($ch);

curl_close($ch);

$res = json_decode($result,true);

//echo $result;
//$otro = count($res);
//echo "Contador 1: ".$otro."<br>";

//var_dump(json_decode($result, true));

$mensaje0 = "";
$mensaje1 = "";
$mensaje2 = "";
$mensaje = "";

$conta = count($res);
//echo $conta;
$array = "";

if ($conta > 10) {
    $mensaje0 = "Conectado a API";
    //FUNCION PARA GUARDAR JSON DE MANERA LOCAL
    try {
        $nombre_archivo = "json/datos.json";

        if (file_exists($nombre_archivo)) {
            $mensaje="El archivo $nombre_archivo se ah modificado ";
        }
        else{
            $mensaje = "El archivo $nombre_archivo se ah creado";
        }
        if ($archivo = fopen($nombre_archivo, "w+")) {
            if (fwrite($archivo, $result)) {
                
                $mensaje2 = "Se ah escrito correctamente <br>";


                // Funcion para Guardar Datos
                    $json = file_get_contents($nombre_archivo, "r");

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

            $dbh = Database::getInstance();

            $consulta2 = $dbh -> prepare("TRUNCATE TABLE autoexcluidos_scj");
            $consulta2->execute();

            
                $consulta1 = $dbh -> prepare("INSERT INTO autoexcluidos_scj(ae_rut,ae_nombre,ae_prim_nombre,ae_apellido_p,ae_apellido_m,ae_email,ae_tel,ae_tele_movil,ae_has_assignee,ae_has_ignee_acepted,ap_nombre,ap_prim_nombre,ap_apellido_p,ap_apellido_m,ap_email,ap_tel,ap_tele_movil)VALUES(:v_r,:v_n,:v_f_n,:v_l_n,:v_sl_n,:v_em,:v_te,:v_tm,:v_h_a,:v_h_aia,:v_ap_n,:v_ap_f_n,:v_ap_l_n,:v_ap_sl_n,:v_ap_em,:v_ap_te,:v_ap_tm)");
    

    
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
                if(isset($nombre['assignee']['name'])){
                    $ap_nombre = $nombre['assignee']['name'];
                }else{
                    $ap_nombre = "";
                }
                if(isset($nombre['assignee']['first_name'])){
                    $ap_prim_nombre = $nombre['assignee']['first_name'];
                }else{
                    $ap_prim_nombre = "";
                }
                if(isset($nombre['assignee']['last_name'])){
                    $ap_apellidop = $nombre['assignee']['last_name'];
                }else{
                    $ap_apellidop = "";
                }
                if(isset($nombre['assignee']['second_last_name'])){
                    $ap_apellidom = $nombre['assignee']['second_last_name'];
                }else{
                    $ap_apellidom = "";
                }
                if(isset($nombre['assignee']['email'])){
                    $ap_email = $nombre['assignee']['email'];
                }else{
                    $ap_email = "";
                }
                if(isset($nombre['assignee']['phone'])){
                    $ap_tel = $nombre['assignee']['phone'];
                }else{
                    $ap_tel = "";
                }
                if(isset($nombre['assignee']['mobile_phone'])){
                    $ap_t_mov = $nombre['assignee']['mobile_phone'];
                }else{
                    $ap_t_mov = "";
                }

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

            $cuenta_array=count($array);

            if ($cuenta_array>0) {
                $mensaje1 = "Se han agregado: ".$cuenta_array." clientes";

                $file = fopen("json/log.txt", "a");

                fwrite($file, "Actualizado el ".$hora . PHP_EOL);
                
                fclose($file);
                echo "Bien";
                //Actualizada!</strong>";
                $paginicial = "index.php";
                header("location: $paginicial");
                die();

            }
           

        }
        else{
            $mensaje2 =  "Problemas en la escritura";
                $file = fopen("json/log.txt", "a");

                fwrite($file, "Problemas con la carga ".$hora . PHP_EOL);
                
                fclose($file);
        }
        fclose($archivo);
            }else{
                echo "no entró";
                 $file = fopen("json/log.txt", "a");

                fwrite($file, "Problemas con la carga ".$hora . PHP_EOL);
                
                fclose($file);
            }
        
            

    } catch (Exception $e) {
                echo $e->getMessage();
            }


}else{
        $mensaje0 = "Error de conexión con API";
    }

 ?>