<?php
$host = '192.168.10.18'; 
$db = 'circuitocerradoN102';
$user = 'sa';
$pass = 'dbmaster*987';

$dsn = "sqlsrv:server=$host;database=$db";


try {
    $pdo = new PDO($dsn, $user, $pass);
   // echo "Conexión exitosa a SQL Server.";
} catch (\PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    exit;
}
?>

