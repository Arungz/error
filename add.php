<?php
include_once('connection.php');

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input fields
    if(empty($name) || empty($username) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
        header('Location: register.php');
        exit();
    }

    // Hash the password securely using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO `tbl_user`(`name`, `username`, `password`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $username, $hashed_password);

    if($stmt->execute()) {
        // Registration successful
        echo "<script>alert('New user registered successfully.');</script>";
        header('Location: index.php');
        exit();
    } else {
        // Registration failed
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
        header('Location: register.php');
        exit();
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
