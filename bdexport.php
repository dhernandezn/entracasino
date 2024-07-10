<?php
require_once("database.php");

function exportarTablas($host, $usuario, $pasword, $nombreDeBaseDeDatos)
{
    set_time_limit(3000);
    $tablasARespaldar = [];
    $mysqli = new mysqli($host, $usuario, $pasword, $nombreDeBaseDeDatos);
    $mysqli->select_db($nombreDeBaseDeDatos);
    $mysqli->query("SET NAMES 'utf8'");
    $tablas = $mysqli->query('SHOW TABLES');
    while ($fila = $tablas->fetch_row()) {
        $tablasARespaldar[] = $fila[0];
    }
    $contenido = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $nombreDeBaseDeDatos . "`\r\n--\r\n\r\n\r\n";
    foreach ($tablasARespaldar as $nombreDeLaTabla) {
        if (empty($nombreDeLaTabla)) {
            continue;
        }
        $datosQueContieneLaTabla = $mysqli->query('SELECT * FROM `' . $nombreDeLaTabla . '`');
        $cantidadDeCampos = $datosQueContieneLaTabla->field_count;
        $cantidadDeFilas = $mysqli->affected_rows;
        $esquemaDeTabla = $mysqli->query('SHOW CREATE TABLE ' . $nombreDeLaTabla);
        $filaDeTabla = $esquemaDeTabla->fetch_row();
        $contenido .= "\n\n" . $filaDeTabla[1] . ";\n\n";
        for ($i = 0, $contador = 0; $i < $cantidadDeCampos; $i++, $contador = 0) {
            while ($fila = $datosQueContieneLaTabla->fetch_row()) {
                //La primera y cada 100 veces
                if ($contador % 100 == 0 || $contador == 0) {
                    $contenido .= "\nINSERT INTO " . $nombreDeLaTabla . " VALUES";
                }
                $contenido .= "\n(";
                for ($j = 0; $j < $cantidadDeCampos; $j++) {
                    $fila[$j] = str_replace("\n", "\\n", addslashes($fila[$j]));
                    if (isset($fila[$j])) {
                        $contenido .= '"' . $fila[$j] . '"';
                    } else {
                        $contenido .= '""';
                    }
                    if ($j < ($cantidadDeCampos - 1)) {
                        $contenido .= ',';
                    }
                }
                $contenido .= ")";
                # Cada 100...
                if ((($contador + 1) % 100 == 0 && $contador != 0) || $contador + 1 == $cantidadDeFilas) {
                    $contenido .= ";";
                } else {
                    $contenido .= ",";
                }
                $contador = $contador + 1;
            }
        }
        $contenido .= "\n\n\n";
    }
    $contenido .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

    # Se guardará dependiendo del directorio, en una carpeta llamada bdrespaldos
    $carpeta = "/home/dhernandez/BDRESPALDOS";
    if (!file_exists($carpeta)) {
        echo"CREAR carpeta";
        mkdir($carpeta);
    }

    # Calcular un ID único
    $id = uniqid();

    # También la fecha
    $fecha = date("Y-m-d");

    # Crear un archivo que tendrá un nombre como respaldo_2018-10-22_asd123.sql
    $nombreDelArchivo = sprintf('%s/respaldo_%s_%s.sql', $carpeta, $fecha, $id);
    #Escribir todo el contenido. Si todo va bien, file_put_contents NO devuelve FALSE
    return file_put_contents($nombreDelArchivo, $contenido) !== false;

}
class Busquedas{

    private $_dbh;
    private $lista_clientes;
    public function __construct()
    {
        $this->_dbh = Database::getInstance();
    }
    public function listarAutoExcluidos(){
        $consulta = $this->_dbh->prepare("SELECT autoexcluidos_scj.ae_rut, autoexcluidos_scj.ae_nombre
         FROM autoexcluidos_scj WHERE 1");
        $consulta->execute();
        $this->lista_clientes = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        return $this->lista_clientes;
    }
    public function listarPep(){
        $consulta = $this->_dbh->prepare("SELECT * FROM pep;");
        $consulta->execute();
        $this->lista_clientes = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        return $this->lista_clientes;
    }
    public function listarProhibidos(){
        $consulta = $this->_dbh->prepare("SELECT * FROM prohibidos");
        $consulta->execute();
        $this->lista_clientes = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        return $this->lista_clientes;
    }
    public function expConting(){
        $salida = "";
        $salida .= "<table>";
        $salida .= "<thead><tr><td><h3>AUTOEXCLUIDOS</h3></tr></td></thead>";
        $salida .= "<thead><th>RUT</th><th>NOMBRE</th></thead>";
        
        foreach($this->listarAutoExcluidos() as $r ){
            $salida .= "<tr><td>" .$r["ae_rut"]."</td><td>".$r["ae_nombre"]."</td></tr>";
        }
        $salida.=  "<tr><td></td><td></td></tr>";
        $salida .= "</table>";
        $salida .= "<table>";
        $salida .= "<thead><tr><td><h3>PEP</h3></tr></td></thead>";
        $salida .= "<thead><th>RUT</th><th>NOMBRE</th></thead>";
        
        foreach($this->listarPep() as $r1 ){
            $salida .= "<tr><td>" .$r1["pep_rut"]."</td><td>".$r1["pep_nombre"]."</td></tr>";
        }
        $salida.=  "<tr><td></td><td></td></tr>";
        $salida .= "</table>";
        $salida .= "<table>";
        $salida .= "<thead><tr><td><h3>PROHIBIDOS</h3></tr></td></thead>";
        $salida .= "<thead><th>RUT</th><th>NOMBRE</th></thead>";
        
        foreach($this->listarProhibidos() as $r2 ){
            $salida .= "<tr><td>" .$r2["rut"]."</td><td>".$r2["nombre"]."</td></tr>";
        }
        $salida .= "</table>";
        // header("Content-Type: text/html;charset=utf-8");
        // //header("Content-type: application/vnd.ms-excel");
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Autoexcluidos.xls"');
        // header('Cache-Control: max-age=0');
        # Calcular un ID único
        $id = uniqid();
        $carpeta = "/home/dhernandez/BDRESPALDOS";
        # También la fecha
        $fecha = date("Y-m-d");

        # Crear un archivo que tendrá un nombre como respaldo_2018-10-22_asd123.sql
        $nombreDelArchivo = sprintf('%s/respaldo_%s_%s.xls', $carpeta, $fecha, $id);
        #Escribir todo el contenido. Si todo va bien, file_put_contents NO devuelve FALSE
        return file_put_contents($nombreDelArchivo, $salida) !== false;
        echo $salida;
    }
    public function expPep(){
        $salida = "";
        $salida .= "<table>";
        $salida .= "<thead><tr><td><h3>PEP</h3></tr></td></thead>";
        $salida .= "<thead><th>RUT</th><th>NOMBRE</th></thead>";
        
        foreach($this->listarPep() as $r ){
            $salida .= "<tr><td>" .$r["pep_rut"]."</td><td>".$r["pep_nombre"]."</td></tr>";
        }
        $salida.=  "<tr><td></td><td></td></tr>";
        $salida .= "</table>";
        $salida .= "<table>";
        $salida .= "<thead><tr><td><h3>PROHIBIDOS</h3></tr></td></thead>";
        $salida .= "<thead><th>RUT</th><th>NOMBRE</th></thead>";
        
        foreach($this->listarProhibidos() as $r1 ){
            $salida .= "<tr><td>" .$r1["rut"]."</td><td>".$r1["nombre"]."</td></tr>";
        }
        $salida .= "</table>";
        header("Content-Type: text/html;charset=utf-8");
        //header("Content-type: application/vnd.ms-excel");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pep.xls"');
        header('Cache-Control: max-age=0');
        echo $salida;
    }
    public function expProhibidos(){
        $salida = "";
        $salida .= "<table>";
        $salida .= "<thead><tr><td><h3>PROHIBIDOS</h3></tr></td></thead>";
        $salida .= "<thead><th>RUT</th><th>NOMBRE</th></thead>";
        
        foreach($this->listarProhibidos() as $r ){
            $salida .= "<tr><td>" .$r["rut"]."</td><td>".$r["nombre"]."</td></tr>";
        }
        $salida .= "</table>";
        header("Content-Type: text/html;charset=utf-8");
        //header("Content-type: application/vnd.ms-excel");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="prohibidos.xls"');
        header('Cache-Control: max-age=0');
        echo $salida;
    }
}
$consu = new Busquedas();

$consu->expConting();
//$consu->expPep();
//$consu->expProhibidos();
exportarTablas('localhost','dahn','Dahn2022','saecc2');


?>
