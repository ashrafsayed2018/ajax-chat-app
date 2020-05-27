
<?php 
session_start();
require_once "database_connection.php";

$message = '';
if(isset($_SESSION['user_id'])) {
    header("location:index.php");
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $sql = "SELECT * FROM login WHERE username = :username";

    $statment = $connect->prepare($sql);

    $statment->execute(array(
        ":username" => $_POST['username']
    ));

    $rowCount = $statment->rowCount();
  


    if($rowCount == 1) {
        $result = $statment->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {

            if($_POST['password'] == $row['password']) {

                $user_id = $row['user_id'];

                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $row['username'];

                //  query to insert the user id  into the login details table 

                    $query = "INSERT INTO  login_detailS (user_id) VALUES('$user_id') ";

                    $statment = $connect->query($query);

                    $statment->execute();
                   print_r($result);

                    $_SESSION['login_details_id'] = $connect->lastInsertId();
                    header("location:index.php");
         
            } else {
                $message = "<label class='alert alert-danger'>Wrong Password  </label>";
            }
        }

    } else {
        $message = "<label class='alert alert-danger'>Wrong user name </label>";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

    <div class="container">
       <h3> chat application using php and jquery and ajax </h3>
       <div class="panel panel-primary ">
           <div class="panel-heading text-center"> Chat Application Login </div>
           <div class="panel-body">
                <form action="" method="post">
                      <div class="form-gruop">
                            <label for="username"> Enter Your Username </label>
                            <input type="text" name="username" id="username" class="form-control"required>
                      </div>
                      <div class="form-gruop">
                            <label for="password"> Enter Your Password </label>
                            <input type="password" name="password" id="password" class="form-control"required >
                      </div>

                      <div class="form-gruop">
                            <input type="submit" name="login" id="login" class="btn btn-primary pull-right" value="login">
                      </div>
                 <?php echo $message; ?>
                </form>
           </div>
       </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>