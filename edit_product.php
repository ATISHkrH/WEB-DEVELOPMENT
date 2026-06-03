<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];
$res = $conn->query("SELECT * FROM products WHERE id = $id");
$p = $res->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    // Simple update logic
    $conn->query("UPDATE products SET name='$name', price='$price' WHERE id=$id");
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <style>body { background: #030303; color: white; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-lg p-10 bg-white/5 border border-white/10 rounded-[3rem] backdrop-blur-xl">
        <h2 class="font-header text-4xl italic uppercase font-bold mb-8">Modify <span class="text-cyan-500">Asset</span></h2>
        
        <form method="POST" class="space-y-6">
            <input type="text" name="name" value="<?= $p['name'] ?>" class="w-full bg-black border border-white/10 p-5 rounded-2xl text-white font-bold outline-none focus:border-cyan-500">
            <input type="number" name="price" value="<?= $p['price'] ?>" class="w-full bg-black border border-white/10 p-5 rounded-2xl text-white font-bold outline-none focus:border-cyan-500">
            
            <div class="flex gap-4">
                <a href="dashboard.php" class="flex-1 text-center py-5 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest">Abort</a>
                <button name="update" class="flex-1 py-5 bg-white text-black rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-cyan-500 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>