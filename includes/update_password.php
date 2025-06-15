<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $newpass = md5($_POST['new_password']);

  $check = $conn->query("SELECT * FROM users WHERE username='$username'");
  if ($check->num_rows > 0) {
    $conn->query("UPDATE users SET password='$newpass' WHERE username='$username'");
    header("Location: admin_dashboard.php?tab=users&msg=Password updated for $username");
  } else {
    header("Location: admin_dashboard.php?tab=users&msg=User not found");
  }
}
?>
