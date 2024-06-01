<?php
require_once('database.php');
require_once("validar.php");
try {
    
    if(isset($_POST["cuando"])){
        $fecha = $_POST["fecha_select"];
        $cbd = Database::getInstance();
        $cantidad = $cbd->prepare("SELECT COUNT(id_log) as peoples
        FROM log
        WHERE (fecha = :v_h)");
        $cantidad->bindValue(':v_h',$fecha);
        $cantidad->execute();
        $total_entr = $cantidad->fetch(PDO::FETCH_ASSOC);
        $mensaje_ce = $total_entr["peoples"];
        
        $consulta = $cbd -> prepare('SELECT * FROM log WHERE (fecha = :v_h) AND (estado_ticket = 1 OR estado_ticket = 2 OR estado_ticket = 3)');
        $consulta->bindValue(':v_h',$fecha);
        $consulta->execute();
    }
    //REVISAR -> CANTIDAD DEPERSONAS EN INDEX

} catch (Exception $e) {
    //echo $e->errorMessage();
    echo $e;
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
    <link rel="stylesheet" href="css/jquery-ui.min.css">
	<link rel="stylesheet" href="css/style.css">


	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.js"></script>
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
        function fecha(){
            var calendario = document.getElementById('datepicker').value;
            console.log(calendario);
        };

	</script>
    <script>
  $( function() {
    $( "#datepicker" ).datepicker({
		dateFormat: "dd-mm-yy"
	});
  } );
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
                            <h2>Historial <b>Entradas</b></h2>
                        <?php echo $resultado[""];?>
                        
                        </div>
                        <div class="col-sm-6">
                        <span>Entradas: <?php echo $mensaje_ce?></span>
                            <!-- <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Añadir nuevo colegio</span></a>
                            <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Eliminar</span></a> -->
                            <a href="index.php" class="btn btn-danger"><i class="material-icons">analytics</i> <span>Volver</span></a>
                            
                            
                        </div>
                    </div>
                </div>
                <div>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <label for="">Fecha</label>
                        <input type="text" id="datepicker" name="fecha_select" value="" required autocomplete="off">
                        <input type="hidden" name="cuando">
                        <button onclick="fecha()" class="btn btn-warning">Actualizar</button>
                    </form> 
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>RUT o PASAPORTE</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($resultado = $consulta->fetch(PDO::FETCH_ASSOC)){ ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <tr>
                                <td><?php echo $resultado["rut_ingre"]?></td>
                                <td><?php echo $resultado["fecha"]?></td>
                                <td><?php echo $resultado["hora"]?></td>                            
                        </tr>
                        </form>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="clearfix">
                    <div class="hint-text">Mostrando <!--<b>5</b> de--> <b><?php echo $mensaje_ce?></b> entradas</div>
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
    <!-- <div class="modal fade" id="editar_entrada">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="tlt-mod">Ingreso de Cliente</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <strong style="display: none" id="mensajes"><?php echo $mensaje3; ?></strong>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        
                        <div class="form-group">
                            <label>Ingrese el Numero de Entrada</label>
                            <input id="n_entrada" name="n_entrada" type="text" placeholder="xxxxxx-x" class="form-control" required autofocus>
                            <input type="hidden" name="id_" id="id_" value=""><br>
                            <strong id="mensajes" ><?php echo $mensaje3; ?></strong>
                        </div>
                        <strong style="display: none" id="mensajes"><?php echo $mensaje2; ?></strong>
                        <div class="modal-footer">
                            <strong id="msj2" name="msj2" value="1" ></strong>
                            
                            <input type="hidden" name="insertar3">
                            <input type="submit" class="btn btn-success" name="insert" value="Ingresar Entrada">
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
	</div> -->
</body>
</html>