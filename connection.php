<?php
$server = 'https://arungz.github.io/error';
$username = 'root';
$password = '';
$database = 'Database/login_register_db';

if (isset($_POST))

    $conn = new mysqli($server, $username, $password, $database);
if ($conn) {
    // echo 'Server Connected Success';
} else {
    die(mysqli_error($conn));
}
