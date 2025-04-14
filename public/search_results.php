<?php 
session_start(); 
require_once __DIR__ . "/../config/database.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results | Gyaanuday</title>
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
    .button-hover {
      transition: all 0.3s ease;
    }
    .button-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card-hover {
      transition: all 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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

  <div class="max-w-6xl mx-auto my-12 px-4">
    <?php
    // Check if search query exists
    if (!isset($_GET['q']) || empty($_GET['q'])) {
      echo '<div class="text-center py-8">
              <h1 class="text-[32px] leading-[48px] font-archivo font-bold text-[#171a1f] mb-4">No Search Query</h1>
              <p class="text-[#565d6d] mb-6">Please enter a search term to find projects.</p>
              <a href="projects.php" class="inline-block px-6 py-3 bg-[#A7D820] text-white rounded-lg shadow-md button-hover">
                Browse All Projects
              </a>
            </div>';
      exit;
    }

    $searchQuery = '%' . $_GET['q'] . '%';
    
    // Search in titles and tags
    $stmt = $pdo->prepare("
      SELECT p.*, u.username 
      FROM projects p 
      JOIN users u ON p.user_id = u.id 
      WHERE p.title LIKE ? OR p.tags LIKE ?
      ORDER BY p.created_at DESC
    ");
    $stmt->execute([$searchQuery, $searchQuery]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Display search results header
    $searchTerm = htmlspecialchars($_GET['q']);
    $resultCount = count($projects);
    ?>
    
    <div class="mb-8">
      <h1 class="text-[32px] leading-[48px] font-archivo font-bold text-[#171a1f]">
        Search Results for "<?php echo $searchTerm; ?>"
      </h1>
      <p class="text-[#565d6d]">
        Found <?php echo $resultCount; ?> project<?php echo $resultCount !== 1 ? 's' : ''; ?> matching your search
      </p>
    </div>

    <?php if ($resultCount > 0): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($projects as $project): 
          // Get file path
          $fileName = htmlspecialchars($project['thumbnail']);
          $filePath = "/gyaanuday/uploads/" . $fileName;
          
          $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

          if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $thumb = $filePath;
          } else {
            $thumb = "/gyaanuday/assets/default_icon.png";
          }

          $title = htmlspecialchars($project['title']);
          $description = htmlspecialchars($project['description']);
          // Truncate description for preview
          if (strlen($description) > 100) {
            $description = substr($description, 0, 100) . '...';
          }
          $username = htmlspecialchars($project['username']);
          $projectId = $project['id'];
          $tags = explode(',', $project['tags']);
        ?>
          <a href="project_details.php?id=<?php echo $projectId; ?>" class="bg-white rounded-lg shadow-md overflow-hidden card-hover border border-[#bdc1ca]">
            <img src="<?php echo $thumb; ?>" alt="<?php echo $title; ?>" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="text-lg font-semibold mb-2 text-[#171a1f]"><?php echo $title; ?></h3>
              <p class="text-[#565d6d] text-sm mb-4"><?php echo $description; ?></p>
              <div class="flex flex-wrap mb-2">
                <?php foreach ($tags as $tag): ?>
                  <?php if(trim($tag) !== ''): ?>
                    <span class="mr-2 mb-2 px-3 py-1 bg-gray-100 rounded-full text-xs text-[#565d6d]"><?php echo htmlspecialchars(trim($tag)); ?></span>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <p class="text-[#9095a1] text-xs">By <?php echo $username; ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="bg-gray-50 rounded-lg p-8 text-center">
        <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
        <h2 class="text-2xl font-archivo font-bold text-[#171a1f] mb-2">No projects found</h2>
        <p class="text-[#565d6d] mb-6">We couldn't find any projects matching your search term.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="projects.php" class="px-6 py-2 bg-white border border-[#bdc1ca] rounded-lg text-[#565d6d] shadow-sm">
            Browse All Projects
          </a>
          <button onclick="document.getElementById('searchButton').click()" class="px-6 py-2 bg-[#A7D820] text-white rounded-lg shadow-md">
            Try Another Search
          </button>
        </div>
      </div>
    <?php endif; ?>
  </div>

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
