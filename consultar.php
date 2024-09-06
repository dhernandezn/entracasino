<?php
require_once('database.php');
require_once("validar.php");
try {
    if (isset($_POST['buscar'])) {
        $model = new Consultas();
        $model -> rut = htmlspecialchars($_POST["busqueda"]);
        $datos= $model -> buscarRut($model->rut);
    }
} catch (\Throwable $th) {
    //throw $th;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    <link rel="stylesheet" href="css/dataTables.dataTables.min.css">
	<script src="js/js.js"></script>
</head>
<body>
<strong id="mensajes" value=""><?php echo $mensaje3; ?></strong>
<input type="hidden" name="rut_cli" id="rut_cli" value="">
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Consultar Cliente <b>Saecc App</b></h2>
                        <?php echo $resultado[""];?>
                        </div>
                        <div class="col-sm-6">
                            <!-- <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Añadir nuevo colegio</span></a>-->
                            <a href="index.php" class="btn btn-danger"><i class="material-icons">exit_to_app</i><span>Salir</span></a>
                            <a href="prohib.php" class="btn btn-danger"><i class="material-icons">person_off</i><span>C.PROH</span></a>
                            <a href="pep.php" class="btn btn-danger"><i class="material-icons">settings_accessibility</i><span>C.PEP</span></a>
                        </div>
                    </div>
                </div><hr>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal" method="post" enctype="multipart/form-data" name="f" autocomplete="off">
                <input type="text" name="busqueda" id="busqueda2" pattern="[0-9-k-K]{1,11}" value="" placeholder="xxxxxxxx-x" autocomplete="off" required autofocus>
                <input type="submit" value="Buscar" name="buscar">    
            </form><hr>
                <table id="tabla" class="table table-striped" style="width:100%" >
                <thead class="table-dark">
                    <tr>
                        <th>N°</th>
                        <th>RUT</th>
                        <th>Fecha Ingreso</th>
                        <th>PEP</th>
                        <th>Autoexcluido</th>
                        <th>Sospechoso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $listar = new Consultas();
                    foreach($datos as $resultado){
                    ?>
                        <tr>
                            <td><?php echo $resultado["id_log"];?></td>
                            <td><?php echo $resultado["rut_ingre"];?></td>
                            <td><?php echo $resultado["fecha_hora"];?></td>
                            <td><?php echo $resultado["cli_pep"];//Categoría Cliente?></td>
                            <td><?php echo $resultado["autoexc"];//Nombre Cliente?></td>
                            <td><?php echo $resultado["cli_sospechoso"];?></td>
                    </tr>
                    <?php }?>
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th>N°</th>
                        <th>RUT</th>
                        <th>Fecha Ingreso</th>
                        <th>PEP</th>
                        <th>Autoexcluido</th>
                        <th>Sospechoso</th>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div> 
    </div>
    <script src="js/dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                let table = new DataTable('#tabla', {
                paging: true,       // Habilitar paginación
                searching: false,    // Habilitar búsqueda
                ordering: false,     // Habilitar ordenación de columnas
                pageLength: 5       // Mostrar 5 filas por página
            });
        });
    </script>
</body>
</html>