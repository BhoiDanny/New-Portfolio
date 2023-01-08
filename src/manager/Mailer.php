<?php /** @noinspection DuplicatedCode */
   /** //@noinspection PhpMultipleClassDeclarationsInspection */
   namespace SannyTech;

   use League\OAuth2\Client\Grant\RefreshToken;
   use League\OAuth2\Client\Provider\Google;
   use PHPMailer\PHPMailer\Exception;
   use PHPMailer\PHPMailer\OAuth;
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;


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
      private $password = MAIL_PASSWORD;
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
            $this->mail->Username = $this->username;
            $this->mail->Password = $this->password;

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

      /**
       * @param string $name
       * @return void
       */
      public function from(string $name=""): void
      {
         try {
            $this->mail->setFrom($this->email,$name);
         } catch(\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function addFrom(string $email, string $name=""): void
      {
         try {
            $this->mail->setFrom($email,$name);
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

      #close the connection
      public function close() {
         try {
            parent::smtpClose();
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
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

      public function clearAddress()
      {
         try {
            $this->mail->clearAddresses();
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function clearAttachments()
      {
         $this->mail->clearAttachments();
      }

      public function clearReplyTo()
      {
         try {
            parent::clearReplyTos();
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function clearRecipients()
      {
         try {
            parent::clearAllRecipients();
         } catch (\Exception $e) {
            $this->error = $e->getMessage() . " Mailer.php" . $e->getCode();
         }
      }

      public function __destruct() {
         parent::clearAddresses();
         parent::clearAttachments();
         parent::clearAllRecipients();
         parent::clearReplyTos();
         parent::smtpClose();
      }

      public function __toString() {
         return $this->error . " " . $this->mail->ErrorInfo;
      }




   }