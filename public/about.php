<?php
session_start();
require_once __DIR__ . "/../config/database.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Gyaanuday</title>
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

  <div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-bold text-center font-archivo mb-8">About Gyaanuday</h1>
      
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="/gyaanuday/public/images/aboutus/about.jpg" alt="About Gyaanuday" class="w-full h-64 object-cover" onerror="this.src='https://via.placeholder.com/1200x400?text=About+Gyaanuday'">
        
        <div class="p-8">
          <h2 class="text-2xl font-semibold mb-4 font-archivo">Our Mission</h2>
          <p class="text-gray-700 mb-6 leading-relaxed">
            Gyaanuday was founded with a clear mission: to unleash creativity through hands-on learning. 
            We believe that knowledge grows when shared, and creativity flourishes in collaborative environments.
            Our platform provides a space where innovators, creators, and learners can share their projects,
            gain inspiration from others, and grow together as a community.
          </p>
          
          <h2 class="text-2xl font-semibold mb-4 font-archivo">Our Story</h2>
          <p class="text-gray-700 mb-6 leading-relaxed">
            Started in 2025, Gyaanuday emerged from a simple idea: to create a platform where people could 
            showcase their creative projects and learn from one another. What began as a small community has 
            grown into a thriving ecosystem of creators spanning multiple disciplines and skill levels.
          </p>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="text-center p-4">
              <div class="rounded-full bg-[#a7d820] bg-opacity-20 w-16 h-16 flex items-center justify-center text-[#5f7b12] text-2xl mx-auto mb-4">
                <i class="fas fa-users"></i>
              </div>
              <h3 class="font-semibold text-lg mb-2">Community</h3>
              <p class="text-gray-600">A supportive network of creators and innovators from around the world.</p>
            </div>
            
            <div class="text-center p-4">
              <div class="rounded-full bg-[#a7d820] bg-opacity-20 w-16 h-16 flex items-center justify-center text-[#5f7b12] text-2xl mx-auto mb-4">
                <i class="fas fa-lightbulb"></i>
              </div>
              <h3 class="font-semibold text-lg mb-2">Creativity</h3>
              <p class="text-gray-600">A space to showcase your projects and inspire others with your ideas.</p>
            </div>
            
            <div class="text-center p-4">
              <div class="rounded-full bg-[#a7d820] bg-opacity-20 w-16 h-16 flex items-center justify-center text-[#5f7b12] text-2xl mx-auto mb-4">
                <i class="fas fa-book-open"></i>
              </div>
              <h3 class="font-semibold text-lg mb-2">Learning</h3>
              <p class="text-gray-600">Continuous growth through knowledge-sharing and collaborative experiences.</p>
            </div>
          </div>
          
          <h2 class="text-2xl font-semibold mb-4 font-archivo">Join Us</h2>
          <p class="text-gray-700 mb-6 leading-relaxed">
            Whether you're a seasoned professional or just starting out, Gyaanuday welcomes you to share your 
            creativity and learn from our community. Upload your projects, provide feedback to others, and be 
            part of a movement that celebrates the joy of creation and learning.
          </p>
          
          <div class="text-center mt-8">
            <a href="register.php" class="inline-block px-6 py-3 bg-[#a7d820] text-white rounded-lg button-hover">Join Gyaanuday Today</a>
          </div>
        </div>
      </div>
    </div>
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
