<?php
   /*Object Instantiation*/
   use SannyTech\Session;
   use SannyTech\Cookie;
   use SannyTech\DB;
   use SannyTech\Helper;

   ob_start();

   //$db = new DB(true);
   $session = new Session();
   $message = $session->message();

   $cookie = new Cookie();

   $help = new Helper();

   $help::setTimezone("Africa/Accra");