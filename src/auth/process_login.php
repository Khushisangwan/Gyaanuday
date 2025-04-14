<?php
  require_once __DIR__ . "/../../config/database.php";
  session_start();
  
  if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)|| empty($password)){
      die("Both fields are required.");
    }

    // fetch userdata from db
    $stmt = $pdo->prepare("SELECT id, username, password FROM users where email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // verify password and start session
    if ($user && password_verify($password, $user["password"])){
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user_username"] = $user["username"];
      header("location: /gyaanuday/public/index.php");
      exit;
    }else{
      die("Invalid email or password");
    }
    

  }else{
    die("Invalid request");
  }

?>