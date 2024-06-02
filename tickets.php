<?php
require_once('database.php');
require_once("validar.php");
try {
    $model = new Consultas();
    $listarEntradas = $model->mostrarEntradas();
 
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
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" href="css/brands.min.css">
    <link rel="stylesheet" href="css/regular.min.css">
    <link rel="stylesheet" href="css/solid.min.css">
    <link rel="stylesheet" href="css/buttons.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
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
                <table id="listarEntr" class="table table-striped" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th></th>
                            <th>RUT</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listarEntradas as $entradas){ ?>
                        <tr>
                            <td></td>
                            <td><?php echo $entradas["rut_ingre"]?></td>
                            <td><?php echo $entradas["fecha"]?></td>
                            <td><?php echo $entradas["hora"]?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th></th>
                            <th>RUT</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                        </tr>
                    </tfoot>
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
    <script src="js/jquery-3.6.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="jquery-ui/jquery-ui.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>
    <script src="js/fontawesome.min.js"></script>
    <script src="js/brands.min.js"></script>
    <script src="js/regular.min.js"></script>
    <script src="js/solid.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/buttons.bootstrap5.js"></script>
    <script src="js/dataTables.buttons.min.js"></script>
    <script src="js/buttons.html5.min.js"></script>
    <script src="js/buttons.print.min.js"></script>
    <script src="js/jszip.min.js"></script>
    <script src="js/pdfmake.min.js"></script>
    <script src="js/vfs_fonts.js"></script>
    <script src="js/buttons.colVis.min.js"></script>
    <script>
     $(document).ready(function () {
        $('#listarEntr').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend:'csv',
                    title: 'Historial Invitaciones',
                    filename: 'Historial Invitaciones',
                },
                {
                    extend:'pdf',
                    title: 'Historial Invitaciones',
                    filename: 'Historial Invitaciones',
                },
                {
                    extend:'print',
                    title: 'Historial Invitaciones',
                    filename: 'Historial Invitaciones',
                },
                {
                    extend:'excel',
                    title: 'Historial Invitaciones',
                    filename: 'Historial Invitaciones',
                }
            ]
        });
    });
</script>
</body>
</html>