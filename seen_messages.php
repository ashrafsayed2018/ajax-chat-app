<?php 

session_start();
 require_once "database_connection.php";
 $from_user_id = $_POST['to_user_id'];
 $to_user_id = $_SESSION['user_id'];
 
$update_query = "UPDATE chat_message SET msg_status = '0' WHERE to_user_id = '$to_user_id' and from_user_id ='$from_user_id' and msg_status = '1'";

$statment = $connect->prepare($update_query);
$statment->execute();


