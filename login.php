<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: login_register_db.sql");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Input validation
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: index.php");
        exit();
    }

    // Brute force protection
    if (isIPBlocked($_SERVER['REMOTE_ADDR'])) {
        $_SESSION['error'] = "Too many login attempts. Please try again later.";
        header("Location: index.php");
        exit();
    }

    // Database connection and login logic
    // (Assuming you have a function authenticateUser() to check username and password against the database)
    if (authenticateUser($username, $password)) {
        $_SESSION['username'] = $username;

        // Set a cookie if "remember me" is checked
        if ($remember_me) {
            $token = generateToken(); // Function to generate a unique token
            setcookie("remember_token", $token, time() + (30 * 24 * 60 * 60), "/", "", true, true); // Cookie expires in 30 days, secure and HttpOnly flags set
            // Store the token in the database associated with the user for validation
            storeTokenInDatabase($username, $token); // Function to store token in the database
        }

        header("Location: login_register_db.sql");
        exit();
    } else {
        // Increment failed login attempts count
        incrementFailedLoginAttempts($_SERVER['REMOTE_ADDR']);

        $_SESSION['error'] = "Invalid username or password.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
