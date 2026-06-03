<?php
$host = "127.0.0.1"; // Using the IP instead of 'localhost' is faster in MAMP
$user = "root";
$pass = "root";      // MAMP default password
$db   = "gaming_store";
$port = 8889;        // MAMP default MySQL port

// We add the $port variable at the end here
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    // If 8889 fails, MAMP might be set to use 3306. 
    // Try changing $port to 3306 if this triggers.
    die("Uplink Failed: " . $conn->connect_error);
}
?>