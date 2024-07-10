<?php
require_once('database.php');
require_once("validar.php");
try {

    //llenador de tabla
    $cbd = Database::getInstance();
    $consulta = $cbd -> prepare('SELECT * FROM log WHERE estado_ticket = 1 ORDER BY id_log DESC');
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

    if (isset($_POST['insertar3'])) {
        $model2 = new Consultas();
        $model2 -> id_ = htmlspecialchars($_POST["id_cli"]);
        $model2 -> n_entrada = htmlspecialchars($_POST["n_entrada"]);

        $model2 -> editar_ticket();
        $mensaje2 = $model2 -> mensaje2;
        $mensaje3 = $model2 -> mensaje3; 
        
    }
    session_start();
    if(isset($_POST['login'])){
        $user = $_POST['user'];
        $pwd = $_POST['pass'];

        $dbh = Database::getInstance();
        echo "ESTAS INTENTANDO INGRESAR";
    }
    

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
        // function mirar(){
	
        //     var a = document.getElementById("i_cli").value;
        //     console.log(a);
        //     var m = document.getElementById("rut_cli").value = a;
        //     //var ru = document.getElementById("ru").value;
        //     var d = document.getElementById("id_").value = m;
        //     console.log("valor:"+a);
        //     console.log(a+"->"+d);
        //     console.log(d);
            
        // };
    </script>
    <script>
    function cap(){
        var a = '0';
			a = document.getElementById("val1").value;
			//b = document.getElementById("valu1").value;
			console.log(a);
            //mirar();
			switch (a) {
				case '1':
					$("#editar_entrada").modal({backdrop:'static',keyboard:false});
                       
                    console.log("AUTOEX");
					break;
				default:
					break;
			}
    };
    </script>
    <script>
		window.onload=cap;
        function modalLogin(){
            $( document ).ready(function() {
                $('#modlogin').modal('toggle')
            });
        }
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
                        <button class="btn" type="submits" name="borrars" value="borrars" onclick="modalLogin()"><span class="material-icons">manage_accounts</span></button>
                            <a href="show_all.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Todos</span></a>
                            <a href="index.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Volver</span></a>
                            <!-- <a href="subir.php" class="btn btn-danger"><i class="material-icons">block</i> <span>Importar</span></a> -->
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>RUT</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>N° ENTRADA</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($resultado = $consulta->fetch(PDO::FETCH_ASSOC)){ ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <tr>
                            <td></td>
                            
                                <td><?php echo $resultado["rut_ingre"]?></td>
                                <td><?php echo $resultado["fecha"]?></td>
                                <td><?php echo $resultado["hora"]?></td>
                                <td><input type="text" class="form-control" name="n_entrada" id="n_entrada" value="<?php echo $resultado["n_entrada"]?>" required></td>
                                <td style="display:none;"><input type="hidden" name="id_" id="id_" value="<?php echo $resultado["id_log"]?>"><?php echo $resultado["id_log"]?></td>
                                <td>
                                    <input type="hidden" name="insertar3">
                                    <input type="submit" value="&#xE254;" class="material-icons">
                                </td>
                            
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
        <!-- MODAL LOGIN -->
        <div class="modal fade" id="modlogin">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">LOGIN</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <!-- Modal body -->
                    <div class="modal-body">
                           
                            <div class="mb-3">
                                <label for="user" class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="user" id="user" required/>
                            </div>
                        
                            <div class="mb-3">
                                <label for="pass" class="form-label">Contraseña</label>
                                <input type="password" id="pass" class="form-control input-sm" name="pass" value="" onchange="mirarFecha()" required>
                            </div>
                        <hr>
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input type="hidden" name="login">
                        <button type="submit" name="btnAction" value="Confirmar" class="btn btn-primary">Entrar</button>   
                    </div>
                    </form>
                </div>
            </div>
        </div> 
    </div>
   
</body>
</html>