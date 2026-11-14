WANDERLUX - SIMPLE TRAVEL WEBSITE (HTML + CSS + JS + PHP + MySQL)

HOW TO RUN
1. Install XAMPP and start "Apache" and "MySQL".
2. Copy this folder into  C:\xampp\htdocs\  (Mac: /Applications/XAMPP/htdocs/).
3. Open  http://localhost/travel-site/   - the database and sample data are created automatically.
4. Open the admin panel: click "Admin" in the menu, or go to  http://localhost/travel-site/login.php
   Admin ID:  admin
   Password:  Admin123

FILES
db.php        - connects to MySQL, creates the table, helper functions
header.php    - logo + menu (included on every page)
footer.php    - footer (included on every page)
index.php     - home page
packages.php  - all packages page
login.php     - admin login page
logout.php    - logs the admin out
admin.php     - add / edit / delete packages (login required)
style.css     - design
script.js     - hamburger menu

HOW IT WORKS
Admin form  -->  PHP saves it in MySQL  -->  Home and Packages pages read it from MySQL and show it.

CHANGE THE LOGIN: open db.php and edit ADMIN_USER and ADMIN_PASS (near the top).
Database settings (user / password) are also at the top of db.php.
Before putting the site online, change the default password!
