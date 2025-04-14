<?php
session_start();
require_once __DIR__ . "/../config/database.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  $_SESSION['error'] = "Please log in to upload projects.";
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Project | Gyaanuday</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Archivo&display=swap');

    body {
      font-family: 'Inter', sans-serif;
    }

    h1,
    h2,
    h3 {
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

    .custom-file-input {
      position: relative;
      display: inline-block;
    }

    .custom-file-input input {
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .custom-file-label {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .gallery-preview img {
      transition: all 0.3s ease;
    }

    .gallery-preview img:hover {
      transform: scale(1.05);
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

<body class="bg-gray-50 text-gray-900">
  <!-- Search Overlay -->
  <div class="search-overlay" id="searchOverlay">
    <div class="search-container">
      <button class="absolute top-8 right-8 text-2xl text-gray-600 hover:text-gray-900" id="closeSearch">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-2xl font-archivo font-bold text-center mb-6">Search Projects</h2>
      <form action="search_results.php" method="GET" class="flex flex-col gap-4">
        <div class="relative">
          <input 
            type="text" 
            name="q" 
            placeholder="Search by title, tags..." 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#A7D820] focus:border-transparent outline-none text-lg"
            autocomplete="off"
            required
          >
          <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-[#A7D820]">
            <i class="fas fa-search fa-lg"></i>
          </button>
        </div>
        <p class="text-sm text-gray-500 text-center">Press Enter to search or ESC to close</p>
      </form>
    </div>
  </div>

  <!-- Include navigation -->
  <?php include 'navigation.php'; ?>

  <!-- Improved Upload Section -->
  <div class="container mx-auto pt-10 pb-16">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="flex flex-col md:flex-row">
        <!-- Form section -->
        <div class="md:w-2/3 p-8">
          <h2 class="text-[32px] leading-[48px] font-semibold text-[#171a1f] font-archivo mb-6">Share Your Project
          </h2>

          <form action="/gyaanuday/src/projects/add_project.php" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
              <input type="text" name="title" id="title"
                class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-[#A7D820] focus:border-transparent outline-none"
                placeholder="Enter your project title" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="project-upload" class="block text-sm font-medium text-gray-700 mb-1">Project File</label>
                <div class="mt-1 flex">
                  <div class="custom-file-input w-full">
                    <input type="file" id="project-upload" name="project_file" required class="hidden">
                    <button type="button" onclick="document.getElementById('project-upload').click()"
                      class="w-full bg-[#A7D820] text-white py-3 px-4 rounded-lg flex items-center justify-center cursor-pointer shadow-sm button-hover">
                      <i class="fas fa-upload mr-2"></i> Upload Project
                    </button>
                  </div>
                </div>
                <span id="project-file-name" class="text-xs text-gray-500 mt-1 block">No file selected</span>
              </div>

              <div>
                <label for="thumbnail-upload" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail
                  Image</label>
                <div class="mt-1 flex">
                  <div class="custom-file-input w-full">
                    <input type="file" id="thumbnail-upload" name="thumbnail" accept="image/*" required class="hidden">
                    <button type="button" onclick="document.getElementById('thumbnail-upload').click()"
                      class="w-full bg-[#A7D820] text-white py-3 px-4 rounded-lg flex items-center justify-center cursor-pointer shadow-sm button-hover">
                      <i class="fas fa-image mr-2"></i> Upload Thumbnail
                    </button>
                  </div>
                </div>
                <span id="thumbnail-file-name" class="text-xs text-gray-500 mt-1 block">No file selected</span>
              </div>
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Project Description</label>
              <textarea name="description" id="description" rows="4"
                class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:ring-2 focus:ring-[#A7D820] focus:border-transparent outline-none text-[16px] leading-[26px]"
                placeholder="Describe your project in detail..." required></textarea>
            </div>

            <div>
              <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
              <div class="flex items-center border border-gray-300 p-3 rounded-lg shadow-sm focus-within:ring-2 focus-within:ring-[#A7D820] focus-within:border-transparent">
                <div id="tags-container" class="flex flex-wrap gap-2"></div>
                <input type="text" id="tag-input" class="border-none p-0 focus:outline-none flex-grow min-w-[100px]" placeholder="Type a tag and press Enter">
                <input type="hidden" name="tags" id="tags-hidden">
              </div>
              <p class="text-xs text-gray-500 mt-1">Press Enter to add a tag</p>
            </div>

            <button type="submit"
              class="w-full md:w-auto bg-[#A7D820] text-white py-3 px-8 rounded-lg flex items-center justify-center shadow-md button-hover">
              <i class="fas fa-paper-plane mr-2"></i> Submit Project
            </button>
          </form>
        </div>

        <!-- Preview section -->
        <div class="md:w-1/3 bg-gray-50 p-8">
          <h3 class="text-lg font-semibold text-[#171a1f] mb-6">Project Inspiration</h3>
          <div class="gallery-preview grid grid-cols-2 gap-3">
            <img src="/gyaanuday/public/images/uploadthumbnail/img1.png" class="w-full h-24 object-cover rounded-lg shadow-sm">
            <img src="/gyaanuday/public/images/uploadthumbnail/img2.png" class="w-full h-24 object-cover rounded-lg shadow-sm">
            <img src="/gyaanuday/public/images/uploadthumbnail/img3.png" class="w-full h-24 object-cover rounded-lg shadow-sm">
            <img src="/gyaanuday/public/images/uploadthumbnail/img4.png" class="w-full h-24 object-cover rounded-lg shadow-sm">
            <img src="/gyaanuday/public/images/uploadthumbnail/img6.png" class="w-full h-24 object-cover rounded-lg shadow-sm">
          </div>
          <div class="mt-6">
            <p class="text-sm text-gray-600 leading-relaxed">
              Share your creative projects with the Gyaanuday community. Whether it's a design, code, or concept, your
              work could inspire others.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Featured Projects Section -->
  <div class="container mx-auto px-8 py-12 bg-white">
    <h2 class="text-[32px] leading-[48px] font-semibold text-center text-[#171a1f] font-archivo mb-8">Featured Projects
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php
      // Fetch 6 random projects from the database
      $stmt = $pdo->query("SELECT p.id, p.title, p.description, p.tags, p.thumbnail, u.username 
                          FROM projects p 
                          JOIN users u ON p.user_id = u.id 
                          ORDER BY RAND() 
                          LIMIT 6");
      $featured_projects = $stmt->fetchAll();

      foreach ($featured_projects as $project) {
        $projectId = $project['id'];
        $fileName = htmlspecialchars($project['thumbnail']);
        $filePath = "/gyaanuday/uploads/" . $fileName;
        $title = htmlspecialchars($project['title']);
        $shortDesc = htmlspecialchars(substr($project['description'], 0, 50) . (strlen($project['description']) > 50 ? '...' : ''));
        $tags = !empty($project['tags']) ? explode(',', $project['tags'])[0] : 'Project';
        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Check if the file is an image
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
          $thumb = $filePath;
        } else {
          $thumb = "/gyaanuday/assets/default_icon.png"; // Default thumbnail
        }
        
        echo '<div class="overflow-hidden rounded-lg shadow-lg border border-[#bdc1ca] card-hover">
          <a href="project_details.php?id=' . $projectId . '">
            <div class="w-full h-48 overflow-hidden">
              <img src="' . $thumb . '" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-semibold text-[#171a1f]">' . $title . '</h3>
              <p class="text-[#565d6d] text-sm">' . $shortDesc . '</p>
              <p class="text-[#9095a1] text-xs">' . $tags . '</p>
            </div>
          </a>
        </div>';
      }
      ?>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>

  <!-- JavaScript for file input display -->
  <script>
    document.getElementById('project-upload').addEventListener('change', function (e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
      document.getElementById('project-file-name').textContent = fileName;
    });

    document.getElementById('thumbnail-upload').addEventListener('change', function (e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
      document.getElementById('thumbnail-file-name').textContent = fileName;
    });

    // Tags input functionality
    document.addEventListener('DOMContentLoaded', function () {
      const tagsContainer = document.getElementById('tags-container');
      const tagInput = document.getElementById('tag-input');
      const tagsHidden = document.getElementById('tags-hidden');
      let tags = [];

      // Function to render tags
      function renderTags() {
        tagsContainer.innerHTML = '';
        tags.forEach((tag, index) => {
          const tagElement = document.createElement('div');
          tagElement.className = 'flex items-center bg-[#A7D820] bg-opacity-25 text-[#5f7b12] px-3 py-1 rounded-full text-sm';
          tagElement.innerHTML = `
            ${tag}
            <button type="button" class="ml-2 text-[#5f7b12] hover:text-[#3a4b0b] focus:outline-none" data-index="${index}">
              <i class="fas fa-times"></i>
            </button>
          `;
          tagsContainer.appendChild(tagElement);

          // Add event listener to the delete button
          tagElement.querySelector('button').addEventListener('click', function () {
            const index = parseInt(this.getAttribute('data-index'));
            tags.splice(index, 1);
            renderTags();
            updateHiddenField();
          });
        });
        
        // Update placeholder visibility based on tags existence
        if (tags.length > 0) {
          tagInput.placeholder = "";
        } else {
          tagInput.placeholder = "Type a tag and press Enter";
        }
      }

      // Function to update hidden field
      function updateHiddenField() {
        tagsHidden.value = tags.join(',');
      }

      // Event listener for tag input
      tagInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          const tag = tagInput.value.trim();

          if (tag && !tags.includes(tag)) {
            tags.push(tag);
            tagInput.value = '';
            renderTags();
            updateHiddenField();
          }
        }
      });

      // Initialize form submission to include tags
      document.querySelector('form').addEventListener('submit', function () {
        updateHiddenField();
      });
    });

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