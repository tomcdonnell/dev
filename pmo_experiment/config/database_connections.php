<?php
/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

require_once dirname(__FILE__) . '/../lib/tom/php/database/DatabaseManager.php';

error_reporting(-1);

DatabaseManager::addMany
(
   array
   (
      array
      (
         'name'     => 'pmo_doctrine',
         'host'     => 'localhost'   ,
         'user'     => 'root'        ,
         'password' => ''            ,
         'database' => 'pmo_doctrine'
      ),
      array
      (
         'name'     => 'pmo'      ,
         'host'     => 'localhost',
         'user'     => 'root'     ,
         'password' => ''         ,
         'database' => 'pmo'
      )
   )
);
