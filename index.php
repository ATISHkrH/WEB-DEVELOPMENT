<?php 
session_start(); 
include "config/db.php"; 

// 1. SECURE CART LOGIC: 
$user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 'guest';
$cart_key = "cart_" . $user_id;

if(!isset($_SESSION[$cart_key])) {
    $_SESSION[$cart_key] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARMORY ALPHA | PHASE 6</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #030303; color: white; overflow-x: hidden; }
        .font-header { font-family: 'Oswald', sans-serif; }
        .scanlines { position: fixed; inset: 0; pointer-events: none; z-index: 50; background: linear-gradient(to bottom, rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.2) 50%); background-size: 100% 4px; }
        .hero-title { font-size: clamp(4rem, 15vw, 12rem); line-height: 0.85; letter-spacing: -0.02em; }
        .gta-text-shimmer { background: linear-gradient(90deg, #fff 0%, #06b6d4 50%, #fff 100%); background-size: 200% auto; -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: shimmer 4s linear infinite; }
        @keyframes shimmer { to { background-position: 200% center; } }
        .bento-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); }
        .bento-card:hover { border-color: rgba(6, 182, 212, 0.5); transform: translateY(-5px); }
        .input-tactical { background: rgba(0, 0, 0, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 0.75rem; outline: none; font-size: 0.75rem; font-weight: 700; transition: all 0.3s; }
        .input-tactical:focus { border-color: #06b6d4; box-shadow: 0 0 15px rgba(6, 182, 212, 0.1); }
    </style>
</head>
<body class="selection:bg-cyan-500">
    <div class="scanlines"></div>

    <nav class="fixed top-0 w-full z-[100] border-b border-white/5 bg-black/80 backdrop-blur-xl">
        <div class="max-w-[1600px] mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-2 group cursor-pointer" onclick="window.location.href='index.php'">
                <div class="w-2 h-6 bg-cyan-500"></div>
                <span class="font-header text-2xl font-bold uppercase italic tracking-tighter">Armory<span class="text-white">Alpha</span></span>
            </div>
            <div class="flex items-center gap-6">
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                    <a href="admin/dashboard.php" class="hidden md:flex items-center gap-2 px-4 py-2 border border-cyan-500/30 rounded-lg bg-cyan-500/5 hover:bg-cyan-500 hover:text-black transition-all group">
                        <span class="text-[9px] font-black uppercase tracking-widest text-cyan-500 group-hover:text-black">Overview</span>
                    </a>
                <?php endif; ?>
                <a href="cart.php" class="font-black text-[10px] tracking-[0.3em] uppercase hover:text-cyan-400">
                    Cart (<?= array_sum($_SESSION[$cart_key]) ?>)
                </a>
                <?php if(!isset($_SESSION['user'])): ?>
                    <a href="login.php" class="bg-white text-black px-6 py-2 rounded-full font-black text-[10px] uppercase hover:invert">Identify</a>
                <?php else: ?>
                    <a href="logout.php" class="text-[10px] font-black text-red-500 uppercase tracking-widest">Terminate</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="relative min-h-screen flex flex-col items-center justify-center pt-20 text-center">
        <div class="inline-block px-4 py-1 border border-cyan-500/30 rounded-full mb-8">
            <span class="text-[10px] font-black tracking-[0.4em] text-cyan-500 uppercase">System Status: Active</span>
        </div>
        <h1 class="font-header hero-title font-bold uppercase tracking-tighter mb-6">
            <span class="block text-white italic">LIMITLESS</span> <span class="gta-text-shimmer">POWER</span>
        </h1>
        <a href="#store" class="px-10 py-4 bg-cyan-600 font-black uppercase text-xs tracking-widest hover:bg-white hover:text-black transition-all">Browse Armory</a>
    </header>

    <main id="store" class="max-w-[1600px] mx-auto px-6 pb-40">
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
        <section class="mb-20 p-10 bento-card rounded-[2.5rem] border-cyan-500/20">
             <div class="flex items-center gap-3 mb-8">
                <div class="w-8 h-1 bg-cyan-500"></div>
                <h3 class="font-header text-2xl font-bold uppercase italic">Quick Gear Deployment</h3>
             </div>
             <form action="admin/add_product_logic.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <input type="text" name="name" placeholder="UNIT NAME" required class="input-tactical">
                    <input type="number" name="price" placeholder="VALUATION (₹)" required class="input-tactical">
                    <input type="file" name="image" required class="input-tactical file:hidden text-[10px] cursor-pointer">
                </div>
                <textarea name="description" placeholder="UNIT SPECIFICATIONS" required class="w-full input-tactical h-32 resize-none"></textarea>
                <button name="add_p" class="w-full bg-white text-black font-black py-5 rounded-2xl uppercase text-xs tracking-[0.3em] hover:bg-cyan-500 transition-all shadow-xl">Authorize System Upload</button>
             </form>
        </section>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8">
            <?php
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            if($res && $res->num_rows > 0):
                $counter = 0;
                while($r = $res->fetch_assoc()):
                    $counter++;
                    $colSpan = ($counter % 3 == 0) ? 'lg:col-span-8' : 'lg:col-span-4';
            ?>
                <div class="<?= $colSpan ?> bento-card rounded-[2rem] group overflow-hidden flex flex-col">
                    <div class="relative h-[400px] overflow-hidden">
                        <img src="<?= htmlspecialchars($r['image']) ?>" class="absolute inset-0 w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#030303] via-transparent"></div>
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-8 gap-4">
                            <div>
                                <h3 class="font-header text-3xl font-bold uppercase italic leading-none mb-2"><?= htmlspecialchars($r['name']) ?></h3>
                                <p class="text-slate-500 text-xs italic"><?= htmlspecialchars($r['description']) ?></p>
                            </div>
                            <span class="text-2xl font-black text-cyan-500 italic">₹<?= number_format($r['price']) ?></span>
                        </div>
                        <div class="flex gap-3">
                            <a href="product_view.php?id=<?= $r['id'] ?>" class="flex-1 py-4 border border-white/10 rounded-xl text-center text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">Specs</a>
                            <a href="add_to_cart.php?id=<?= $r['id'] ?>" class="flex-1">
                                <button class="w-full py-4 bg-white text-black rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-cyan-500 transition-all">Acquire</button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </main>

    <footer class="py-10 border-t border-white/5 text-center">
        <span class="font-header text-sm opacity-20 tracking-[0.5em]">ARMORY ALPHA // 2026</span>
    </footer>
</body>
</html>