<?php
// =====================================================================
// login.php  -  ADMIN LOGIN PAGE
// The admin types an ID and password. If they match db.php, we let them in.
// =====================================================================
require_once 'db.php';   // gives us ADMIN_USER, ADMIN_PASS and the h() helper
session_start();         // start the session (this is how the site remembers who is logged in)

// If the admin is already logged in, skip this page and open the admin panel
if (isset($_SESSION['is_admin'])) {
    header('Location: admin.php');
    exit;
}

$error = '';   // will hold an error message if the login is wrong

// Run only when the login form was sent (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = trim($_POST['id']);          // what the user typed as ID
    $pass = $_POST['password'];          // what the user typed as password

    // Compare with the correct ID and password (hash_equals is a safe way to compare text)
    if (hash_equals(ADMIN_USER, $id) && hash_equals(ADMIN_PASS, $pass)) {
        session_regenerate_id(true);     // new session id (a basic security step)
        $_SESSION['is_admin'] = true;    // remember: this visitor is the admin
        header('Location: admin.php');   // open the admin panel
        exit;
    }
    $error = 'Wrong ID or password. Please try again.';   // wrong details
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Wanderlux</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="adminbody">

<!-- Top bar with logo -->
<header class="site">
    <div class="wrap nav">
        <a href="index.php" class="logo">Wander<span>lux</span></a>
    </div>
</header>

<main>
    <div class="login">
        <h2>Admin Login</h2>

        <!-- Show the error message only if there is one -->
        <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

        <!-- Login form: sends ID and password to this same page -->
        <form method="post" action="login.php" autocomplete="off">
            <label class="label" for="id">Admin ID</label>
            <input type="text" id="id" name="id" required autofocus>

            <label class="label" for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button class="btn" type="submit">Login</button>
        </form>

        <a class="back" href="index.php">&larr; Back to website</a>
    </div>
</main>

<script src="script.js"></script>
</body>
</html>
