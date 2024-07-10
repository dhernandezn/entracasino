<?php 
require_once("database.php");
date_default_timezone_set("America/Santiago");
$hoy = date ("d-m-Y",time());
/** Busco Nuevos AE que no estén en mi BD local */
function buscarNuevosAE($arrayLocal,$arrayScj){
    $n_ae = [];
	foreach($arrayScj as $scj){
        echo array_search(intval($scj['id']),$arrayLocal,TRUE);
        exit();
        //echo gettype($scj['id']); //INT
        if (array_search(intval($scj['id']),$arrayLocal,TRUE) == "" ){
			echo "-ID ".$scj['id']." RUT: ".$scj['run']." Nombre: ".$scj['name']."</br>";
            array_push($n_ae,[$scj['id'],$scj['run'],$scj['name']]);
        }
	}
    if(count($n_ae) == 0){
        echo "<h4>No hay nuevos registros</h4>";
    } 
}
/** Busco Registros que ya no vengan en la nueva version de la BD de SCJ */
function buscarRevocados($arrayLocal,$arrayScj){
    echo "dahn";
    $n_rv = [];
	foreach($arrayLocal as $local){
        //echo gettype($local['ae_scj']); // STRING -> LO CONVIERTO EN INT
		if (array_search(intval($local['scjid']),$arrayScj,TRUE) == "" ) {
			echo "-ID:".$local['scjid']." RUT:".$local['rut']." ".$local['nombre']."</br>" ;
            array_push($n_rv,[$local['id'],$local['run'],$local['name']]);
		}
	}
    if(count($n_rv)==0){
        echo "<h4>No hay nuevos registros</h4>";
    }
}


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

$datos_bdscj = json_decode($result,true);
//print_r($datos_bdscj);
// Puedo crear una funcion para contar elementos de un array
/** SI no existen datos desde la API de conexión dar aviso de mala conexión */


//Recolecto los ID_SCJ de la BD local
$dbh = Database::getInstance();
$consulta_dblocal = $dbh -> prepare("SELECT ae_scj as scjid,ae_rut as rut,ae_nombre as nombre from saecc2.autoexcluidos_scj order by scjid asc ;");
$consulta_dblocal -> execute();
$datos_bdlocal = $consulta_dblocal->fetchAll(PDO::FETCH_ASSOC);

//#########################################
$datos_local_int = [];
foreach($datos_bdlocal as $bdlocal)
{
    $dato1 = intval($bdlocal['scjid']);
    array_push($datos_local_int,$dato1,$bdlocal['rut'],$bdlocal['nombre']);
}
//#########################################

function crearArrayIdscj($arrayScj)
{   
    $arrayIDscj = [];
    echo $arrayIDscj['id'];
    foreach($arrayScj as $bdscj)
    {
        $dato = $bdscj['id'];

        array_push($arrayIDscj,$dato);
        echo $dato.'</br>';
    }
}

$id_Scj_local1 = array(1,2,3,4,5,6,7,100);
$id_Scj_api1 = array(7,6,5,4,3,2,1);

//echo "<h3><u>Nuevos AutoExcluidos:</u> </h3>";

//buscarNuevosAE($datos_local_int,$datos_bdscj);
//echo "<hr>";
// echo "<h3><u>Revocadoss:</u> </h3>";
// phpinfo();
//print_r($datos_bdscj);
// echo $datos_bdscj['id'];
// echo array_column($datos_bdscj,'id');
//buscarRevocados($datos_bdlocal,array_column($datos_bdscj,'id'));




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/style.css">

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.4.5.0.js"></script>
	<script src="js/js.js"></script>
</head>
<body>
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Reporte <b><?php echo $hoy;?></b></h2>
                    </div>
                </div>
            </div>
            <div class="autoe">
                <h3><u>Nuevos AutoExcluidos:</u> </h3><br>
                <?php   
                    buscarNuevosAE($datos_local_int,$datos_bdscj);
                ?>
                <br>
            </div>
            <div class="revocados">
                <h3><u>Revocados:</u> </h3><br>
                <?php
                echo "ok";
                    buscarRevocados($datos_bdlocal,array_column($datos_bdscj,'id'));
                ?>
            </div><hr>
        </div>
    </div>
</div>
</body>
</html>