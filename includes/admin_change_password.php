<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../login.php");
  exit();
}

$username = $_SESSION['username'];
$current_input = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

// Step 1: Get current hashed password from DB
$result = $conn->query("SELECT password FROM users WHERE username='$username'");
$row = $result->fetch_assoc();
$current_hashed = $row['password'];

// ✅ Step 2: Verify password using password_verify
if (!password_verify($current_input, $current_hashed)) {
  header("Location: ../admin_dashboard.php?tab=users&msg=❌ Incorrect current password&type=warning");
  exit();
}

// Step 3: Check if new and confirm match
if ($new !== $confirm) {
  header("Location: ../admin_dashboard.php?tab=users&msg=❌ New passwords do not match&type=warning");
  exit();
}

// Step 4: Password strength
if (strlen($new) < 6) {
  header("Location: ../admin_dashboard.php?tab=users&msg=❌ Password must be at least 6 characters&type=warning");
  exit();
}

// ✅ Step 5: Hash new password with password_hash
$new_hashed = password_hash($new, PASSWORD_DEFAULT);
$conn->query("UPDATE users SET password = '$new_hashed' WHERE username = '$username'");

// Redirect with success message
header("Location: ../admin_dashboard.php?tab=users&msg=✅ Password changed successfully&type=success");
exit();
