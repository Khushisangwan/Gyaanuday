<?php
  require_once __DIR__ . "/../../config/database.php";

  if($_SERVER['REQUEST_METHOD']=="POST"){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username)|| empty($email) || empty($password)){
      die("All fields are required.");
    }

    // check email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()){
      die("Email already registered");
    }

    // hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // resgiter user in db
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (? , ? , ?)");
    
    try{
      $stmt->execute([$username, $email, $hashedPassword]);
      header("Location: /gyaanuday/public/login.php");
      exit;
    }
    catch(\PDOException $e){
      die("Registration failed: " . $e->getMessage());
    }
  }else{
    die("Invalid request");
  }
?>