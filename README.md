gaming-store/               <-- This is your Root Repository folder
│
├── database.sql            <-- Exported from phpMyAdmin (Optional, but good for backup)
├── index.php               <-- Storefront Catalog
├── product_view.php        <-- Single Product View
├── cart.php                <-- Shopping Cart Loadout
├── add_to_cart.php         <-- Cart handling logic
├── login.php               
├── register.php            
├── logout.php              
│
├── config/                 <-- Database Connection Configuration
│   └── db.php
│
├── admin/                  <-- Secure Dashboard Command Center
│   ├── dashboard.php
│   ├── add_product_logic.php
│   ├── edit_product.php
│   └── delete_product.php
│
└── uploads/                <-- Product images go here
