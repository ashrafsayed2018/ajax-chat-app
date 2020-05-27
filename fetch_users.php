<?php 
session_start();
 require_once "database_connection.php";
if(!isset($_SESSION['user_id'])) {
    header("Location:login.php");
}
$user_id = $_SESSION['user_id'];
function fetch_user_last_activity($user_id,$connect) {
    $query = "SELECT * FROM login_details WHERE user_id = '$user_id' ORDER BY last_activity DESC";

    $statment = $connect->prepare($query);
    $statment->execute();
    $result = $statment->fetchAll();

    foreach($result as $row) {
        return $row['last_activity'];
    }
 
}
$query = "SELECT * FROM login WHERE user_id != '$user_id'";

$stmt = $connect->query($query);

$stmt->execute();

 $rowCount = $stmt->rowCount();

 $output = '<table class="table-bordered table-hover table-responsive col-lg-12">
                <tr>
                <th>Username</th>
          
                <th>Unread messages </th>
                <th>Status</th>
                <th>Action</th>
                </tr>
          ';

 if($rowCount > 0) {
    
    foreach ( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

        $status = '';
        $current_timestamp = strtotime(date('Y-m-d  H:i:s') . " - 10 seconds");
        $current_timestamp = date('Y-m-d  H:i:s' ,$current_timestamp);
        $user_last_activity  = fetch_user_last_activity($row['user_id'],$connect);

        if($user_last_activity > $current_timestamp) {

            $status = "<label class='alert alert-success'> Online </label>";

        } else {
            $status = "<label class='alert alert-danger'>Offline</label>";
        }

        $output .= '
        <tr> 
            <td>'.$row['username'].'
             '.fetch_is_type_status($row['user_id'],$connect).'</td>
            <td class="unread_msgs">'.count_unseen_messages($row['user_id'],$_SESSION['user_id'],$connect).'</td>
            <td>'.$status.'</td>
            <td>
                <button class="btn btn-info btn-xs start-chat" data-touserid='.$row['user_id'].' data-tousername='.$row['username'].'>
                    Start Chat 
                </button>
            </td>
        </tr>
    
        ';

    }
     $output .= "</table>";

echo $output;

  
 }




?>







