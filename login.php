<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
        }
        
        
        .container {
            width: 350px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .login-form {
            margin-top: 20px;
        }
        
        .login-form input[type="text"], .login-form input[type="password"] {
            width: 90%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            padding-left: 40px;
            box-sizing: border-box;
        }
        
        .login-form input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .login-form input[type="submit"]:hover {
            background-color: #3e8e41;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .login-form .fa {
            position: absolute;
            margin-left: 10px;
            margin-top: 22px;
            color: #666;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login-form">
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Username" id="username">
            </div>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" id="password">
            </div>
            <input type="submit" value="Login">
            <p id="error-message" class="error-message"></p>
        </form>
    </div>
    
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        // Validate username and password
        if ($username == "admin" && $password == "pass") {
            // Login successful, redirect to data log page
            $_SESSION['logged_in'] = true;
            header('Location: protected.php');
            exit;
        } else {
            // Login failed, display error message
            echo '<script>document.getElementById("error-message").innerText = "Invalid username or password";</script>';
        }
    }
    ?>
    
    <script>
        // If URL has error parameter, display error message
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error')) {
            document.getElementById("error-message").innerText = "Invalid username or password";
        }
    </script>
</body>
</html>