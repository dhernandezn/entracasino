<?php
require_once('database.php');
require_once("validar.php");

try {

    //llenador de tabla
    $cbd = Database::getInstance();
    $consulta = $cbd -> prepare('SELECT * FROM log');
    $consulta->execute();
    //paginador
    // $consulta_pg = $cbd -> prepare('SELECT COUNT(*) as total_reg FROM colegios');
    // $consulta_pg->execute();
    // $res_pg=$consulta_pg->fetch(PDO::FETCH_ASSOC);
    // $por_pg = 5;
    // if (empty($_GET['pagina'])) {
    //     $pagina = 1;
    // }else{
    //     $pagina = $_GET['pagina'];
    // }
    // $desde = ($pagina-1) * $por_pg;
    // $total_pg = ceil($res_pg / $por_pg);

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
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
	<script src="js/js.js"></script>
    <script>
		window.onload=cap;
	</script>
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
                            <h2>Administrar <b>Entradas</b></h2>
                        <?php echo $resultado[""];?>
                        </div>
                        <div class="col-sm-6">
                            <!-- <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Añadir nuevo colegio</span></a>-->
                            <a href="show_all.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Todos</span></a>
                            <a href="index.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Volver</span></a>
                            <a href="subir.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Importar</span></a>
                            <a href="bajar.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Exportar</span></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>RUT</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>Autoexcluido</th>
                            <th>PEP</th>
                            <th>Prohibido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($resultado = $consulta->fetch(PDO::FETCH_ASSOC)){ ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <tr>
                                <td><?php echo $resultado["rut_ingre"]?></td>
                                <td><?php echo $resultado["fecha"]?></td>
                                <td><?php echo $resultado["hora"]?></td>
                                <td><?php echo $resultado["autoexc"]?></td>
                                <td><?php echo $resultado["cli_pep"]?></td>
                                <td><?php echo $resultado["cli_prohibido"]?></td>
                        </tr>
                        </form>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="clearfix">
                    <div class="hint-text">Mostrando <b>5</b> de <b><?php //echo $res_pg["total_reg"]?></b> entradas</div>
                    <ul class="pagination">
                        <li class="page-item disabled"><a href="#">Anterior</a></li>
                        <li class="page-item active"><a href="#" class="page-link">1</a></li>
                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                        <li class="page-item"><a href="#" class="page-link">3</a></li>
                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                        <li class="page-item"><a href="#" class="page-link">Siguiente</a></li>
                    </ul>
                </div>
            </div>
        </div> 
    </div>
   
</body>
</html>