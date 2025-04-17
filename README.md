
# Gyaanuday Project

Gyaanuday is a platform that allows users to submit their projects, search for others' projects, and engage with them through likes. The platform also supports user authentication, project management, and profile customization.

## Features

- **User Registration & Login**: Secure user authentication system.
- **Project Submission**: Users can submit their projects with names, descriptions, and media (PDFs, images, videos, audio).
- **Search Functionality**: Users can search for projects based on keywords.
- **Like Feature**: Users can like projects to show their appreciation.
- **Profile Customization**: Users can upload profile pictures and update their bios.

## File Structure


/gyanuday  
│── /public  
│   ├── index.php          # Homepage (Project listings, search bar)  
│   ├── login.php          # User login page  
│   ├── register.php       # User registration page  
│   ├── submit_project.php # Project submission page  
│   ├── scripts.js         # JavaScript for frontend interactions  
│   ├── styles.css         # Tailwind CSS styles  
│── /src  
│   ├── auth/  
│   │   ├── process_register.php  # Handles registration logic  
│   │   ├── process_login.php     # Handles login logic  
│   │   ├── logout.php            # Logs out users  
│   ├── projects/  
│   │   ├── add_project.php       # Handles project submissions  
│   │   ├── get_projects.php      # Fetches projects for search  
│   │   ├── like_project.php      # Handles likes  
│── /config  
│   ├── database.php       # Database connection  
│── /uploads  
│   ├── profile_photos/    # Stores user profile photos  
│── .env                   # Environment variables (optional)  
│── .htaccess              # Rewrite rules (optional)  
│── README.md              # Project Documentation  

## Setup

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd gyaanuday
   ```

2. Install dependencies (if any, such as PHP libraries).

3. Set up your `.env` file with the necessary environment variables for database connections.

4. Start your local server (e.g., using XAMPP for PHP and MySQL):

   ```bash
   sudo systemctl start apache2
   sudo systemctl start mysql
   ```

5. Access the application by navigating to `localhost` in your browser.


## Contributions

Contributions are welcome! Please fork this repository, create a new branch, and submit a pull request with your changes.


