<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect back to login page (index.php in root folder)
header("Location: ../index.php?msg=Logged out successfully&type=success");
exit();
