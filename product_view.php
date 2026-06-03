<?php 
session_start(); 
include "config/db.php"; 

// 1. SECURE ID HANDLING
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. SECURE CART KEY (Matches your index.php logic)
$user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 'guest';
$cart_key = "cart_" . $user_id;

// 3. FETCH PRODUCT WITH ERROR PROTECTION
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $product = $res->fetch_assoc();
} else {
    // If product doesn't exist, don't crash, just redirect
    header("Location: index.php");
    exit();
}

// 4. GET EXTRA IMAGES (Optional feature)
// Added a check to see if the table exists to prevent crashes
$extra_images = $conn->query("SHOW TABLES LIKE 'product_images'")->num_rows > 0 
    ? $conn->query("SELECT * FROM product_images WHERE product_id = $id") 
    : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> | ARMORY ALPHA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #030303; color: white; }
        .font-header { font-family: 'Oswald', sans-serif; }
        .bg-grid {
            background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .glass-panel { background: rgba(15, 15, 15, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .image-hud { position: relative; overflow: hidden; border-radius: 2.5rem; border: 1px solid rgba(255, 255, 255, 0.1); }
        .image-hud::after { content: ""; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 40%); pointer-events: none; }
    </style>
</head>
<body class="bg-grid min-h-screen">

    <nav class="max-w-7xl mx-auto px-8 py-10 flex justify-between items-center">
        <a href="index.php" class="flex items-center gap-2 text-slate-500 hover:text-white transition-all font-black text-[10px] uppercase tracking-[0.3em]">
            <span class="text-xl">←</span> Return to Armory
        </a>
        
        <div class="flex items-center gap-6">
            <a href="cart.php" class="relative group">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-cyan-500 transition-colors">
                    Loadout (<?= isset($_SESSION[$cart_key]) ? array_sum($_SESSION[$cart_key]) : 0 ?>)
                </p>
                <div class="absolute -bottom-1 left-0 w-0 h-[1px] bg-cyan-500 group-hover:w-full transition-all"></div>
            </a>
            <div class="hidden md:block text-right border-l border-white/10 pl-6">
                <p class="text-[8px] font-black text-slate-600 uppercase tracking-widest">System Status</p>
                <p class="text-[10px] text-cyan-500 font-mono tracking-tighter">SECURE_UPLINK_STABLE</p>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 pb-20">
        
        <div class="lg:col-span-7 space-y-6">
            <div class="image-hud bg-black group">
                <img src="<?= $product['image'] ?>" id="main-img" class="w-full h-[650px] object-cover transition-transform duration-700 group-hover:scale-105">
                
                <div class="absolute top-8 left-8 z-10">
                    <div class="bg-black/50 backdrop-blur-md border border-white/10 px-4 py-2 rounded-full text-[10px] font-black tracking-widest uppercase">
                        Asset ID: #<?= str_pad($product['id'], 4, '0', STR_PAD_LEFT) ?>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 p-2 overflow-x-auto">
                <div onclick="document.getElementById('main-img').src=this.querySelector('img').src" class="flex-shrink-0 cursor-pointer group">
                    <img src="<?= $product['image'] ?>" class="w-24 h-24 object-cover rounded-2xl border-2 border-cyan-500 transition-all">
                </div>
                <?php if($extra_images): while($img = $extra_images->fetch_assoc()): ?>
                    <div onclick="document.getElementById('main-img').src=this.querySelector('img').src" class="flex-shrink-0 cursor-pointer group">
                        <img src="<?= $img['image_path'] ?>" class="w-24 h-24 object-cover rounded-2xl border border-white/10 group-hover:border-white/40 transition-all">
                    </div>
                <?php endwhile; endif; ?>
            </div>
        </div>

        <div class="lg:col-span-5 flex flex-col justify-center space-y-10">
            
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-[1px] w-12 bg-cyan-500"></div>
                    <span class="text-cyan-500 font-black text-[10px] uppercase tracking-[0.4em]">Hardware Specification</span>
                </div>
                <h1 class="font-header text-7xl font-bold uppercase italic tracking-tighter leading-[0.9] mb-4">
                    <?= htmlspecialchars($product['name']) ?>
                </h1>
                <p class="text-5xl font-header italic text-white/90">₹<?= number_format($product['price']) ?></p>
            </div>

            <div class="glass-panel p-8 rounded-[2.5rem] relative overflow-hidden">
                <h3 class="text-slate-500 text-[9px] font-black uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                    <span class="w-1 h-3 bg-cyan-500"></span> Field intel / Background
                </h3>
                <p class="text-slate-300 text-sm leading-relaxed font-medium italic">
                    <?= !empty($product['description']) ? nl2br(htmlspecialchars($product['description'])) : "Data stream corrupted. Full specifications for this unit remain classified." ?>
                </p>
            </div>

            <div class="space-y-4">
                <a href="add_to_cart.php?id=<?= $product['id'] ?>&return=view" class="block w-full group">
                    <button class="w-full relative py-6 bg-white text-black font-black uppercase text-[12px] tracking-[0.3em] rounded-2xl overflow-hidden transition-all shadow-2xl hover:shadow-cyan-500/20 active:scale-[0.98]">
                        <span class="relative z-10 group-hover:text-white transition-colors">Acquire Asset</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-purple-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                </a>

                <?php if(isset($_GET['status']) && $_GET['status'] == 'added'): ?>
                    <div class="bg-cyan-500/10 border border-cyan-500/20 py-3 rounded-xl text-center">
                        <p class="text-cyan-500 text-[9px] font-black uppercase tracking-widest animate-pulse">✓ Asset successfully deployed to loadout</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pt-8 border-t border-white/5 flex justify-between items-center opacity-40">
                <div class="text-[8px] font-black uppercase tracking-widest">Availability: <span class="text-green-500 underline">In Stock</span></div>
                <div class="text-[8px] font-black uppercase tracking-widest">Delivery: Worldwide Tactical</div>
            </div>
        </div>
    </div>

</body>
</html>