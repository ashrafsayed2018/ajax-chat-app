<?php
session_start();
$from_user_id = $_SESSION['user_id'];
$to_user_id = $_POST['to_user_id'];
require_once "database_connection.php";
$data = array (
    ':to_user_id'   => $_POST['to_user_id'],
    ':from_user_id' => $_SESSION['user_id'],
    ':chat_message'  => $_POST['chat_message'],
    ':msg_status'       => '1'
);

$query = "INSERT INTO chat_message (to_user_id,from_user_id,chat_message,msg_status) VALUES(:to_user_id,:from_user_id,:chat_message,:msg_status)";

$statment = $connect->prepare($query);
if($statment->execute($data)) {

    echo  fetch_user_chat_history($to_user_id,$from_user_id ,$connect);

}
?>






