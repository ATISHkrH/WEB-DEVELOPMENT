<?php
session_start();
include "../config/db.php"; // Uplink to database

// SECURITY: Only Admins can deploy gear
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add_p'])){
    // Sanitize inputs for security
    $name  = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $desc  = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['description']));
    
    // 1. Handle File Upload
    $target_dir = "../uploads/";
    
    // Verify folder existence
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Auto-create folder if missing
    }

    $image_raw_name = basename($_FILES["image"]["name"]);
    $file_extension = strtolower(pathinfo($image_raw_name, PATHINFO_EXTENSION));
    
    // Create a unique tactical filename
    $file_name = "ASSET_" . time() . "." . $file_extension;
    $target_file = $target_dir . $file_name;
    
    // Path stored in DB for the storefront to read
    $db_path = "uploads/" . $file_name; 

    // Validate if it's actually an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        die("❌ ERROR: File is not a valid visual asset (image).");
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        
        // 2. Insert into Database
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
        
        // "s" = string, "d" = double/decimal, "s" = string, "s" = string
        $stmt->bind_param("sdss", $name, $price, $desc, $db_path);
        
        if($stmt->execute()){
            // Redirect back to dashboard with success status
            header("Location: dashboard.php?status=success&msg=AssetDeployed");
            exit();
        } else {
            echo "❌ DATABASE ERROR: " . $conn->error;
        }
    } else {
        echo "❌ UPLINK FAILED: Check folder permissions (chmod 777 uploads).";
    }
} else {
    // Kick back to dashboard if accessed directly
    header("Location: dashboard.php");
    exit();
}
?>