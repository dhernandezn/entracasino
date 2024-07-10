<?php 
session_start();
require_once("database.php");
require_once("validar.php");
//require_once("importar_csv.php");

try {
	$dbh = Database::getInstance();

//	$consulta = $dbh->prepare('SELECT * FROM perfiles');
//	$consulta->execute(); 
	
	
$mensaje = null;

if (isset($_POST['insertar'])) {
	$model = new Consultas();
	$model -> nombre = htmlspecialchars($_FILES['arch_carga']['size']);
	/*$model -> tipo = htmlspecialchars($_FILES['archivo1']['type']);
	$model -> tamaño = htmlspecialchars($_FILES['archivo1']['size']);
	$model -> ruta = htmlspecialchars($_FILES['archivo1']['tmp_name']);*/
	
	$model -> importar();
	$mensaje = $model -> mensaje; 

}
} catch (PDOException $e) {
	 echo "ERROR: " . $e->getMessage();
}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>## SAECC - Carga CSV ##</title>
	<link rel="icon" href="css/images/logofavico.png" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/pace.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/carga.js"></script>
</head>
<body>
	<div class="contenedor">
		

			<h2>Importar Datos</h2><br><br>
		<div class="formulario_s">
				<form action="importar_csv.php" method="post" enctype="multipart/form-data" name="fs" id="fs">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Para cargar datos seleccione el archivo .CSV para ser importado</label><br><br>
				    <input type="file" class="form-control" id="arch_carga" name="arch_carga">
				    <br>
				    <button class="btn btn-success" form="fs" type="submit" >Importar datos</button><br>
				    <label id="respuesta"></label>
				  </div>
				</form>
			<br>
			<div>
				<input type="hidden" name="insertar2">
				
			</div>
			
			<div id="respuesta2">
				
			<strong id="mensajes" value="<?php echo $mensaje; ?>"><?php echo $mensaje; ?></strong>
			</div>
				<div id="resultadoBusqueda"></div>
					<br>

					
			<strong></strong>

		</div>


	<script type="text/javascript" src="js/sweetalert.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>