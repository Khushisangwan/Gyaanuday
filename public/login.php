<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Gyaanuday</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Archivo&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4 {
            font-family: 'Archivo', sans-serif;
        }

        body {
            min-height: 100vh;
            height: 100vh;
            background: url('/gyaanuday/public/images/Signup/bg.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #A7D820;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-top: 25px;
        }

        .btn-primary:hover {
            background: #95c118;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .logo {
            font-family: 'Archivo', sans-serif;
            font-size: 36px;
            font-weight: bold;
            color: #171a1f;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo i {
            color: #A7D820;
            margin-right: 12px;
            font-size: 36px;
        }

        header h2 {
            font-size: 28px;
            margin-bottom: 10px;
            font-family: 'Archivo', sans-serif;
            color: #171a1f;
            line-height: 48px;
        }

        header p {
            font-size: 16px;
            color: #565d6d;
            margin-bottom: 30px;
            line-height: 26px;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            border: none;
            outline: none;
        }

        .input-group {
            display: flex;
            align-items: center;
            border: 1px solid #bdc1ca;
            padding: 12px;
            border-radius: 8px;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within {
            border-color: #A7D820;
            box-shadow: 0 0 0 2px rgba(167, 216, 32, 0.2);
        }
        
        .input-group .icon {
            color: #565d6d;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .input-group input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            padding-left: 10px;
            font-size: 16px;
            line-height: 26px;
        }

        /* Footer */
        footer {
            margin-top: 30px;
            font-size: 14px;
        }

        footer a {
            color: #A7D820;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        footer a:hover {
            color: #95c118;
            text-decoration: underline;
        }
        
        .home-link {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            background-color: rgba(0,0,0,0.6);
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .home-link:hover {
            background-color: #A7D820;
            color: white;
        }
        
        .home-link i {
            margin-right: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <!-- Home link button -->
    <a href="index.php" class="home-link">
        <i class="fas fa-home"></i> Home
    </a>

    <div class="form-container">
        <!-- Logo/Branding -->
        <div class="logo">
            <i class="fas fa-project-diagram"></i>
            <span>Gyaanuday</span>
        </div>
        
        <!-- Header -->
        <header>
            <h2>Log In</h2>
            <p>Welcome back! Please login to your account</p>
        </header>

        <!-- Form -->
        <form method="post" action="../src/auth/process_login.php">
            <div class="input-group">
                <span class="icon"><i class="fas fa-envelope"></i></span>
                <input name="email" type="email" placeholder="Your email address" required>
            </div>

            <div class="input-group">
                <span class="icon"><i class="fas fa-lock"></i></span>
                <input name="password" type="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-primary">Log In</button>
        </form>

        <!-- Footer -->
        <footer>
            <p>Don't have an account? <a href="register.php">Sign Up</a></p>
        </footer>
    </div>
</body>
</html>