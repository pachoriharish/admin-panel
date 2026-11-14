<?php
// =====================================================================
// admin.php  -  ADMIN PANEL (add, edit and delete packages)
// Anything saved here is stored in MySQL and instantly shows on the website.
// This page is PRIVATE: only a logged-in admin can open it (see login.php).
// =====================================================================
require_once 'db.php';   // connect to the database

// ---------------------------------------------------------------
// STEP 0: Check the login. If the visitor is not logged in, send them to the login page
// ---------------------------------------------------------------
session_start();                                  // start (or continue) the visitor's session
if (!isset($_SESSION['is_admin'])) {              // "is_admin" is only set after a correct login
    header('Location: login.php');                // not logged in -> go to the login page
    exit;                                         // stop running this file
}

// ---------------------------------------------------------------
// STEP 1: Handle the DELETE button  (link looks like admin.php?delete=5)
// ---------------------------------------------------------------
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];                                  // make sure it is a number
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");  // safe SQL with a placeholder (?)
    $stmt->bind_param("i", $id);                                 // put the id in place of ?
    $stmt->execute();                                            // run it: the package is deleted
    header("Location: admin.php?msg=Package+deleted");           // go back to the admin page
    exit;                                                        // stop running this file
}

// ---------------------------------------------------------------
// STEP 2: Handle the SAVE button (form was sent with POST)
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read every form field (trim removes extra spaces)
    $id          = (int)$_POST['id'];                 // 0 = new package, otherwise = package being edited
    $title       = trim($_POST['title']);
    $destination = trim($_POST['destination']);
    $description = trim($_POST['description']);
    $image       = trim($_POST['image']);
    $price       = (float)$_POST['price'];
    $days        = (int)$_POST['days'];
    $nights      = (int)$_POST['nights'];
    $itinerary   = trim($_POST['itinerary']);

    if ($id > 0) {
        // EDIT: update the existing package
        $stmt = $conn->prepare("UPDATE packages SET title=?, destination=?, description=?, image=?, price=?, days=?, nights=?, itinerary=? WHERE id=?");
        $stmt->bind_param("ssssdiisi", $title, $destination, $description, $image, $price, $days, $nights, $itinerary, $id);
        $msg = 'Package updated';
    } else {
        // ADD: insert a brand new package
        $stmt = $conn->prepare("INSERT INTO packages (title, destination, description, image, price, days, nights, itinerary) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssdiis", $title, $destination, $description, $image, $price, $days, $nights, $itinerary);
        $msg = 'Package added';
    }
    $stmt->execute();                                  // run the query: data is saved in MySQL
    header("Location: admin.php?msg=" . urlencode($msg));  // reload the page (prevents double saving)
    exit;
}

// ---------------------------------------------------------------
// STEP 3: If "Edit" was clicked (admin.php?edit=5), load that package into the form
// ---------------------------------------------------------------
$edit = null;                                          // empty = we are adding a new package
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $eid = (int)$_GET['edit'];
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();        // the package's current data
}

// Get all packages to show in the list on the right
$packages = $conn->query("SELECT * FROM packages ORDER BY id DESC");

// A small function: print the old value inside a form box (blank when adding)
function val($edit, $key, $default = '') {
    return h($edit[$key] ?? $default);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Wanderlux</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="adminbody">

<!-- Simple top bar -->
<header class="site">
    <div class="wrap nav">
        <a href="index.php" class="logo">Wander<span>lux</span></a>
        <!-- Right side of the top bar: view website + log out -->
        <div class="topbtns">
            <a href="index.php" class="btn sm ghost">View Website</a>
            <a href="logout.php" class="btn sm book">Log out</a>
        </div>
    </div>
</header>

<main>
<div class="wrap adminwrap">
    <h1>Admin Panel</h1>

    <!-- Success message (shown after add / update / delete) -->
    <?php if (isset($_GET['msg'])): ?>
        <p class="alert"><?= h($_GET['msg']) ?></p>
    <?php endif; ?>

    <div class="admin">

        <!-- ============ LEFT: the form (Add or Edit) ============ -->
        <form class="panel" method="post" action="admin.php">
            <h2><?= $edit ? 'Edit Package' : 'Add New Package' ?></h2>

            <!-- Hidden box: 0 = new package, otherwise the id of the package being edited -->
            <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">

            <label class="label">Title</label>
            <input type="text" name="title" required value="<?= val($edit, 'title') ?>">

            <label class="label">Destination</label>
            <input type="text" name="destination" required value="<?= val($edit, 'destination') ?>">

            <label class="label">Description</label>
            <textarea name="description" rows="3"><?= val($edit, 'description') ?></textarea>

            <label class="label">Image link (URL)</label>
            <input type="text" name="image" placeholder="https://..." value="<?= val($edit, 'image') ?>">

            <label class="label">Price per person ($)</label>
            <input type="number" step="0.01" min="0" name="price" required value="<?= val($edit, 'price') ?>">

            <div class="two">
                <div>
                    <label class="label">Days</label>
                    <input type="number" min="1" name="days" required value="<?= val($edit, 'days', 5) ?>">
                </div>
                <div>
                    <label class="label">Nights</label>
                    <input type="number" min="0" name="nights" required value="<?= val($edit, 'nights', 4) ?>">
                </div>
            </div>

            <label class="label">Itinerary (write one line for each day)</label>
            <textarea name="itinerary" rows="6"><?= val($edit, 'itinerary') ?></textarea>

            <!-- Save button -->
            <button class="btn" type="submit"><?= $edit ? 'Update Package' : 'Add Package' ?></button>
            <!-- Cancel button only appears while editing -->
            <?php if ($edit): ?><a class="btn ghost" href="admin.php">Cancel</a><?php endif; ?>
        </form>

        <!-- ============ RIGHT: list of all packages ============ -->
        <div class="panel">
            <h2>All Packages</h2>
            <?php while ($p = $packages->fetch_assoc()): ?>
                <div class="row">
                    <!-- Small picture -->
                    <div class="th" style="background-image:url('<?= h($p['image']) ?>')"></div>
                    <!-- Title and details -->
                    <div class="info">
                        <b><?= h($p['title']) ?></b>
                        <?= h($p['destination']) ?> &middot; $<?= number_format($p['price']) ?> &middot; <?= (int)$p['days'] ?>D/<?= (int)$p['nights'] ?>N
                    </div>
                    <!-- Edit and Delete buttons -->
                    <div class="acts">
                        <a class="btn sm" href="admin.php?edit=<?= $p['id'] ?>">Edit</a>
                        <!-- onclick asks "Are you sure?" before deleting -->
                        <a class="btn sm danger" href="admin.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this package?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
</main>

<script src="script.js"></script>
</body>
</html>
