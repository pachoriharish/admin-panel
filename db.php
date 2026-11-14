<?php
// =====================================================================
// db.php  -  Connects PHP to MySQL. Every other page includes this file.
// =====================================================================

// ---- 1. Database settings (XAMPP default values) ----
$host   = 'localhost';   // where MySQL is running
$user   = 'root';        // MySQL username
$pass   = '';            // MySQL password (empty in XAMPP)
$dbname = 'travel_db';   // name of our database

// ---- Admin login details (used by login.php) ----
// Change these two lines to change the admin ID and password
define('ADMIN_USER', 'admin');      // admin ID
define('ADMIN_PASS', 'Admin123');   // admin password

// ---- 2. Connect to the MySQL server ----
$conn = new mysqli($host, $user, $pass);

// If the connection fails, stop and show a message
if ($conn->connect_error) {
    die('Could not connect to MySQL. Is MySQL running? Check the settings in db.php');
}

// ---- 3. Create the database the first time (if it does not exist) ----
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

// Select that database and set the text format so all languages work
$conn->select_db($dbname);
$conn->set_charset('utf8mb4');

// ---- 4. Create the "packages" table the first time (if it does not exist) ----
$conn->query("CREATE TABLE IF NOT EXISTS packages (
    id          INT AUTO_INCREMENT PRIMARY KEY,   -- unique number for each package
    title       VARCHAR(200) NOT NULL,            -- package name
    destination VARCHAR(200) NOT NULL,            -- place, e.g. Paris, France
    description TEXT,                             -- short description
    image       VARCHAR(500),                     -- link of the picture
    price       DECIMAL(10,2) NOT NULL,           -- price per person
    days        INT NOT NULL,                     -- number of days
    nights      INT NOT NULL,                     -- number of nights
    itinerary   TEXT                              -- one line per day
)");

// ---- 5. Add 3 sample packages if the table is empty ----
$row = $conn->query("SELECT COUNT(*) AS total FROM packages")->fetch_assoc();
if ($row['total'] == 0) {
    $conn->query("INSERT INTO packages (title, destination, description, image, price, days, nights, itinerary) VALUES
    ('Parisian Elegance', 'Paris, France', 'Art, cuisine and candlelit evenings along the Seine.',
     'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=900&q=70', 2450, 6, 5,
     'Arrive in Paris and Seine cruise\nLouvre guided tour\nDay trip to Versailles\nMontmartre and wine tasting\nEiffel Tower dinner\nDeparture'),
    ('Maldives Escape', 'Maldives', 'Overwater villas, turquoise lagoons and total serenity.',
     'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=70', 3890, 5, 4,
     'Seaplane transfer and villa check-in\nSnorkelling with manta rays\nSunset dolphin cruise\nSpa and beach dinner\nDeparture'),
    ('Japan Heritage Trail', 'Tokyo and Kyoto, Japan', 'Ancient temples, neon cities and unforgettable food.',
     'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=900&q=70', 3250, 8, 7,
     'Arrive in Tokyo\nTokyo food walk\nMount Fuji day trip\nBullet train to Kyoto\nFushimi Inari and Gion\nTea ceremony and Nara\nOsaka street food\nDeparture')");
}

// ---- 6. Helper: make text safe before printing it on a web page ----
function h($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

// ---- 7. Helper: print one package card (used on Home and Packages pages) ----
// $p = one row from the database, $i = card number (used for the one-by-one animation)
function show_card($p, $i) {
    // Turn the itinerary text into a list of days (one line = one day)
    $days = array_filter(array_map('trim', explode("\n", $p['itinerary'])));
    ?>
    <!-- One card. "--i" tells CSS how long to wait before this card appears -->
    <article class="card" style="--i:<?= $i ?>">
        <!-- Picture with a "days / nights" badge on top -->
        <div class="img">
            <img src="<?= h($p['image']) ?>" alt="<?= h($p['title']) ?>">
            <span class="tag"><?= (int)$p['days'] ?>D / <?= (int)$p['nights'] ?>N</span>
        </div>
        <div class="body">
            <span class="label"><?= h($p['destination']) ?></span>   <!-- place name -->
            <h3><?= h($p['title']) ?></h3>                            <!-- package title -->
            <p><?= h($p['description']) ?></p>                        <!-- short description -->

            <!-- Click "View itinerary" to open the day-by-day plan (no JavaScript needed) -->
            <details>
                <summary>View itinerary</summary>
                <ol>
                    <?php foreach ($days as $d): ?>
                        <li><?= h($d) ?></li>
                    <?php endforeach; ?>
                </ol>
            </details>

            <!-- Price and Enquire button -->
            <div class="foot">
                <div class="price">$<?= number_format($p['price']) ?> <small>/ person</small></div>
                <a class="btn sm" href="mailto:hello@wanderlux.example?subject=Enquiry: <?= rawurlencode($p['title']) ?>">Enquire</a>
            </div>
        </div>
    </article>
    <?php
}
