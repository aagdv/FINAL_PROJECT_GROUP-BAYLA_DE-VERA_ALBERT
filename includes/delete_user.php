<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = intval($_POST['id']);

  // Prevent admin from deleting their own account
  $check = $conn->query("SELECT username FROM users WHERE id = $id")->fetch_assoc();
  if ($check && $check['username'] === $_SESSION['username']) {
    header("Location: ../admin_dashboard.php?tab=users&msg=You cannot delete your own account&type=danger");
    exit();
  }

  $conn->query("DELETE FROM users WHERE id = $id");
  header("Location: ../admin_dashboard.php?tab=users&msg=User deleted successfully&type=success");
  exit();
}
?>
