<?php 

session_start();

$user_id = $_SESSION['user_id'];
require_once "database_connection.php";

$query = "UPDATE  login_details SET  last_activity = 'NULL' where user_id = '$user_id'";

$statment = $connect->prepare($query);
$statment->execute();

session_unset();
session_destroy();
header("Location:login.php");

