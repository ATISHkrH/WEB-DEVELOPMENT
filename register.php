<?php 
include "config/db.php"; 

if(isset($_POST['register'])){
    $u = mysqli_real_escape_string($conn, $_POST['u']);
    $n = mysqli_real_escape_string($conn, $_POST['n']);
    $p = $_POST['p'];

    // Securely Hash the password
    $hashed_password = password_hash($p, PASSWORD_DEFAULT);

    // Prepare and Bind
    $stmt = $conn->prepare("INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $u, $hashed_password, $n);

    if($stmt->execute()){
        $success = "RECRUITMENT SUCCESSFUL. OPERATOR DATA UPLOADED.";
    } else {
        $error = "DATABASE ERROR: USERNAME ALREADY TAKEN OR UPLINK LOST.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RECRUITMENT | ARMORY ALPHA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #030303; color: white; overflow-x: hidden; }
        .font-header { font-family: 'Oswald', sans-serif; }

        .bg-animate {
            background: url('https://wallpaperaccess.com/full/2044383.jpg');
            background-size: cover;
            background-position: center;
            filter: grayscale(100%) brightness(15%) blur(12px);
            position: fixed; inset: 0; z-index: -1;
            transform: scale(1.1);
        }

        .scanlines {
            position: fixed; inset: 0; pointer-events: none; z-index: 50;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.2) 50%);
            background-size: 100% 4px;
        }

        .glass-card {
            background: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8);
        }

        .input-field {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.15);
        }

        .gta-shimmer {
            background: linear-gradient(90deg, #fff 0%, #a855f7 50%, #fff 100%);
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

    <a href="login.php" class="absolute top-10 left-10 flex items-center gap-2 text-slate-500 hover:text-white transition-all font-black text-[10px] uppercase tracking-[0.3em] z-[60]">
        <span class="text-lg">←</span> Back to Login
    </a>

    <div class="w-full max-w-lg glass-card p-10 md:p-12 rounded-[3.5rem] relative z-10 animate-in fade-in slide-in-from-bottom-8 duration-700">
        
        <div class="text-center mb-10">
            <div class="inline-block px-3 py-1 border border-purple-500/20 rounded-full mb-4">
                <span class="text-[8px] font-black tracking-[0.5em] text-purple-400 uppercase">Phase 6 Enrollment</span>
            </div>
            <h1 class="font-header text-5xl font-bold uppercase italic tracking-tighter mb-2">
                NEW <span class="gta-shimmer">RECRUIT</span>
            </h1>
        </div>

        <?php if(isset($success)): ?>
            <div class="mb-8 p-6 bg-cyan-500/10 border border-cyan-500/20 rounded-3xl text-center">
                <p class="text-cyan-400 text-[10px] font-black uppercase tracking-widest mb-4">✅ <?= $success ?></p>
                <a href="login.php" class="inline-block w-full py-4 bg-cyan-500 text-black font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-white transition-all">Proceed to Identification</a>
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-[10px] font-black uppercase tracking-widest text-center animate-pulse">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2">Operator Name</label>
                    <input name="n" type="text" required placeholder="Ghost Riley"
                        class="w-full input-field p-5 rounded-2xl outline-none text-sm font-bold placeholder:text-slate-800">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2">Unique ID</label>
                    <input name="u" type="text" required placeholder="ALPHA_01"
                        class="w-full input-field p-5 rounded-2xl outline-none text-sm font-bold placeholder:text-slate-800">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2">Security Passcode</label>
                <input name="p" type="password" required placeholder="••••••••"
                    class="w-full input-field p-5 rounded-2xl outline-none text-sm tracking-widest placeholder:text-slate-800">
            </div>

            <div class="pt-6">
                <button name="register" type="submit" 
                    class="w-full group relative py-6 bg-white text-black font-black uppercase text-[11px] tracking-[0.3em] rounded-2xl overflow-hidden transition-all shadow-2xl active:scale-95">
                    <span class="relative z-10 group-hover:text-white transition-colors">Initialize Profile</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
            </div>
        </form>

        <div class="mt-10 text-center">
            <p class="text-[10px] text-slate-600 font-black uppercase tracking-widest">
                Already registered? <a href="login.php" class="text-slate-300 hover:text-purple-400 transition-colors">Return to Terminal</a>
            </p>
        </div>
    </div>

    <div class="absolute top-10 right-10 opacity-10 hidden lg:block">
        <div class="w-32 h-32 border-t border-r border-white/30 rounded-tr-[3rem]"></div>
    </div>
    <div class="absolute bottom-10 left-10 opacity-10 hidden lg:block">
        <div class="w-32 h-32 border-b border-l border-white/30 rounded-bl-[3rem]"></div>
    </div>

</body>
</html>