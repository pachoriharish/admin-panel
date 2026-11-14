<?php
// =====================================================================
// logout.php  -  Logs the admin out
// =====================================================================
session_start();       // open the current session
session_destroy();     // delete it (the admin is no longer logged in)
header('Location: login.php');   // go back to the login page
exit;
