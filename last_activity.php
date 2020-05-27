<?php 
session_start();
date_default_timezone_set("Asia/Kuwait");
require_once "database_connection.php";
if(!isset($_SESSION['user_id'])) {
    header("Location:login.php");
}
$login_details_id = $_SESSION['login_details_id'];
$date = date("Y-m-d h:i:s");
$query = "UPDATE login_details SET last_activity = '$date' WHERE login_details_id = '$login_details_id' ";

$stmt = $connect->query($query);

$stmt->execute();
