<?php 
session_start();
 require_once "database_connection.php";
if(!isset($_SESSION['user_id'])) {
    header("Location:login.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

 <div class="container">
     <h3>Chat Application using php and jquery and ajax</h3>
     <br><br>
     <div class="table table-responsive">
           <h4 class="text-center">Oline Users</h4>
           <p class="text-right">
           Hi <?php echo $_SESSION['username'];?>

                 <a href="logout.php" class="btn btn-danger">Logout</a>
           
           </p>
           <div id="user-details"></div>
           <div id="user_model_details"></div>
     </div>

 </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.js"></script>

    <script>
    $(document).ready(function() {
          // fetch user function 

          function fetch_user() {
              $.ajax({
                  url : "fetch_users.php",
                  method : "POST",
                  success : function ($data) {
                      $("#user-details").html($data);
                  }
               })
          };
       
          fetch_user();
          // function to update last activity 

          function update_last_activity() {
             $.ajax({
                 url: "last_activity.php",
                 method: "post",
                 success : function () {

                 }
             })
          }

          // function to update the last activity evey 5 seconds 

          setInterval(() => {
            update_last_activity();
            fetch_user();
            update_chat_history_data();
          }, 1000);

          // make chat dialog box 

          function make_chat_dialog_box(to_user_id,to_user_name) {
              var model_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_name+'" style="background:#fffef7">';

              model_content += "<div style='width:400px;height:400px;border:1px solid #ccc;overflow-y:scroll;margin-bottom:24px;padding:16px' class='chat_history' data-touserid='"+to_user_id+"' id='chat_history_"+to_user_id+"'>";
              model_content +=   fetch_user_chat_history(to_user_id);
              model_content += ' </div><div class="form-group" style="width:400px">';
              model_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea> </div>';
              model_content += '<div class="form-group text-right" style="width:400px">';
              model_content += '<button type="button" name="send_chat" class="btn btn-info send_chat" id="'+to_user_id+'">chat</button></div></div>';

              $('#user_model_details').html(model_content);

          }


          // on click on start chat

          $(document).on('click','.start-chat', function () {
               var to_user_id = $(this).data('touserid');
               var to_user_name = $(this).data('tousername');
             
               make_chat_dialog_box(to_user_id,to_user_name);
               $('#user_dialog_'+to_user_id).dialog({
                   autoOpen : false,
                   width: 400
               });
               $('#user_dialog_'+to_user_id).dialog('open');
               $('.ui-dialog-titlebar-close').html('x');
               $('#chat_message_'+to_user_id).emojioneArea({
                pickerPosition : "top",
                toneStyle : "bullet"
               });
              

          });

          // function when click on send chat button 

          $(document).on('click','.send_chat',function () {
             var to_user_id = $(this).attr('id');

             var chat_message = $.trim($('#chat_message_'+to_user_id).val());
             if(chat_message.length > 0) {
                $.ajax({
                 url : "insert_chat.php",
                 method : "post",
                 data : {to_user_id: to_user_id, chat_message: chat_message} ,
                 success : function (data) {
                    // $('#chat_message_'+to_user_id).val(' ');
                    var elenent = $('#chat_message_'+to_user_id).emojioneArea();
                    elenent[0].emojioneArea.setText('');
                    $("#chat_history_"+to_user_id).html(data);
                   
                 }
             });

           
         
             }
    
      
          });


          // function to fetch the chat history for certain user 

          function fetch_user_chat_history(to_user_id) {
              $.ajax({
                  url : "fetch_user_chat_history.php",
                  method : "post",
                  data : {to_user_id: to_user_id},
                  success : function (data) {
                    $("#chat_history_"+to_user_id).html(data);
                  }
              })
          }

          // function of the real time update chat history 

          function update_chat_history_data () {
              $('.chat_history').each(function() {
                  var to_user_id = $(this).data('touserid');
                  fetch_user_chat_history(to_user_id);
              })
          }

          // function to update the unread message when open start chat 
          $(document).on('click','.start-chat', function () {
            var to_user_id = $(this).data('touserid');

            $.ajax({
                url : "seen_messages.php",
                method : "post",
                data : {to_user_id: to_user_id},

                success : function (data) {
                }
            })

          });


        //   whent the chat message is focused the typeing messing is showing to the user 

        $(document).on('keyup keydown','.chat_message', function () {
            var is_typing = "yes";
            $.ajax({
                url : "update_is_type_status.php",
                method : 'POST',
                data : {is_typing : is_typing},
                success : function (data) {
                    console.log(data)

                }
            })
        });
                //   whent the chat message is focused the typeing messing is showing to the user 

        $(document).on('blur','.chat_message', function () {
            var is_typing = "no";
            $.ajax({
                url : "update_is_type_status.php",
                method : 'POST',
                data : {is_typing : is_typing},
                success : function (data) {
                    console.log(data)

                }
            })
        });
    });
    </script>
</body>
</html>



