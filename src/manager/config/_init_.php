<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

   ini_set('expose_php','off');
   header("X-Powered-By: ASP.NET");

   use SannyTech\Dot;
   use SannyTech\Helper;

   $root = dirname(__DIR__ );
   $baseDir = dirname($root);
   $start = "";
   require_once($baseDir . '/vendor/autoload.php');

   try {
      $dotenv = new Dot;
      $dotenv->create($baseDir);
      $dotenv->load();
      $dotenv->require(['APP_NAME', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME','MAIL_CLIENT_ID'])->notEmpty();
      $dotenv->run();
      $start = "God is a Programmer";

      if(Helper::isProduction()){
         error_reporting(0);
         ini_set('display_errors', 0);
         set_exception_handler(function($e) {
            #show error page
            Helper::productionErrorPage('./manager/errors/error-500.php');
            Helper::productionErrorLog($e,"./manager/logs/error.log", 3);
         });
      } else {
         error_reporting(E_ALL);
         ini_set('display_errors', 1);
      }
   } catch(Exception $e) {
      die($e->getMessage() . ' Please check your .env file');
   }

   if($start != "God is a Programmer") {
      echo "Contact Developer";
      return;
   } else {
      require_once("start_app.php");
   }