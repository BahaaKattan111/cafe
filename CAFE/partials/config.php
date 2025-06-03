<?php
$server='localhost';
$user='root';
$password='';
$db='cafe';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
};
$conn = mysqli_connect($server,$user,$password,$db);
define('SITEURL','http://localhost/CAFE/');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>