<?php 

$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "bookstore";

$conn = new mysqli($servername, $username_db, $password, $dbname);

$conn->set_charset("utf8");

if($conn->connect_error){
    die("Connencton failed: " . $conn->connect_error);
}
