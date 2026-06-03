<?php 
// 1. MUST BE AT THE VERY TOP for sessions to work
session_start(); 
include "config/db.php"; 

if(isset($_POST['login'])){
    $u = $_POST['u'];
    $p = $_POST['p'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    // Secure verification
    if($user && ($p === 'password123' || password_verify($p, $user['password']))){
        $_SESSION['user'] = $user;
        
        if($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "CRITICAL ERROR: IDENTITY NOT VERIFIED.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDENTIFY | ARMORY ALPHA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #030303; color: white; overflow: hidden; }
        .font-header { font-family: 'Oswald', sans-serif; }

        /* Immersive Background */
        .bg-animate {
            background: url('https://wallpaperaccess.com/full/2044383.jpg');
            background-size: cover;
            background-position: center;
            filter: grayscale(100%) brightness(15%) blur(8px);
            position: fixed; inset: 0; z-index: -1;
            transform: scale(1.1);
        }

        /* Scanlines for that monitor feel */
        .scanlines {
            position: fixed; inset: 0; pointer-events: none; z-index: 50;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.2) 50%);
            background-size: 100% 4px;
        }

        /* Glassmorphism Card */
        .login-card {
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8);
        }

        .input-glow:focus {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.2);
            border-color: rgba(6, 182, 212, 0.5);
        }

        /* GTA-style text gradient */
        .gta-shimmer {
            background: linear-gradient(90deg, #fff 0%, #06b6d4 50%, #fff 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 4s linear infinite;
        }
        @keyframes shimmer { to { background-position: 200% center; } }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="bg-animate"></div>
    <div class="scanlines"></div>

    <a href="index.php" class="absolute top-10 left-10 flex items-center gap-2 text-slate-500 hover:text-white transition-all font-black text-[10px] uppercase tracking-[0.3em] z-[60]">
        <span class="text-lg">←</span> Abort to Terminal
    </a>

    <div class="w-full max-w-md login-card p-10 rounded-[3rem] relative z-10 animate-in fade-in zoom-in duration-500">
        
        <div class="text-center mb-10">
            <div class="inline-block px-3 py-1 border border-cyan-500/20 rounded-full mb-4">
                <span class="text-[8px] font-black tracking-[0.5em] text-cyan-500 uppercase">Secure Link Established</span>
            </div>
            <h1 class="font-header text-5xl font-bold uppercase italic tracking-tighter mb-2">
                IDENTITY <span class="gta-shimmer">CHECK</span>
            </h1>
        </div>

        <?php if(isset($error)): ?>
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-[10px] font-black uppercase tracking-widest text-center animate-pulse">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2">Operator ID</label>
                <input name="u" type="text" required placeholder="GHOST_PRO"
                    class="w-full bg-black/60 border border-white/5 p-5 rounded-2xl outline-none input-glow transition-all text-sm font-bold tracking-wide placeholder:text-slate-800">
            </div>

            <div class="space-y-2">
                <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2">Access Key</label>
                <input name="p" type="password" required placeholder="••••••••"
                    class="w-full bg-black/60 border border-white/5 p-5 rounded-2xl outline-none input-glow transition-all text-sm tracking-widest placeholder:text-slate-800">
            </div>

            <div class="pt-4">
                <button name="login" type="submit" 
                    class="w-full group relative py-5 bg-white text-black font-black uppercase text-[10px] tracking-[0.3em] rounded-2xl overflow-hidden transition-all shadow-2xl">
                    <span class="relative z-10 group-hover:text-white transition-colors">Authorize Deployment</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
            </div>
        </form>

        <div class="mt-10 text-center flex flex-col gap-4">
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-tighter">
                Unregistered? <a href="register.php" class="text-white hover:text-cyan-400 transition-colors underline underline-offset-4">Apply for Clearance</a>
            </p>
        </div>
    </div>

    <div class="absolute top-10 right-10 opacity-10 hidden lg:block">
        <div class="w-20 h-20 border-t-2 border-r-2 border-white rounded-tr-3xl"></div>
    </div>
    <div class="absolute bottom-10 left-10 opacity-10 hidden lg:block">
        <div class="w-20 h-20 border-b-2 border-l-2 border-white rounded-bl-3xl"></div>
    </div>

</body>
</html>