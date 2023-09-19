<?php require("../config/_init_.php");
   global $help, $mail;

   if(isset($_POST['user_send']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){
      $name    = $_POST['name'];
      $email   = $_POST['email'];
      $subject = $_POST['subject'];
      $message = htmlentities(trim($_POST['message']));

      $user_template = file_get_contents("message.html");
      $user_template = str_replace("{{name}}",$name,$user_template);
      $user_template = str_replace("{{user}}",$help::env('MAIL_EMAIL'),$user_template);
      $user_template = str_replace("{{date}}",date('Y'),$user_template);
      $user_template = str_replace("{{app_name}}",$help::env('APP_NAME'),$user_template);
      $user_template = str_replace("{{my_email}}",$help::env('EMAIL'),$user_template);
      $user_template = str_replace("{{phone}}",$help::env('PHONE'),$user_template);

      try {
         $mail->isHTML(true);
         $mail->CharsetUTF8();
         $mail->addFrom($help::env('MAIL_USERNAME'), $help::env('APP_NAME'));
         $mail->to($email);
         $mail->Subject($subject);
         $mail->Body($user_template);

         if($mail->send()){
            print json_encode(array(
               'status' => 'success',
               'message' => 'Thanks, your message is sent successfully.'
            ));
         } else {
            print json_encode(array(
               'status' => 'failed',
               'message' => 'Sorry, your message is not sent.'
            ));
         }
      } catch (\Exception $e) {
         print json_encode(array(
            'status' => false,
            'message' => $e->getMessage()
         ));
      }
   }