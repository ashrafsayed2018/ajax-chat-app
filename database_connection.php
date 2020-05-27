<?php 

$servername = "localhost";
$user   = "root";
$password = "";
$db_name = "chat";



try {
    
    $connect = new PDO("mysql:host=$servername;dbname=$db_name;charset:utf8mb4",$user,$password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    die('connect failed ').$e->getMessage();
}

function fetch_user_chat_history($to_user_id,$from_user_id ,$connect) {
    $query = "SELECT * FROM chat_message WHERE (from_user_id = '$from_user_id' AND to_user_id ='$to_user_id') OR (from_user_id = '$to_user_id' AND to_user_id= '$from_user_id') ORDER BY timestamp DESC";
    $statment = $connect->prepare($query);
    $statment->execute();
    $result = $statment->fetchAll();
    $output = '<ul class="list-unstyled"></ul>';
    foreach($result as $row) {
        $user_name = '';
        if($row['from_user_id'] == $from_user_id) {
            $user_name = '<b class="text-success">You</b>';
        } else {
            $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'],$connect).'</b>';
        }

        $output .= '<li style="border-bottom:1px solid #ccc">
        <p>'.$user_name .'-- '.$row['chat_message'].'</p>
        <div class="text-right">
                <small><em>'.$row['timestamp'].'</em></small>
       </div>
        </li>';
    }
    $output .= "</ul>";


    return $output;
    
}

//  get username function 

function get_user_name($user_id,$connect) {
   
   $query = "SELECT username from login where user_id = '$user_id'";
   $statment = $connect->prepare($query);
   $statment->execute();
   $result = $statment->fetchAll();

 foreach($result as $row) {
     return $row['username'];
 }

}

// function to count unsean message 

function count_unseen_messages($from_user_id,$to_user_id,$connect) {
    $query = "SELECT * FROM chat_message WHERE from_user_id='$from_user_id' AND to_user_id='$to_user_id' AND msg_status ='1'";
    $statment = $connect->prepare($query);
    $statment->execute();
    $count_unseen = $statment->rowCount();
    $output = '';
    if($count_unseen > 0) {
        $output = '<span class="label label-success">'.$count_unseen.'</span>';
    }

    return $output;
}

// function to fetch is typing field 

function fetch_is_type_status($user_id,$connect) {
 $query = "SELECT is_typing FROM login_details WHERE user_id = '$user_id' ORDER BY last_activity DESC LIMIT 1";

 $statment = $connect->prepare($query);
 $statment->execute();

 $result = $statment->fetchAll();
 $output = '';

 foreach($result as $row) {
     if($row['is_typing'] == "yes") {
         $output .= '<small><em><span class="text-muted">Typing ...</span></em></small>';
     }
 }

 return $output;

}
?>



