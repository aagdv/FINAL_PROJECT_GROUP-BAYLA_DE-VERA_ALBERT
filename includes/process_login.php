<?php
session_start();
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      // ✅ Set session variables
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['email'] = $user['email']; // ✅ Needed for ticket form

      // ✅ Redirect by role
      if ($user['role'] === 'admin') {
        header("Location: ../admin_dashboard.php");
      } else {
        header("Location: ../client_dashboard.php");
      }
      exit();
    }
  }

  // ❌ Login failed
  header("Location: ../index.php?error=Invalid+username+or+password");
  exit();
}
