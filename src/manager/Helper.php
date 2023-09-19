<?php

   namespace SannyTech;

   class Helper
   {
      public static function isProduction(): bool
      {
         if(self::env('APP_ENV') == 'production' || self::env('APP_ENV') == 'prod') {
            return true;
         } else {
            return false;
         }
      }

      public static function productionErrorPage($page)
      {
         return require_once $page;
      }

      public static function productionErrorLog(
         mixed $error,
         string $destination='logs/error.log',
         int $type= 3,
         string $message = "",
      ):void
      {
         $log = 'Date: [' . date('Y-m-d H:i:s') . "]" . PHP_EOL . $message . PHP_EOL . $error->getLine() . PHP_EOL . $error->getMessage() . PHP_EOL . $error->getFile() . PHP_EOL . '-------------------------' . PHP_EOL;
         error_log($log, $type, $destination);
      }

      public static function setTimeZone(string $timezone='UTC'): void
      {
         date_default_timezone_set($timezone);
      }

      public static function env($key, $default = null)
      {
         return $_ENV[$key] ?? $default;
      }
   }