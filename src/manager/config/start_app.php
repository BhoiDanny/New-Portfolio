<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

   /*Object Instantiation*/
   use SannyTech\Helper;
   use SannyTech\Mailer;

   ob_start();

   $help = new Helper();
   $mail = new Mailer(true);

   $help::setTimezone("Africa/Accra");