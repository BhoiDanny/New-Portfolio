<?php /** @noinspection DuplicatedCode */
   /** //@noinspection PhpMultipleClassDeclarationsInspection */
   namespace SannyTech;

   use SannyTech\Helper as help;

   use PHPMailer\PHPMailer\PHPMailer;

   use PHPMailer\PHPMailer\SMTP;

   use PHPMailer\PHPMailer\OAuth;

   use PHPMailer\PHPMailer\Exception;

   use League\OAuth2\Client\Provider\Google;

   use League\OAuth2\Client\Grant\RefreshToken;


   class Mailer extends PHPMailer
   {

      private static $PHPMailer    = PHPMailer::class;
      private static $SMTP         = SMTP::class;
      private static $OAuth        = OAuth::class;
      private static $Exception    = Exception::class;
      private static $Google       = Google::class;
      private static $RefreshToken = RefreshToken::class;


      private $mail;
      public $error;
      private $host = MAIL_HOST;
      private $port = MAIL_PORT;
      private $secure;
      private $auth = MAIL_AUTH;
      public $email = MAIL_EMAIL;
      private $username = MAIL_USERNAME;
      private $clientId = MAIL_CLIENT_ID;
      private $clientSecret = MAIL_CLIENT_SECRET;
      private $refreshToken = MAIL_REFRESH_TOKEN;



      public function __construct($bool=false)
      {
         try {

            parent::__construct();

            //require(VENDOR_PATH.DS.'autoload.php');

            $this->mail = new static::$PHPMailer($bool);
            $this->mail->isSMTP();
            $this->mail->Host = $this->host;
            $this->mail->Port = $this->port;
            $this->mail->SMTPSecure = $this->mail::ENCRYPTION_SMTPS;
            $this->mail->SMTPAuth = true;

            if(\SannyTech\Helper::env('MAIL_GMAIL_AUTH')){
               //#
               $this->mail->AuthType = $this->auth;
               $provider = new static::$Google(
                  [
                     'clientId' => $this->clientId,
                     'clientSecret' => $this->clientSecret,
                  ]
               );

               if(\SannyTech\Helper::env('MAIL_REFRESH_TOKEN_STAT')) {
                  try {
                     $grant = new static::$RefreshToken();
                     $token = $provider->getAccessToken(
                        $grant, [
                           'refresh_token' => $this->refreshToken,
                        ]
                     );
                  } catch (\Exception $ex) {
                     $this->error = "Check Internet Connection";
                     return;
                  }
               }

               $this->mail->setOAuth(
                  new OAuth(
                     [
                        'provider' => $provider,
                        'clientId' => $this->clientId,
                        'clientSecret' => $this->clientSecret,
                        'refreshToken' => $this->refreshToken,
//                        'accessToken' => $token->getToken(),
//                        'redirectUri' => 'http://localhost:3002/inc/get_oauth_token.php',
                        'userName' => $this->username,
                     ]
                  )
               );

            }


         } catch(Exception $e){
            $this->error = "Working Here" . $e->getMessage() . " " . $e->getCode();
         }
      }

      public function mailer()
      {
         return $this->mail;
      }

      public function from($name=""){
         try {
            $this->mail->setFrom($this->email,$name);
         } catch(\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function to($to,$name="") {
         try {
            $this->mail->addAddress($to,$name);
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function replyTo($email,$name="") {
         try {
            $this->mail->addReplyTo($email,$name);
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function Subject($subject) {
         try {
            $this->mail->Subject = $subject;
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function Body($body) {
         try {
            $this->mail->Body = $body;
            $this->mail->AltBody = $body;
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function Attach($file) {
         try {
            $this->mail->addAttachment($file);
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function send(): bool
      {
         try {
            return $this->mail->send();
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
            return false;
         }
      }

      public function CharsetUTF8() {
         try {
            $this->mail->CharSet = $this->mail::CHARSET_UTF8;
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function isHTML($isHtml=false) {
         try {
            $this->mail->isHTML($isHtml);
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function Error(): string
      {
         return $this->error;
      }

      public function sendMail($to, $subject,$message,$from=""){
         $this->from($from);
         $this->to($to);
         $this->Subject($subject);
         $this->CharsetUTF8();
         $this->isHTML(true);
         $this->Body($message);
      }

      public function __destruct() {
         $this->mail->clearAddresses();
         $this->mail->clearAttachments();
         $this->mail->clearAllRecipients();
      }

      public function __toString() {
         return $this->error;
      }




   }