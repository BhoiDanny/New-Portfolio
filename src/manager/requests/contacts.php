<?php require("../config/_init_.php");
global $help, $mail;

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){
   $name    = $_POST['name'];
   $email   = $_POST['email'];
   $subject = $_POST['email'];
   $message = $_POST['message'];

   $messages  = "<h3>New message from the site " . $help::env('APP_NAME') . "</h3> \r\n";
   $messages .= "<ul>";
   $messages .= "<li><strong>Name: </strong>" .$name."</li>";
   $messages .= "<li><strong>Email: </strong>" .$email."</li>";
   $messages .= "<li><strong>Subject: </strong>" .$subject."</li>";
   $messages .= "<li><strong>Message: </strong>" .$message."</li>";
   $messages .= "</ul> \r\n";

   $mail->isHTML(true);
   $mail->CharsetUTF8();
   $mail->from($help::env('MAIL_USERNAME'));
   $mail->to($help::env('MAIL_USERNAME'));
   $mail->replyTo($email,$name);
   $mail->Subject($subject);
   $mail->Body($messages);

   try {
      $mail->send();
      print json_encode(array(
         'status' => true,
         'message' => 'Thanks, your message is sent successfully.'
      ));
   } catch (\Exception $e) {
      print json_encode(array(
         'status' => false,
         'message' => $e->getMessage()
      ));
   }



}

