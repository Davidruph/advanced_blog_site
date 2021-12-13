<?php

$server = "localhost";  //server host
$username = "root";	//Username of server
$password = "";  //Server password
$dbname = "one_meta";  //Name of the database

$conn = mysqli_connect($server, $username, $password, $dbname);

try {
    $connection = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e)
{
  echo $e->getMessage();                         
}


?>

