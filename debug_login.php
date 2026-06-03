<?php
include "config/db.php";

echo "<h2>🔍 System Diagnostic</h2>";

// 1. Check Connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
} else {
    echo "✅ Database Connected Successfully.<br>";
}

// 2. Check Table Structure
$result = $conn->query("DESCRIBE users");
echo "<h3>Table Structure:</h3><table border='1'><tr><th>Field</th><th>Type</th></tr>";
$pass_ok = false;
while($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
    if($row['Field'] == 'password' && strpos($row['Type'], '255') !== false) $pass_ok = true;
}
echo "</table>";

if(!$pass_ok) {
    echo "<p style='color:red'>⚠️ ERROR: Your 'password' column is too short. It must be VARCHAR(255).</p>";
}

// 3. Check for the Admin User
$user_check = $conn->query("SELECT * FROM users WHERE username = 'admin'");
if($user_check->num_rows > 0) {
    $u = $user_check->fetch_assoc();
    echo "<h3>Admin User Found!</h3>";
    echo "Username: " . $u['username'] . "<br>";
    echo "Role: " . $u['role'] . "<br>";
    echo "Hash in DB: <code>" . $u['password'] . "</code><br>";
    
    // 4. Test the Password Matching
    $test_pass = "password123";
    if(password_verify($test_pass, $u['password'])) {
        echo "<h3 style='color:green'>✅ SUCCESS: Password 'password123' matches this hash!</h3>";
    } else {
        echo "<h3 style='color:red'>❌ FAILURE: Password does not match. The hash in your database is incorrect.</h3>";
    }
} else {
    echo "<h3 style='color:red'>❌ ERROR: No user named 'admin' found in the database.</h3>";
}
?>