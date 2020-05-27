<?php
session_start();
require_once "database_connection.php";

$from_user_id = $_SESSION['user_id'];

 echo fetch_user_chat_history($_POST['to_user_id'],$from_user_id ,$connect);
