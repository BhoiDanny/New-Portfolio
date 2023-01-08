<?php require("../config/_init_.php");
   global $help, $mail;

   if(isset($_POST['user_first']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){
      $name    = $_POST['name'];
      $email   = $_POST['email'];
      $subject = $_POST['subject'];
      $message = htmlentities(trim($_POST['message']));

      $site_template = file_get_contents("message-user.html");
      $site_template = str_replace("{{recipient}}",$help::env('APP_NAME'),$site_template);
      $site_template = str_replace("{{name}}",$name,$site_template);
      $site_template = str_replace("{{message}}",$message,$site_template);
      $site_template = str_replace("{{email}}",$email,$site_template);
      $site_template = str_replace("{{subject}}",$subject,$site_template);
      $site_template = str_replace("{{date}}",date('Y'),$site_template);
      $site_template = str_replace("{{app_name}}",$help::env('APP_NAME'),$site_template);



      try {
         $mail->isHTML(true);
         $mail->CharsetUTF8();
         $mail->addFrom($help::env('MAIL_USERNAME'),$help::env('APP_NAME'));
         $mail->to($help::env('MAIL_USERNAME'));
         $mail->replyTo($email,$name);
         $mail->Subject($subject);
         $mail->Body($site_template);

         if($mail->send()){
            //$mail->clearAddress();
            print json_encode(array(
               'status' => 'success',
               'message' => 'Thanks, your message is sent successfully.'
            ));
         } else {
            print json_encode(array(
               'status' => 'failed',
               'message' => 'Sorry, your message is not sent.' .$mail->Error()
            ));
         }
      } catch (\Exception $e) {
         print json_encode(array(
            'status' => false,
            'message' => $e->getMessage()
         ));
      }

   }

