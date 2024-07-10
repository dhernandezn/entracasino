<?php
require_once('database.php');
require_once("validar.php");
date_default_timezone_set("America/Santiago");
$hoy = date ("d-m-Y",time());
try {
    
header("Pragma: public");
header("Expires: 0");
$filename = "informe_acceso_clientes.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

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
</head>
<body>
    <h1>Consulta de Clientes</h1>
    <hr>

    <h3>Fecha: <?php echo $hoy;?></h3>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>RUT</th>
                <th>FECHA</th>
                <th>HORA</th>
                <th>Autoexcluido</th>
                <th>PEP</th>
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
            </tr>
            </form>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>