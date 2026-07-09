<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "duka_bora";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database Connection Critical Failure: " . mysqli_connect_error());
}
?>