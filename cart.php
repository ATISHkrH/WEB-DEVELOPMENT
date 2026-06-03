<?php
session_start();
include "config/db.php";

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $results = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $results->fetch_assoc()) {
        $row['qty'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['qty'];
        $cart_items[] = $row;
        $total_price += $row['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>LOADOUT | ARMORY ALPHA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #030303; color: white; }
        .font-header { font-family: 'Oswald', sans-serif; }
    </style>
</head>
<body class="p-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-header text-5xl mb-12 uppercase italic tracking-tighter">Current <span class="text-cyan-500">Loadout</span></h1>

        <?php if (empty($cart_items)): ?>
            <div class="border border-dashed border-white/10 p-20 text-center rounded-[3rem]">
                <p class="text-slate-500 uppercase tracking-widest font-black text-xs">No assets deployed in current loadout.</p>
                <a href="index.php" class="text-cyan-500 underline mt-4 inline-block text-[10px] uppercase font-bold">Return to Armory</a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($cart_items as $item): ?>
                <div class="bg-white/5 border border-white/10 p-6 rounded-3xl flex justify-between items-center">
                    <div class="flex items-center gap-6">
                        <img src="<?= $item['image'] ?>" class="w-16 h-16 object-cover rounded-xl border border-white/10">
                        <div>
                            <h4 class="font-bold uppercase italic text-sm"><?= $item['name'] ?></h4>
                            <p class="text-slate-500 text-[10px]">QTY: <?= $item['qty'] ?> × ₹<?= number_format($item['price']) ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-header text-xl text-cyan-500">₹<?= number_format($item['subtotal']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-12 flex justify-between items-end border-t border-white/10 pt-8">
                <div>
                    <p class="text-slate-500 text-[10px] uppercase font-black tracking-widest">Total Valuation</p>
                    <p class="font-header text-6xl text-white">₹<?= number_format($total_price) ?></p>
                </div>
                <button class="bg-cyan-500 text-black px-12 py-5 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-white transition-all">
                    Initiate Checkout
                </button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>