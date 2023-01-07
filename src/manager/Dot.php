<?php

   namespace SannyTech;

   use Dotenv\Dotenv;
   use Exception;

   /*Dot Exception*/
   class DotException extends Exception {}

   class Dot
   {
      private mixed $env;


      public function create($env): void
      {
         $this->env = Dotenv::createImmutable($env);
      }

      public function load(): void
      {
         $this->env->load();
      }

      /**
       * Items Required to Start
       * @params $items
      */
      public function require($items)
      {
         return $this->env->required($items);
      }

      /**
       *Load Env Variables into Project
       */
      public function run(): void
      {
         global $_ENV;
         foreach ($_ENV as $key => $value) {
            define(strtoupper($key), $value);
         }
      }

   }