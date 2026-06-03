<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product or increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect back to the product page with a success flag
    header("Location: product_view.php?id=$product_id&status=added");
    exit();
}