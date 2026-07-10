<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "duka_bora";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("An error occurred. Please try again.");
}
?>