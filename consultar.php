<?php 
	require_once("database.php");

	$dbh = Database::getInstance();


    
        $consulta = $dbh -> prepare("SELECT * from log");
        $consulta -> execute();




 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Consultar cliente</title>
	<link rel="icon" href="css/images/logofavico.png" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/pace.css">
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
</head>
<body>
	<div class="contenedor">
		<div><h2>Historial de Consultas</h2></div>
		<!--<h1>Cons</h1>-->
		<div class="tabla table-responsive">
			<table class="table table-striped table-bordered table-conden" id="lista" cellspacing="0" width="100%">
				<thead>
            <tr>
            	<th>Fecha</th>
                <th>Hora</th>
                <th>Rut consultado</th>
                <th>AE</th>
                
                
			</tr>
        </thead>
        
        <tbody>

        	<?php while($resultado = $consulta->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                
            	<td><?php echo $resultado["fecha"]?></td>
                <td><?php echo $resultado["hora"]?></td>
                <td><?php echo $resultado["rut_ingre"]?></td>
                <td><?php echo $resultado["autoexc"]?></td>
                
            </tr>
            <?php } ?>
			</tbody>
			</table>
		</div>
		<!--<input type="text" name="abc" autofocus>-->
	</div>

	<script type="text/javascript" src="js/sweetalert.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	
    <script type="text/javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="js/dataTablesButton.min.js"></script>


   
    
    <script type="text/javascript" src="js/jszip.js"></script>
    <script type="text/javascript" src="js/pdfMake.js"></script>
    <script type="text/javascript" src="js/vfs_fonts.js"></script>
    <script type="text/javascript" src="js/buttons.html5.js"></script>
    <script type="text/javascript" src="js/buttons.print.js"></script>
    
    <script type="text/javascript" src="js/DataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
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
</body>
</html>