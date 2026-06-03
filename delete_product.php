<?php
session_start();
include "../config/db.php";

// 1. SECURITY: Verify Admin Status
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// 2. LOGIC: Handle Deletion
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Get the image path first so we can delete the physical file
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

    if($product){
        $image_path = "../" . $product['image'];
        
        // Delete physical file if it exists
        if(file_exists($image_path)){
            unlink($image_path);
        }

        // Delete from Database
        $del_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $del_stmt->bind_param("i", $id);
        
        if($del_stmt->execute()){
            // Success: Return to dashboard
            header("Location: dashboard.php?status=scrapped");
        } else {
            echo "CRITICAL ERROR: Failed to scrap asset.";
        }
    }
} else {
    header("Location: dashboard.php");
}
exit();