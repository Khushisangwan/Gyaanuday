<?php
// At the very beginning of the file, add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/../config/database.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Details | Gyaanuday</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Archivo&display=swap');
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
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
    .tag {
      background-color: #f3f4f6;
      padding: 8px 16px;
      border-radius: 30px;
      font-size: 0.875rem;
      color: #565d6d;
      margin-right: 8px;
      display: inline-block;
      margin-bottom: 10px;
      transition: all 0.2s ease;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      border: 1px solid #e5e7eb;
    }
    .tag:hover {
      background-color: #A7D820;
      color: white;
      border-color: #A7D820;
    }
    /* Like button styles - Updated for better visibility */
    .like-btn, .bookmark-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 8px 16px;
      background-color: white;
      border: 2px solid #e5e7eb;
      border-radius: 50px;
      font-size: 1.1rem;
      color: #565d6d;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      position: relative;
      top: auto;
      right: auto;
      width: auto;
      height: auto;
    }
    .like-btn:hover, .bookmark-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }
    .like-btn.liked {
      color: white;
      background-color: #ef4444;
      border-color: #ef4444;
    }
    .bookmark-btn.bookmarked {
      color: white;
      background-color: #3b82f6;
      border-color: #3b82f6;
    }
    .like-count, .bookmark-count {
      position: relative;
      top: auto;
      right: auto;
      background: none;
      color: inherit;
      border-radius: 0;
      min-width: auto;
      height: auto;
      font-size: 1rem;
      font-weight: bold;
      padding: 0;
    }
    .like-btn.liked .like-count, .bookmark-btn.bookmarked .bookmark-count {
      color: white;
    }
    .card {
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    .card:hover {
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
      transform: translateY(-3px);
    }
    .project-header {
      position: relative;
    }
    .project-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100px;
      height: 5px;
      background-color: #A7D820;
      border-radius: 3px;
    }
    .project-meta-item {
      display: flex;
      align-items: center;
      margin-right: 20px;
      color: #6b7280;
    }
    .project-meta-item i {
      margin-right: 6px;
      color: #A7D820;
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
    /* Profile photo styles */
    .profile-img {
      border: 2px solid #A7D820;
      transition: all 0.3s ease;
    }
   
    .profile-initial {
      border: 2px solid #95c41f;
      transition: all 0.3s ease;
    }
    .profile-initial:hover {
      transform: scale(1.05);
      box-shadow: 0 0 10px rgba(167, 216, 32, 0.3);
    }
  </style>
</head>

<body>
  <!-- Include navigation -->
  <?php include 'navigation.php'; ?>

  <?php
  // Get project ID from URL parameter
  if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: projects.php");
    exit;
  }

  $projectId = intval($_GET['id']);
  
  // Connect to database and fetch project details
  
  try {
    // Fetch project details
    $stmt = $pdo->prepare("SELECT p.*, u.username FROM projects p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->execute([$projectId]);
    $project = $stmt->fetch();
    
    if (!$project) {
      echo '<div class="max-w-6xl mx-auto my-12 px-4">
              <div class="bg-red-100 p-6 rounded-lg">
                <h1 class="text-2xl font-bold text-red-700">Project not found</h1>
                <p class="mt-2">The project you are looking for does not exist.</p>
                <a href="projects.php" class="mt-4 inline-block px-6 py-2 text-white rounded" style="background-color: #A7D820;">
                  Browse Projects
                </a>
              </div>
            </div>';
      exit;
    }
  
    // Prepare data for display
    $title = htmlspecialchars($project['title']);
    $description = htmlspecialchars($project['description']);
    $username = htmlspecialchars($project['username']);
    $created = date('F j, Y', strtotime($project['created_at']));
    $tags = $project['tags'] ? explode(',', $project['tags']) : [];
  
    // Get file path
    $fileName = htmlspecialchars($project['thumbnail'] ?? '');
    $filePath = "/gyaanuday/uploads/" . $fileName;
    $projectFile = "/gyaanuday/uploads/" . htmlspecialchars($project['project_file'] ?? '');
    
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $projectExt = strtolower(pathinfo($project['project_file'] ?? '', PATHINFO_EXTENSION));
  
    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
      $thumb = $filePath;
    } else {
      $thumb = "/gyaanuday/assets/default_icon.png";
    }
  
    // Get like count and check if user has liked this project
    $likeCount = 0;
    $hasLiked = false;
    $hasBookmarked = false;
  
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE project_id = ?");
    $stmt->execute([$projectId]);
    $likeCount = $stmt->fetchColumn();
  
    if (isset($_SESSION['user_id'])) {
      $stmt = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND project_id = ?");
      $stmt->execute([$_SESSION['user_id'], $projectId]);
      $hasLiked = $stmt->fetch() ? true : false;
      
      // Check if user has bookmarked this project
      $stmt = $pdo->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND project_id = ?");
      $stmt->execute([$_SESSION['user_id'], $projectId]);
      $hasBookmarked = $stmt->fetch() ? true : false;
    }
  
    // Fetch project comments - now including profile_photo
    $comments = [];
    $stmt = $pdo->prepare("
      SELECT c.*, u.username, u.profile_photo
      FROM comments c 
      JOIN users u ON c.user_id = u.id 
      WHERE c.project_id = ? 
      ORDER BY c.created_at DESC
    ");
    $stmt->execute([$projectId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (Exception $e) {
    echo '<div class="max-w-6xl mx-auto my-12 px-4">
            <div class="bg-red-100 p-6 rounded-lg">
              <h1 class="text-2xl font-bold text-red-700">Error</h1>
              <p class="mt-2">An error occurred while retrieving project data.</p>
              <p class="text-sm text-red-700">' . $e->getMessage() . '</p>
            </div>
          </div>';
    exit;
  }
  ?>

  <div class="max-w-6xl mx-auto my-12 px-4">
    <!-- Project Header -->
    <div class="flex flex-wrap justify-between items-start mb-12 project-header pb-5">
      <div class="w-full md:w-3/4 mb-4 md:mb-0">
        <div class="flex items-center flex-wrap">
          <h1 class="text-[36px] leading-[48px] font-archivo font-bold text-[#171a1f] mb-3 mr-4"><?php echo $title; ?></h1>
          <!-- Like and Bookmark Buttons beside title -->
          <div class="flex gap-2 items-center my-3">
            <button id="likeButton" class="like-btn <?php echo $hasLiked ? 'liked' : ''; ?>" data-project-id="<?php echo $projectId; ?>">
              <i class="<?php echo $hasLiked ? 'fas' : 'far'; ?> fa-heart"></i>
              <span class="like-count ml-1"><?php echo $likeCount; ?> <?php echo $likeCount == 1 ? 'like' : 'likes'; ?></span>
            </button>
            <button id="bookmarkButton" class="bookmark-btn <?php echo $hasBookmarked ? 'bookmarked' : ''; ?>" data-project-id="<?php echo $projectId; ?>">
              <i class="<?php echo $hasBookmarked ? 'fas' : 'far'; ?> fa-bookmark"></i>
              <span class="bookmark-count ml-1"><?php echo $hasBookmarked ? 'Saved' : 'Save'; ?></span>
            </button>
          </div>
        </div>
        <div class="flex flex-wrap items-center text-[#565d6d] mb-4">
          <div class="project-meta-item">
            <i class="fas fa-user"></i>
            <span><?php echo $username; ?></span>
          </div>
          <div class="project-meta-item">
            <i class="fas fa-calendar"></i>
            <span><?php echo $created; ?></span>
          </div>
          <div class="project-meta-item">
            <i class="fas fa-file"></i>
            <span class="uppercase"><?php echo $projectExt; ?></span>
          </div>
        </div>
      </div>
      <div class="w-full md:w-1/4 flex justify-center md:justify-end">
        <a href="<?php echo $projectFile; ?>" download class="px-6 py-3 rounded-full text-white button-hover shadow-md inline-block bg-[#A7D820]">
          <i class="fas fa-download mr-2"></i> Download
        </a>
      </div>
    </div>

    <!-- Project Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Column - Thumbnail and Details -->
      <div class="lg:col-span-2">
        <div class="rounded-lg overflow-hidden shadow-md mb-6 card">
          <img src="<?php echo $thumb; ?>" alt="<?php echo $title; ?>" class="w-full h-auto max-h-96 object-cover">
        </div>
        
        <div class="bg-white rounded-lg p-6 border border-[#e5e7eb] card relative">
          <!-- Remove the like button from here -->
          
          <h2 class="text-2xl font-bold mb-4 text-[#171a1f] font-archivo">Description</h2>
          <p class="text-[#565d6d] text-[16px] leading-[26px] mb-6"><?php echo nl2br($description); ?></p>
          
          <!-- Project Content Preview Based on File Type -->
          <?php if (in_array($projectExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
            <h2 class="text-xl font-semibold mt-8 mb-4 text-[#171a1f] font-archivo">Project Preview</h2>
            <img src="<?php echo $projectFile; ?>" alt="Project Preview" class="w-full h-auto rounded-lg">
          <?php elseif ($projectExt === 'pdf'): ?>
            <h2 class="text-xl font-semibold mt-8 mb-4 text-[#171a1f] font-archivo">PDF Preview</h2>
            <div class="rounded-lg overflow-hidden border border-gray-300">
              <object data="<?php echo $projectFile; ?>" type="application/pdf" width="100%" height="500px">
                <p>Unable to display PDF. <a href="<?php echo $projectFile; ?>" download>Download</a> instead.</p>
              </object>
            </div>
          <?php elseif (in_array($projectExt, ['mp4', 'webm', 'ogg'])): ?>
            <h2 class="text-xl font-semibold mt-8 mb-4 text-[#171a1f] font-archivo">Video Preview</h2>
            <video controls class="w-full rounded-lg">
              <source src="<?php echo $projectFile; ?>" type="video/<?php echo $projectExt; ?>">
              Your browser does not support the video tag.
            </video>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Right Column - Additional Info -->
      <div>
        <!-- Project Info Card -->
        <div class="bg-white rounded-lg p-6 border border-[#e5e7eb] mb-6 card">
          <h2 class="text-xl font-semibold mb-4 text-[#171a1f] font-archivo border-b pb-3">Project Details</h2>
          <div class="space-y-5 mt-4">
            <div class="flex items-center">
              <div class="bg-gray-100 rounded-full p-3 mr-3">
                <i class="fas fa-user text-[#A7D820]"></i>
              </div>
              <div>
                <h3 class="text-sm font-medium text-[#9095a1]">CREATED BY</h3>
                <p class="text-[#171a1f] font-medium"><?php echo $username; ?></p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="bg-gray-100 rounded-full p-3 mr-3">
                <i class="fas fa-calendar text-[#A7D820]"></i>
              </div>
              <div>
                <h3 class="text-sm font-medium text-[#9095a1]">DATE UPLOADED</h3>
                <p class="text-[#171a1f] font-medium"><?php echo $created; ?></p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="bg-gray-100 rounded-full p-3 mr-3">
                <i class="fas fa-file text-[#A7D820]"></i>
              </div>
              <div>
                <h3 class="text-sm font-medium text-[#9095a1]">FILE TYPE</h3>
                <p class="text-[#171a1f] font-medium uppercase"><?php echo $projectExt; ?></p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tags Card -->
        <div class="bg-white rounded-lg p-6 border border-[#e5e7eb] card">
          <h2 class="text-xl font-semibold mb-4 text-[#171a1f] font-archivo border-b pb-3">Tags</h2>
          <div class="mt-4">
            <?php foreach ($tags as $tag): ?>
              <?php if(trim($tag) !== ''): ?>
                <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Related Projects - Could be implemented in the future -->
      </div>
    </div>

    <!-- Comment Section -->
    <div class="mt-12">
      <h2 class="text-2xl font-bold mb-6 text-[#171a1f] font-archivo border-b pb-3">Comments</h2>
      
      <!-- Comment Form -->
      <div class="bg-white rounded-lg p-6 border border-[#e5e7eb] card mb-6">
        <?php if (isset($_SESSION['user_id'])): ?>
          <form id="commentForm" class="space-y-4">
            <input type="hidden" name="project_id" value="<?php echo $projectId; ?>">
            <div>
              <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Add a comment</label>
              <textarea 
                id="comment" 
                name="comment" 
                rows="3" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#A7D820] focus:border-[#A7D820]"
                placeholder="Share your thoughts on this project..."
                required
              ></textarea>
            </div>
            <div class="flex justify-end">
              <button 
                type="submit" 
                class="px-4 py-2 bg-[#A7D820] text-white rounded-md hover:bg-[#95c41f] focus:outline-none focus:ring-2 focus:ring-[#A7D820] focus:ring-offset-2 transition-all button-hover"
              >
                Post Comment
              </button>
            </div>
          </form>
        <?php else: ?>
          <div class="bg-gray-50 p-4 rounded-md text-center">
            <p class="text-gray-600 mb-2">Please <a href="login.php" class="text-[#A7D820] font-bold">login</a> to leave a comment.</p>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Comments List -->
     <!-- Comments List -->
<div class="space-y-4" id="commentsContainer">
  <?php if (count($comments) > 0): ?>
    <?php foreach ($comments as $comment): ?>
      <div class="bg-white rounded-lg p-6 border border-[#e5e7eb] card">
        <div class="flex items-start space-x-4">
          <div class="flex-shrink-0">
            <?php if (!empty($comment['profile_photo'])): ?>
              <img class="h-12 w-12 rounded-full object-cover profile-img" 
                   src="/gyaanuday/uploads/profile_photos/<?php echo htmlspecialchars($comment['profile_photo']); ?>" 
                   alt="<?php echo htmlspecialchars($comment['username']); ?>">
            <?php else: ?>
              <div class="h-12 w-12 rounded-full bg-[#A7D820] flex items-center justify-center text-white font-bold text-lg profile-initial">
                <?php echo strtoupper(substr(htmlspecialchars($comment['username']), 0, 1)); ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex-grow">
            <div class="flex justify-between items-center mb-1">
              <h3 class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($comment['username']); ?></h3>
              <p class="text-xs text-gray-500"><?php echo date('M j, Y \a\t g:i a', strtotime($comment['created_at'])); ?></p>
            </div>
            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-sm text-gray-500">No comments yet.</p>
  <?php endif; ?>
</div>


  </div>

  <?php include 'components/footer.php'; ?>

  <!-- Add this script before closing body tag -->
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

    // Like functionality
    document.addEventListener('DOMContentLoaded', function() {
      const likeButton = document.getElementById('likeButton');
      const bookmarkButton = document.getElementById('bookmarkButton');
      
      if (likeButton) {
        likeButton.addEventListener('click', function() {
          const projectId = this.getAttribute('data-project-id');
          
          // Check if user is logged in
          <?php if (!isset($_SESSION['user_id'])): ?>
            alert('Please login to like this project');
            return;
          <?php endif; ?>
          
          // Send AJAX request to like/unlike
          const formData = new FormData();
          formData.append('project_id', projectId);
          
          fetch('/gyaanuday/src/projects/like_project.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            // Update like button appearance and count
            const likeButton = document.getElementById('likeButton');
            const likeCount = document.querySelector('.like-count');
            
            if (data.status === 'liked') {
              likeButton.classList.add('liked');
              likeButton.querySelector('i').classList.replace('far', 'fas');
            } else {
              likeButton.classList.remove('liked');
              likeButton.querySelector('i').classList.replace('fas', 'far');
            }
            
            // Update count with proper singular/plural form
            const count = data.count;
            likeCount.textContent = `${count} ${count == 1 ? 'like' : 'likes'}`;
          })
          .catch(error => {
            console.error('Error:', error);
          });
        });
      }
      
      // Bookmark functionality
      if (bookmarkButton) {
        bookmarkButton.addEventListener('click', function() {
          const projectId = this.getAttribute('data-project-id');
          
          // Check if user is logged in
          <?php if (!isset($_SESSION['user_id'])): ?>
            alert('Please login to bookmark this project');
            return;
          <?php endif; ?>
          
          // Send AJAX request to bookmark/unbookmark
          const formData = new FormData();
          formData.append('project_id', projectId);
          
          fetch('/gyaanuday/src/projects/bookmark_project.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            // Update bookmark button appearance
            const bookmarkButton = document.getElementById('bookmarkButton');
            const bookmarkCount = document.querySelector('.bookmark-count');
            
            if (data.status === 'bookmarked') {
              bookmarkButton.classList.add('bookmarked');
              bookmarkButton.querySelector('i').classList.replace('far', 'fas');
              bookmarkCount.textContent = 'Saved';
            } else {
              bookmarkButton.classList.remove('bookmarked');
              bookmarkButton.querySelector('i').classList.replace('fas', 'far');
              bookmarkCount.textContent = 'Save';
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
        });
      }
    });

    // Comment functionality
    document.addEventListener('DOMContentLoaded', function() {
      const commentForm = document.getElementById('commentForm');
      
      if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const comment = document.getElementById('comment').value;
          const projectId = <?php echo $projectId; ?>;
          
          if (!comment.trim()) {
            alert('Please enter a comment');
            return;
          }
          
          // Send AJAX request to add comment
          const formData = new FormData();
          formData.append('project_id', projectId);
          formData.append('comment', comment);
          
          fetch('/gyaanuday/src/projects/add_comment.php', {
            method: 'POST',
            body: formData
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Clear form
              document.getElementById('comment').value = '';
              
              // Create new comment HTML with profile photo or initial
              const newComment = document.createElement('div');
              newComment.className = 'bg-white rounded-lg p-6 border border-[#e5e7eb] card';
              
              const date = new Date(data.created_at);
              const formattedDate = date.toLocaleString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric', 
                hour12: true 
              });
              
              // Profile image handling with border
              let profileHtml;
              if (data.profile_photo) {
                profileHtml = `<img class="h-12 w-12 rounded-full object-cover profile-img" src="/gyaanuday/uploads/profiles/${data.profile_photo}" alt="${data.username}">`;
              } else {
                const initial = data.username.charAt(0).toUpperCase();
                profileHtml = `<div class="h-12 w-12 rounded-full bg-[#A7D820] flex items-center justify-center text-white font-bold text-lg profile-initial">${initial}</div>`;
              }
              
              newComment.innerHTML = `
                <div class="flex items-start space-x-4">
                  <div class="flex-shrink-0">
                    ${profileHtml}
                  </div>
                  <div class="flex-grow">
                    <div class="flex justify-between items-center mb-1">
                      <h3 class="text-sm font-medium text-gray-900">${data.username}</h3>
                      <p class="text-xs text-gray-500">${formattedDate}</p>
                    </div>
                    <p class="text-gray-700">${data.comment.replace(/\n/g, '<br>')}</p>
                  </div>
                </div>
              `;
              
              // Add new comment to the top of the list
              const commentsContainer = document.getElementById('commentsContainer');
              
              // Remove "no comments" message if it exists
              const noComments = document.getElementById('noComments');
              if (noComments) {
                noComments.remove();
              }
              
              commentsContainer.insertBefore(newComment, commentsContainer.firstChild);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Failed to post comment. Please try again.');
          });
        });
      }
    });
  </script>
  
  <script>
    // Debug output to console
    console.log("Page loaded successfully");
    document.addEventListener('DOMContentLoaded', function() {
      console.log("DOM fully loaded");
      console.log("Project ID: <?php echo $projectId; ?>");
      console.log("Title: <?php echo addslashes($title); ?>");
    });
  </script>
</body>
</html>
