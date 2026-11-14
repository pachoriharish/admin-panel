<?php
// =====================================================================
// index.php  -  HOME PAGE
// =====================================================================
$title = 'Home';        // page name for the browser tab
$page  = 'home';        // highlights "Home" in the menu
include 'header.php';   // top of the page (logo + menu)

// Get the 3 newest packages from the database (newest = biggest id)
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC LIMIT 3");
?>

<!-- ================= HERO SECTION ================= -->
<section class="hero">
    <div class="wrap hero-grid">
        <div>
            <span class="label">Luxury Travel &amp; Tours</span>
            <h1>Discover the world, <em>beautifully</em> curated</h1>
            <p>Handcrafted itineraries, boutique stays and private guides - your next unforgettable journey starts here.</p>
            <!-- Button goes to the Packages page -->
            <a href="packages.php" class="btn">Explore Packages</a>
        </div>
        <!-- Hero picture -->
        <img class="hero-img" src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=70" alt="Tropical beach">
    </div>
</section>

<!-- ================= LATEST PACKAGES (read from MySQL) ================= -->
<section class="section">
    <div class="wrap">
        <div class="head">
            <span class="label">Latest Packages</span>
            <h2>Popular Journeys</h2>
        </div>

        <div class="grid">
            <?php
            $i = 0;                                   // card counter
            while ($p = $result->fetch_assoc()) {     // go through each package row
                show_card($p, $i++);                  // print it as a card
            }
            if ($i == 0) echo '<p class="empty">No packages yet. Add one from the admin panel.</p>';
            ?>
        </div>

        <p class="center"><a href="packages.php" class="btn">View All Packages</a></p>
    </div>
</section>

<!-- ================= WHY US ================= -->
<section class="section alt">
    <div class="wrap">
        <div class="head"><span class="label">Why Wanderlux</span><h2>Travel without compromise</h2></div>
        <div class="features">
            <div class="feat"><h3>Tailored Trips</h3><p>Every day designed around your pace and passions.</p></div>
            <div class="feat"><h3>Handpicked Stays</h3><p>Boutique hotels we have personally checked.</p></div>
            <div class="feat"><h3>24/7 Support</h3><p>A real person on call wherever you are.</p></div>
        </div>
    </div>
</section>

<?php include 'footer.php'; // bottom of the page ?>
