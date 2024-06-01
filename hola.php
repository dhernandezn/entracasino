<?php 
echo "hola";
echo "consulta";
try {
    $dbmysql = Databasemysql::getInstance();
$consulta=$dbmysql->prepare("SELECT * from log");
echo "consulta2";
$consulta->execute();
$resultado = $consulta->fetch(PDO::FETCH_ASSOC);
if($resultado){
    echo "tamos";
}else{
    echo "NO";
}

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>yaaa</title>
</head>
<body>
    <div>mirame</div>
</body>
</html>