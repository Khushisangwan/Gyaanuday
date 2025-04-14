<?php
  require_once __DIR__ . "/../../config/database.php";
  session_start();

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
      die("You need to log in first.");
    }

    $userId = $_SESSION['user_id'];
    $title = trim($_POST['title']); // Get the title
    $description = trim($_POST['description']);
    $tags = trim($_POST['tags']);

    if (empty($title) || empty($description) || empty($tags) || empty($_FILES['project_file']['name'])) {
      die("All fields are required.");
    }

    // Handle project file upload
    $targetDir = __DIR__ . "/../../uploads/";
    $fileName = basename($_FILES['project_file']['name']);
    $targetFile = $targetDir . $fileName;

    // Validate project file type (allow images, pdfs, etc.)
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'png', 'jpeg', 'pdf', 'mp4', 'mp3'];
    if (!in_array($fileType, $allowedTypes)) {
      die("Only JPG, PNG, JPEG, PDF, MP4, MP3 files are allowed for the project file.");
    }

    // Move the project file to the target directory
    if (!move_uploaded_file($_FILES['project_file']['tmp_name'], $targetFile)) {
      die("Sorry, there was an error uploading your project file.");
    }

    // Handle thumbnail upload (optional)
    $thumbnailFileName = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
      // Validate thumbnail file type (allow only image files)
      $thumbnailFileType = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
      $allowedThumbnailTypes = ['jpg', 'jpeg', 'png', 'gif'];

      if (in_array($thumbnailFileType, $allowedThumbnailTypes)) {
        // Generate a unique name for the thumbnail
        $thumbnailFileName = uniqid('thumb_', true) . '.' . $thumbnailFileType;
        $thumbnailTargetPath = $targetDir . $thumbnailFileName;

        // Move the thumbnail to the target directory
        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailTargetPath)) {
          die("Sorry, there was an error uploading the thumbnail.");
        }
      } else {
        die("Only JPG, PNG, GIF files are allowed for the thumbnail.");
      }
    }

    // Insert project into the database
    $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, project_file, description, tags, thumbnail) VALUES (?, ?, ?, ?, ?, ?)");
    try {
      $stmt->execute([$userId, $title, $fileName, $description, $tags, $thumbnailFileName]);
      header("Location: /gyaanuday/public/index.php");
      exit;
    } catch (\PDOException $e) {
      die("Project upload failed: " . $e->getMessage());
    }
  } else {
    die("Invalid request");
  }
?>