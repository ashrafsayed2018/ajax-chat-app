<?php 

session_start();
require_once "database_connection.php";

$is_typing = $_POST['is_typing'];
$login_details_id = $_SESSION['login_details_id'];

$query = "UPDATE  login_details SET is_typing = '$is_typing' WHERE login_details_id = '$login_details_id'";
$statment = $connect->prepare($query);
$statment->execute();


if($statment->execute()) {
    $data ="the is typing field is updated successfully ";

    echo $login_details_id;
   
}