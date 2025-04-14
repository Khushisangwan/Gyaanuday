<?php
// Start session and include database at the very beginning
session_start();
require_once __DIR__ . "/../config/database.php";

// Initialize photo_url with a better default value
$photo_url = "/gyaanuday/public/images/default_profile.jpeg";  // Changed default avatar pathth

// Get user data if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Improved query to get user data
    $stmt = $pdo->prepare("SELECT id, username, email, bio, profile_photo FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Store user data properly
        $username = htmlspecialchars($user['username']);
        $email = htmlspecialchars($user['email']);
        // Check if bio is null or empty and provide a default value
        $bio = !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'No bio available';
        $profile_photo = $user['profile_photo'] ?? null;
        
        // Use consistent path handling with the upload path in update_profile.php
        if (!empty($profile_photo)) {
            $photo_path = __DIR__ . "/../uploads/profile_photos/" . $profile_photo;
            
            // Use relative path for browser display
            if (file_exists($photo_path)) {
                $photo_url = "../uploads/profile_photos/" . $profile_photo;
            }
        }
    }
} else {
    // If not logged in, redirect to login
    header("Location: login.php");
    exit;
}

// Debug information - disable in production
$debug = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Gyaanuday</title>
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

    <!-- Show success/error messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 max-w-4xl mx-auto mt-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 max-w-4xl mx-auto mt-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if($debug): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4 max-w-4xl mx-auto mt-4">
            <p>Debug Info:</p>
            <p>Profile photo in DB: <?= htmlspecialchars($profile_photo ?? '') ?></p>
            <p>Photo path (server): <?= htmlspecialchars($photo_path ?? '') ?></p>
            <p>Photo URL (browser): <?= htmlspecialchars($photo_url) ?></p>
            <p>Photo exists: <?= isset($photo_path) && file_exists($photo_path) ? 'Yes' : 'No' ?></p>
            <p>Username: <?= $username ?></p>
            <p>Bio: <?= $bio ?></p>
        </div>
    <?php endif; ?>

    <!-- User Profile Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white border border-[#bdc1ca] p-8 rounded-lg mb-8">
            <!-- User info container with avatar and bio -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
                <div class="flex-shrink-0">
                    <img src="<?= $photo_url ?>" alt="User avatar" class="w-32 h-32 rounded-lg object-cover shadow-md">
                </div>
                
                <div class="flex flex-col gap-4 flex-grow text-center md:text-left">
                    <h1 class="text-[32px] leading-[48px] font-archivo text-[#171a1f]"><?= $username ?></h1>
                    <p class="text-base leading-[26px] text-[#565d6d] font-inter"><?= $bio ?></p>
                </div>
            </div>
            
            <!-- Toggle for edit profile form -->
            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                <button id="toggleEditForm" class="w-[150px] h-[44px] bg-[#a7d820] rounded-lg text-[#3a4b0b] font-inter text-base hover:bg-[#96c01c] transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
                <a href="/gyaanuday/src/auth/logout.php" class="w-[150px] h-[44px] bg-white border border-gray-300 rounded-lg text-[#565d6d] font-inter text-base hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <button id="deleteAccountBtn" class="w-[200px] h-[44px] bg-white border border-red-500 rounded-lg text-red-500 font-inter text-base hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-user-slash"></i> Delete Account
                </button>
            </div>
            
            <!-- Edit Profile Form (hidden by default) -->
            <div id="editProfileForm" class="mt-8 hidden">
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-2xl mb-6 font-archivo text-[#171a1f]">Update Your Profile</h2>
                    
                    <form action="../src/auth/update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Username readonly field - showing the username but not allowing edits -->
                        <div>
                            <label for="username-display" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input 
                                type="text" 
                                id="username-display" 
                                value="<?= $username ?>" 
                                class="w-full p-3 border border-gray-200 rounded-md shadow-sm bg-gray-50 text-gray-600" 
                                readonly
                            >
                            <p class="mt-1 text-xs text-gray-500">Username cannot be changed.</p>
                        </div>
                        
                        <!-- Profile Picture Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                            <div class="flex flex-col items-center md:items-start">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Photo</label>
                                <div class="w-24 h-24">
                                    <img src="<?= $photo_url ?>" class="w-full h-full rounded-lg object-cover border border-gray-200">
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Photo</label>
                                <div class="mt-1 flex items-center">
                                    <label class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                        <span>Choose file</span>
                                        <input type="file" name="profile_photo" accept="image/*" class="sr-only" id="profile_photo_input">
                                    </label>
                                    <span class="ml-3 text-sm text-gray-500" id="file_name_display">No file selected</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Recommended size: 400x400 pixels. JPG, PNG only.</p>
                            </div>
                        </div>
                        
                        <!-- Bio Section -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea 
                                name="bio" 
                                id="bio" 
                                rows="4" 
                                class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-[#a7d820] focus:border-[#a7d820] focus:outline-none transition" 
                                placeholder="Tell us about yourself..."
                            ><?= $bio === 'No bio available' ? '' : $bio ?></textarea>
                            <p class="mt-1 text-xs text-gray-500">Brief description that will appear on your profile.</p>
                        </div>
                        
                        <!-- Hidden username field to keep username unchanged -->
                        <input type="hidden" name="original_username" value="<?= $username ?>">
                        
                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <button type="button" id="cancelEdit" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a7d820]">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-[#3a4b0b] bg-[#a7d820] hover:bg-[#96c01c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a7d820]">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Uploaded Projects Section -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-center text-4xl font-normal text-[#171a1f] mb-12 font-['Archivo']">Uploaded Projects</h1>
        
        <?php
        // Fetch user's projects from the database
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($projects) > 0) {
            echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';
            
            foreach ($projects as $project) {
                // Set default image if none available
                $projectImage = !empty($project['thumbnail']) ? "/gyaanuday/uploads/". htmlspecialchars($project['thumbnail']) : "https://dashboard.codeparrot.ai/api/image/Z90sbsNZNkcbc4lS/image-20.png";
                
                echo '<div class="bg-white rounded-md border border-[#bdc1ca] p-4">
                    <div class="cursor-pointer project-card" data-project-id="' . $project['id'] . '">
                        <img src="' . $projectImage . '" alt="' . htmlspecialchars($project['title']) . '" class="w-full h-40 object-cover rounded-lg mb-4">
                        <h2 class="text-lg font-bold text-[#171a1f] mb-2">' . htmlspecialchars($project['title']) . '</h2>
                        <p class="text-[#9095a1] text-sm mb-2">' . htmlspecialchars($project['short_description'] ?? '') . '</p>
                        <p class="text-[#9095a1] text-sm mb-4">' . htmlspecialchars($project['description']) . '</p>
                    </div>
                    <div class="flex justify-end">
                        <button class="border border-[#d32f2f] text-[#d32f2f] py-2 px-4 rounded-md hover:bg-[#d32f2f] hover:text-white transition-colors delete-project" data-project-id="' . $project['id'] . '">Delete</button>
                    </div>
                </div>';
            }
            
            echo '</div>';
        } else {
            echo '<div class="text-center py-8 text-gray-500">
                <p>You haven\'t uploaded any projects yet.</p>
            </div>';
        }
        ?>
    </div>

    <!-- Bookmarked Projects Section -->
    <div class="container mx-auto px-4 py-8" id="bookmarkedProjectsSection">
        <h1 class="text-center text-[32px] text-[#171a1f] font-archivo mb-12 leading-[48px]">Bookmarked Projects</h1>
        
        <?php
        // Fetch user's bookmarked projects
        $stmt = $pdo->prepare("
            SELECT p.*, u.username, b.id as bookmark_id
            FROM bookmarks b
            JOIN projects p ON b.project_id = p.id
            JOIN users u ON p.user_id = u.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$user_id]);
        $bookmarked_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($bookmarked_projects) > 0) {
            echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="bookmarksGrid">';
            
            foreach ($bookmarked_projects as $project) {
                // Set default image if none available
                $projectImage = !empty($project['thumbnail']) ? "/gyaanuday/uploads/". htmlspecialchars($project['thumbnail']) : "https://dashboard.codeparrot.ai/api/image/Z90sbsNZNkcbc4lS/image-20.png";
                
                echo '<div class="bg-white rounded-md border border-[#bdc1ca] p-4 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="cursor-pointer project-card flex-grow" data-project-id="' . $project['id'] . '">
                        <img src="' . $projectImage . '" alt="' . htmlspecialchars($project['title']) . '" class="w-full h-40 object-cover rounded-lg mb-4">
                        <h2 class="text-[#171a1f] text-lg font-bold mb-2">' . htmlspecialchars($project['title']) . '</h2>
                        <div class="flex items-center text-[#9095a1] text-xs mb-2">
                            <i class="fas fa-user mr-1"></i>
                            <span>' . htmlspecialchars($project['username']) . '</span>
                        </div>
                        <div class="description-container overflow-hidden" style="max-height: 80px;">
                            <p class="text-[#9095a1] text-sm leading-[22px]">' . htmlspecialchars(substr($project['description'], 0, 100) . '...') . '</p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 pt-2 border-t border-gray-100">
                        <button class="bookmark-remove border border-[#f97316] text-[#f97316] py-2 px-4 rounded-md hover:bg-[#f97316] hover:text-white transition-colors" data-bookmark-id="' . $project['bookmark_id'] . '">
                            <i class="fas fa-bookmark-slash mr-1"></i> Remove
                        </button>
                    </div>
                </div>';
            }
            
            echo '</div>';
        } else {
            echo '<div class="text-center py-8 text-gray-500" id="noBookmarksMessage">
                <p>You haven\'t bookmarked any projects yet.</p>
                <a href="projects.php" class="text-[#A7D820] hover:underline mt-2 inline-block">Browse Projects</a>
            </div>';
        }
        ?>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <h2 class="text-2xl font-bold text-red-600 mb-4">Delete Account</h2>
            <p class="mb-6 text-gray-700">Are you sure you want to permanently delete your account? This action cannot be undone and all your data, projects, and activities will be removed.</p>
            
            <form action="../src/auth/delete_account.php" method="POST" class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Confirm with your password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your password"
                    >
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button 
                        type="button" 
                        id="cancelDelete" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        Delete Permanently
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script>
        // Toggle edit profile form
        document.getElementById('toggleEditForm').addEventListener('click', function() {
            const form = document.getElementById('editProfileForm');
            form.classList.toggle('hidden');
            
            // Scroll to the form when it's opened
            if (!form.classList.contains('hidden')) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
        
        // Cancel button functionality
        document.getElementById('cancelEdit').addEventListener('click', function() {
            document.getElementById('editProfileForm').classList.add('hidden');
        });
        
        // Show selected file name
        document.getElementById('profile_photo_input').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file selected';
            document.getElementById('file_name_display').textContent = fileName;
        });
        
        // Project deletion functionality
        document.querySelectorAll('.delete-project').forEach(button => {
            button.addEventListener('click', function() {
                const projectId = this.getAttribute('data-project-id');
                if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
                    window.location.href = '../src/projects/delete_project.php?id=' + projectId;
                }
            });
        });
        
        // Project card click to view details
        document.querySelectorAll('.project-card').forEach(card => {
            card.addEventListener('click', function() {
                const projectId = this.getAttribute('data-project-id');
                window.location.href = 'project_details.php?id=' + projectId;
            });
        });

        // Remove bookmark functionality
        document.querySelectorAll('.bookmark-remove').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent triggering the parent card click
                const bookmarkId = this.getAttribute('data-bookmark-id');
                const bookmarkCard = this.closest('.bg-white');
                
                if (confirm('Are you sure you want to remove this bookmark?')) {
                    // Send AJAX request to remove bookmark
                    const formData = new FormData();
                    formData.append('bookmark_id', bookmarkId);
                    
                    fetch('/gyaanuday/src/projects/remove_bookmark.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the project card from the DOM
                            bookmarkCard.remove();
                            
                            // If no more bookmarks, show the empty message
                            const bookmarksGrid = document.getElementById('bookmarksGrid');
                            if (!bookmarksGrid || bookmarksGrid.children.length === 0) {
                                const bookmarkedSection = document.getElementById('bookmarkedProjectsSection');
                                bookmarkedSection.innerHTML = `
                                    <h1 class="text-center text-[32px] text-[#171a1f] font-archivo mb-12 leading-[48px]">Bookmarked Projects</h1>
                                    <div class="text-center py-8 text-gray-500" id="noBookmarksMessage">
                                        <p>You haven't bookmarked any projects yet.</p>
                                        <a href="projects.php" class="text-[#A7D820] hover:underline mt-2 inline-block">Browse Projects</a>
                                    </div>
                                `;
                            }
                        } else {
                            alert('Failed to remove bookmark: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                }
            });
        });

        // Delete account modal functionality
        const deleteAccountBtn = document.getElementById('deleteAccountBtn');
        const deleteAccountModal = document.getElementById('deleteAccountModal');
        const cancelDelete = document.getElementById('cancelDelete');
        
        deleteAccountBtn.addEventListener('click', function() {
            deleteAccountModal.classList.remove('hidden');
        });
        
        cancelDelete.addEventListener('click', function() {
            deleteAccountModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === deleteAccountModal) {
                deleteAccountModal.classList.add('hidden');
            }
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