<?php
// =====================================================================
// header.php  -  Top part of every public page (logo + menu)
// Before including it, each page sets $title (page name) and $page (menu item to highlight)
// =====================================================================
require_once 'db.php';   // connect to the database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Makes the site fit phones and tablets -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title) ?> - Wanderlux Travel</title>

    <!-- Google Fonts: Playfair Display (headings) and Inter (text) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Our own styles -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Sticky header: stays at the top while you scroll -->
<header class="site">
    <div class="wrap nav">

        <!-- Logo (top-left) -->
        <a href="index.php" class="logo">
            Wander<span>lux</span>
        </a>

        <!-- Hamburger button (only visible on small screens) -->
        <button class="burger" id="burger" aria-label="Open menu"><i></i><i></i><i></i></button>

        <!-- Menu links. "active" class highlights the current page -->
        <ul class="links" id="links">
            <li><a href="index.php"    class="<?= $page == 'home' ? 'active' : '' ?>">Home</a></li>
            <li><a href="packages.php" class="<?= $page == 'packages' ? 'active' : '' ?>">Packages</a></li>
            <li><a href="login.php">Admin</a></li>   <!-- goes to the admin login page -->
            <li><a href="packages.php" class="btn sm book">Book Now</a></li>
        </ul>
    </div>
</header>

<!-- Main page content starts here (it grows to push the footer down) -->
<main>
