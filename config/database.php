<?php 
  $host = "localhost";
  $db = "gyaanuday";
  $charset = "utf8mb4";
  $user = "root";
  $pass = "";

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];

  try{
    $pdo = new PDO($dsn, $user, $pass, $options);
  }catch(\PDOException $e){
    die("Connection failed: " . $e->getMessage());
  }
?>