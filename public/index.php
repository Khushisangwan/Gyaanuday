<?php
session_start();
require_once __DIR__ . "/../config/database.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gyaanuday</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Archivo&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
    h1, h2, h3 {
      font-family: 'Archivo', sans-serif;
    }
    .nav-item {
      transition: all 0.3s ease;
    }
    .nav-item:hover {
      color: #A7D820;
    }
    .nav-item-active {
      color: #A7D820;
      position: relative;
    }
    .nav-item-active::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 100%;
      height: 3px;
      background-color: #A7D820;
      border-radius: 2px;
    }
    .card-hover {
      transition: all 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .button-hover {
      transition: all 0.3s ease;
    }
    .button-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    /* Search overlay styles */
    .search-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 0;
      background-color: rgba(255, 255, 255, 0.95);
      z-index: 100;
      overflow: hidden;
      transition: height 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .search-overlay.active {
      height: 100%;
    }
    .search-container {
      width: 80%;
      max-width: 600px;
      transform: translateY(-50px);
      opacity: 0;
      transition: all 0.4s ease;
    }
    .search-overlay.active .search-container {
      transform: translateY(0);
      opacity: 1;
    }
  </style>
</head>

<body class="bg-white">
  <!-- Include navigation -->
  <?php include 'navigation.php'; ?>

  <header class="relative w-full h-[70vh] bg-cover" style="background-image: url('/gyaanuday/public/images/discover/discover.jpg'); background-position: center bottom;">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-start justify-center text-left text-white pl-16">
      <h1 class="text-[32px] leading-[48px] font-archivo font-bold">Discover Projects</h1>
      <p class="mt-2 text-[16px] leading-[26px]">Unleash creativity through hands-on learning</p>
      <a href="explore_projects.php" class="mt-4 px-6 py-2 rounded text-white shadow-md button-hover" style="background-color: #A7D820;">Explore Projects</a>
    </div>
  </header>

  <section class="max-w-6xl mx-auto my-12">
    <h2 class="text-[32px] leading-[48px] font-bold text-center mb-6 text-[#171a1f] font-archivo">Popular Projects</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      require_once __DIR__ . "/../config/database.php";
      $stmt = $pdo->query("SELECT p.id, p.title, p.project_file, p.description, p.tags, u.username, p.thumbnail FROM projects p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 6");
      $projects = $stmt->fetchAll();

      foreach ($projects as $project) {
        $projectId = htmlspecialchars($project['id']);
        $fileName = htmlspecialchars($project['thumbnail']);
        $filePath = "/gyaanuday/uploads/" . $fileName;
        $description = htmlspecialchars($project['description']);
        $title = htmlspecialchars($project['title']);
        $tags = htmlspecialchars($project['tags']);

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $thumb = '';

        // Check if the file is an image
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
          // For image files, use the image itself as the thumbnail
          $thumb = $filePath;
        } else {
          // For other file types (e.g., PDF, MP4, MP3), show a default thumbnail
          $thumb = "/gyaanuday/assets/default_icon.png"; // Use your own icon or placeholder for non-image files
        }

        echo "
        <a href='project_details.php?id=$projectId' class='bg-white shadow-md rounded-lg p-4 border border-[#bdc1ca] card-hover'>
          <div class='relative h-48 overflow-hidden'>
            <img src='$thumb' class='rounded-lg w-full h-full object-cover' alt='$title'>
          </div>
          <h3 class='text-lg font-semibold mt-2 text-[#171a1f] truncate'>" . $title . "</h3>
          <p class='text-[#565d6d] text-[16px] leading-[26px] line-clamp-2 h-13 overflow-hidden'>" . $description . "</p>
          <p class='text-[#9095a1] text-[14px] mt-2 truncate'>Tags: $tags</p>
        </a>";
      }
      ?>
    </div>
  </section>

  <section class="max-w-6xl mx-auto my-12">
    <h2 class="text-[32px] leading-[48px] font-bold text-center mb-6 text-[#171a1f] font-archivo">Explore by Categories</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
      <?php
      // Define common categories with their images
      $popularCategories = [
        'Web Development' => '/gyaanuday/public/images/thumbnail/Web Development.jpg',
        'Mobile Apps' => '/gyaanuday/public/images/thumbnail/Mobile Apps.jpg',
        'Data Analysis' => '/gyaanuday/public/images/thumbnail/Data Analysis.jpg',
        'UI/UX Design' => '/gyaanuday/public/images/thumbnail/UIUX Design.png',
        'AI & ML' => '/gyaanuday/public/images/thumbnail/aiml.jpg',
        'Blockchain' => '/gyaanuday/public/images/thumbnail/Blockchian.jpg',
        'Game Development' => '/gyaanuday/public/images/thumbnail/Game Development.jpg',
        'Cybersecurity' => '/gyaanuday/public/images/thumbnail/Cybersecurity.webp',
        'IoT Projects' => '/gyaanuday/public/images/thumbnail/IoT Projects.jpg',
        'Cloud Computing' => '/gyaanuday/public/images/thumbnail/Cloud Computing.jpg'
      ];
      
      // Display categories as clickable cards
      foreach ($popularCategories as $category => $image) {
        echo '
        <a href="search_results.php?q=' . urlencode($category) . '" class="relative rounded-lg overflow-hidden shadow-md h-32 card-hover">
          <div class="absolute inset-0 w-full h-full">
            <img src="' . $image . '" class="w-full h-full object-cover" alt="' . htmlspecialchars($category) . '">
          </div>
          <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <span class="text-white text-lg font-bold text-center px-2">' . htmlspecialchars($category) . '</span>
          </div>
        </a>';
      }
      ?>
    </div>
  </section>

  <?php include 'components/footer.php'; ?>

  <script>
    // Search functionality
    const searchButton = document.getElementById('searchButton');
    const searchOverlay = document.getElementById('searchOverlay');
    const closeSearch = document.getElementById('closeSearch');
    
    // Open search overlay
    searchButton.addEventListener('click', () => {
      searchOverlay.classList.add('active');
      setTimeout(() => {
        document.querySelector('.search-container input').focus();
      }, 400);
    });
    
    // Close search overlay
    closeSearch.addEventListener('click', () => {
      searchOverlay.classList.remove('active');
    });
    
    // Close search with ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
        searchOverlay.classList.remove('active');
      }
    });
  </script>
</body>

</html>