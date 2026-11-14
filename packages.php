<?php
// =====================================================================
// packages.php  -  ALL PACKAGES PAGE
// Shows every package saved from the admin panel.
// =====================================================================
$title = 'Packages';
$page  = 'packages';
include 'header.php';

// Get ALL packages from the database, newest first
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
?>

<!-- Page title area -->
<section class="page-hero">
    <div class="wrap">
        <span class="label">Latest Updates</span>
        <h1>Tour Packages &amp; Itineraries</h1>
        <p>Prices, days, nights and plans - added by our team.</p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="grid">
            <?php
            $i = 0;                                   // card counter (for animation delay)
            while ($p = $result->fetch_assoc()) {     // loop through every package
                show_card($p, $i++);                  // print the card
            }
            if ($i == 0) echo '<p class="empty">No packages yet. Add one from the admin panel.</p>';
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
