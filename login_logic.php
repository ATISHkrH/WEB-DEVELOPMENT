<?php
include "../config/db.php"; // Adjust path if needed
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // This MUST match the 'name' attribute in login.php
    $password = $_POST['password'];

    // Secure Query
    $res = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: ../index.php"); // Redirect to home on success
        exit();
    } else {
        header("Location: ../login.php?error=1"); // Redirect back with error
        exit();
    }
}
?>