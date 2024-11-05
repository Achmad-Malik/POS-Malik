<?php

include __DIR__ . '/config.php';

function render($view)
{
     $include = @include "pages/$view.php";
     if (!$include) {
          @include "pages/$view/index.php";
     }
}

function middleware()
{
     $view = @$_GET['view'] ?: 'login';

     // $isLogin  = false;
     // if ($isLogin && $view == 'login') {
     //      header('Location: dashboard');
     // }
     // if (!$isLogin && $view != 'login') {
     //      header('Location: login');
     // }
     return $view;
}

function url($url)
{
     return BASE_URL . $url;
}

function action($url)
{
     return url("/actions$url.php");
}

function redirect($path){
     $url = url($path);
     header("Location: $url");
}


function connection()
{
     try {
          $serverAddress = DB_HOST;
          $databaseName = DB_DATABASE;
          $username = DB_USERNAME;
          $password = DB_PASSWORD;
          $database = new PDO(
               "mysql:host={$serverAddress};dbname={$databaseName}",
               $username,
               $password,
          );
          define('db', $database);
     } catch (\Exception $e) {
          die("Gagal koneksi => " . $e->getMessage());
     }
}

connection();