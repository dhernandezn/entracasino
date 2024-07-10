<?php 
require_once("database.php");

echo "!";
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
print_r("Hay: ".$result);
print_r($datos_bdscj);
echo "!";
if (isset($result)) {
    echo "API funcionando";
}else{
    echo "API no funcionando";
}
/* ------------------------------------------------------------------------------------------------------- */

// echo $result;
//$otro = count($datos_bdscj);
//echo "Contador 1: ".$otro."<br>";

//var_dump(json_decode($result, true));

$conta = count($datos_bdscj);
echo "conta: ".$conta;
echo "Cantidad: ".$conta;

if ($conta > 10) {

    //FUNCION PARA GUARDAR JSON DE MANERA LOCAL
    try {
        $nombre_archivo = "json/datos.json";
        

        if (file_exists($nombre_archivo)) {
            $mensaje="El archivo $nombre_archivo se a modificado";
        }
        else{
            $mensaje = "El archivo $nombre_archivo se a creado";
        }
        
        if ($archivo = fopen($nombre_archivo, "w+")) {
            
            if (fwrite($archivo, $result)) {
                echo "Se a escrito correctamente <br>";

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

    
                $consulta1 = $dbh -> prepare("INSERT INTO autoexcluidos_scj(ae_rut,ae_nombre,ae_prim_nombre,ae_apellido_p,ae_apellido_m,ae_email,ae_tel,ae_tele_movil,ae_has_assignee,ae_has_ignee_acepted,ap_nombre,ap_prim_nombre,ap_apellido_p,ap_apellido_m,ap_email,ap_tel,ap_tele_movil,ae_scj)VALUES(:v_r,:v_n,:v_f_n,:v_l_n,:v_sl_n,:v_em,:v_te,:v_tm,:v_h_a,:v_h_aia,:v_ap_n,:v_ap_f_n,:v_ap_l_n,:v_ap_sl_n,:v_ap_em,:v_ap_te,:v_ap_tm,:v_scj)");


    
                $array_scj = json_decode($json, true);



                foreach ($array_scj as $key => $nombre) {
                    
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
                if(isset($nombre['id'])){
                    $ae_scj = $nombre['id'];
                }else{
                    $ae_scj = "";
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
                    $consulta1 -> bindValue(':v_scj', $ae_scj);


                    $consulta1 -> execute();
                
                }

                $cuenta_array=count($array_scj);

                if ($cuenta_array>0) {
                    echo "Se han agregado: ".$cuenta_array." clientes";
                }
            }
            else{
                echo "Problemas en la escritura";
            }
            fclose($archivo);
        }else{
        echo "no entró";
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }


}else{
    echo "Error de conexión con API";
}







?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>## SAECC - Bajar datos ##</title>
	<link rel="icon" href="css/images/logofavico.png" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/pace.css">
	
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
</head>
<body id="tabla_consulta">
	
	<div class="contenedor2">
			<div><h2 class="font_informe">Lista de Autoexcluidos</h2></div>
            <p><?php echo $mensaje; ?></p>
		<div class="form-group">
                    <div class="col-md-2 col-md-offset-8">

                        <input type="hidden" name="salir">
                        <input class="btn btn-primary" type="submit" id="btn-exit" name=Accion OnClick="window.location.href='index.php'" value="Salir">
                    </div>
        </div>
		<div class="tabla table-responsives">
			<table class="table table-striped table-bordered table-conden" id="lista" cellspacing="0" width="100%">
				<thead>
            <tr>
                <th>Id SCJ</th>
            	<th>Rut</th>
                <th>Nombre</th>
                <th>Apellido Materno</th>
                <th>Apellido Paterno</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Telefono Movil</th>
                <th>Nombre</th>
                <th>Apellido Materno</th>
                <th>Apellido Paterno</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Telefono Movil</th>
                
			</tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id SCJ</th>
                <th>Rut</th>
                <th>Nombre</th>
                <th>Apellido Materno</th>
                <th>Apellido Paterno</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Telefono Movil</th>
                <th>Nombre</th>
                <th>Apellido Materno</th>
                <th>Apellido Paterno</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Telefono Movil</th>
            </tr>
        </tfoot>
        <tbody>

        	<?php foreach ($datos_bdscj as $key => $nombre) {
            	
                $ae_scj = $nombre['id'];
                $rut = $nombre['run'];
            	$nombreae = $nombre['first_name'];
            	$apellidom = $nombre['last_name'];
            	$apellidop = $nombre['second_last_name'];
            	$email = $nombre['email'];
            	$phone = $nombre['phone'];
            	$movil = $nombre['mobile_phone'];
                if(isset($nombre['assignee']['first_name']))$nombreap = $nombre['assignee']['first_name'];
                if(isset($nombre['assignee']['last_name']))$apellidomap = $nombre['assignee']['last_name'];
                if(isset($nombre['assignee']['second_last_name']))$apellidopap = $nombre['assignee']['second_last_name'];
                if(isset($nombre['assignee']['email']))$emailap = $nombre['assignee']['email'];
                if(isset($nombre['assignee']['phone']))$phoneap = $nombre['assignee']['phone'];
                if(isset($nombre['assignee']['mobile_phone']))$movilap = $nombre['assignee']['mobile_phone'];

	           ?>
            <tr>
                <td><?php echo $ae_scj?></td>
            	<td><?php echo $rut?></td>
                <td><?php echo $nombreae?></td>
                <td><?php echo $apellidom?></td>
                <td><?php echo $apellidop?></td>
                <td><?php echo $email?></td>
                <td><?php echo $phone?></td>
                <td><?php echo $movil?></td>
                <td><?php echo $nombreap?></td>
                <td><?php echo $apellidomap?></td>
                <td><?php echo $apellidopap?></td>
                <td><?php echo $emailap?></td>
                <td><?php echo $phoneap?></td>
                <td><?php echo $movilap?></td>
                
				
            </tr>
            <?php } ?>
			</tbody>
			</table>
		</div>
	</div>



	<script type="text/javascript" src="js/sweetalert.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.4.5.0.js"></script>
	
    <script type="text/javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="js/dataTablesButton.min.js"></script>


   
    
    <script type="text/javascript" src="js/jszip.js"></script>
    <script type="text/javascript" src="js/pdfMake.js"></script>
    <script type="text/javascript" src="js/vfs_fonts.js"></script>
    <script type="text/javascript" src="js/buttons.html5.js"></script>
    <script type="text/javascript" src="js/buttons.print.js"></script>
    
    <script type="text/javascript" src="js/DataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
    <script type="text/javascript" src="js/Buttons.Bootstrap.min.js"></script>

    <script type="text/javascript">
    	$(document).ready(function() {
    $('#lista').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
    </script>
    
</html>