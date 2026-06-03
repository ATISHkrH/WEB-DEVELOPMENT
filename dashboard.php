<?php
session_start();
include "../config/db.php"; 

// SECURITY: Kick out non-admins
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// Get the commander name safely
$commander = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : "ADMIN";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMMAND CENTER | ARMORY ALPHA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #030303; color: white; overflow-x: hidden; }
        .font-header { font-family: 'Oswald', sans-serif; }

        .bg-grid {
            background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .glass-card {
            background: rgba(15, 15, 15, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        
        .input-tactical {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            outline: none;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: white;
        }
        .input-tactical:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.1);
        }
    </style>
</head>
<body class="bg-grid">

    <div class="flex min-h-screen">
        <aside class="w-72 bg-black border-r border-white/5 p-8 hidden lg:block sticky top-0 h-screen">
            <div class="mb-12">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-1 h-6 bg-cyan-500"></div>
                    <h1 class="font-header text-2xl font-bold uppercase italic tracking-tighter text-white">ARMORY<span class="text-cyan-500">OS</span></h1>
                </div>
                <p class="text-[9px] text-slate-600 font-black uppercase tracking-[0.4em]">Auth Level: Administrator</p>
            </div>
            
            <nav class="space-y-4">
                <a href="#" class="flex items-center gap-4 bg-white/5 border border-white/10 text-white px-5 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <span class="text-cyan-500">01</span> Overview
                </a>
                <a href="../index.php" class="flex items-center gap-4 hover:bg-white/5 border border-transparent hover:border-white/5 text-slate-500 hover:text-white px-5 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <span class="text-slate-800">02</span> Storefront
                </a>
                <a href="../logout.php" class="flex items-center gap-4 text-red-900 hover:text-red-500 px-5 py-4 font-black text-[10px] uppercase tracking-widest transition-all">
                    Terminate Session
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-6 md:p-12">
            
            <header class="flex justify-between items-center mb-16">
                <div>
                    <h2 class="font-header text-5xl font-bold uppercase italic tracking-tighter leading-none mb-2">SYSTEM <span class="text-cyan-500">DASHBOARD</span></h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest italic">Commander: <?= htmlspecialchars($commander) ?></p>
                </div>
            </header>

            <section class="glass-card p-10 rounded-[3rem] border-white/10 mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-1 bg-cyan-500"></div>
                    <h3 class="font-header text-2xl font-bold uppercase italic tracking-tighter">Deploy New Asset</h3>
                </div>
                
                <form action="add_product_logic.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-2">Designation</label>
                            <input type="text" name="name" placeholder="UNIT NAME" required class="input-tactical">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-2">Market Valuation</label>
                            <input type="number" name="price" placeholder="PRICE (₹)" required class="input-tactical">
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-2">Tactical Specifications (Description)</label>
                        <textarea name="description" rows="3" placeholder="ENTER ASSET LORE OR STATS..." required class="input-tactical resize-none normal-case font-medium tracking-normal"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-2">Visual Asset</label>
                            <input type="file" name="image" required class="input-tactical file:hidden cursor-pointer text-[10px] text-slate-500">
                        </div>
                        <button name="add_p" class="bg-white text-black font-black uppercase text-[11px] tracking-[0.2em] rounded-2xl hover:bg-cyan-500 hover:scale-[1.02] transition-all h-[58px] shadow-lg">
                            Authorize Deployment
                        </button>
                    </div>
                </form>
            </section>

            <section class="glass-card rounded-[3rem] overflow-hidden">
                <div class="p-10 pb-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-1 bg-purple-500"></div>
                        <h3 class="font-header text-2xl font-bold uppercase italic tracking-tighter">Live Inventory Logs</h3>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/5">
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Asset</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Designation</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Valuation</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right">Commands</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php
                            $products_query = $conn->query("SELECT * FROM products ORDER BY id DESC");
                            if($products_query):
                                while($p = $products_query->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="p-6">
                                    <img src="../<?= htmlspecialchars($p['image']) ?>" class="w-12 h-12 object-cover rounded-xl border border-white/10 shadow-lg">
                                </td>
                                <td class="p-6">
                                    <p class="font-bold text-sm uppercase italic"><?= htmlspecialchars($p['name']) ?></p>
                                    <p class="text-[9px] text-slate-500 font-mono tracking-tighter truncate max-w-[200px]">
                                        <?= htmlspecialchars($p['description']) ?>
                                    </p>
                                </td>
                                <td class="p-6 font-header text-lg">₹<?= number_format($p['price']) ?></td>
                                <td class="p-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="edit_product.php?id=<?= $p['id'] ?>" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-cyan-500 hover:text-black transition-all">Modify</a>
                                        <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('SCRAP THIS ASSET?')" class="px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-500 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">Scrap</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>